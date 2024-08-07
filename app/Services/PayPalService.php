<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Traits\ConsumesExternalServices;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class PayPalService
{
  use ConsumesExternalServices;

  protected $baseUri;

  protected $clientId;

  protected $clientSecret;

  protected $plans;

  protected $user;

  public function __construct()
  {
    $this->baseUri = config('services.paypal.base_uri');
    $this->clientId = config('services.paypal.client_id');
    $this->clientSecret = config('services.paypal.client_secret');
    $this->plans = config('services.paypal.plans');
    $this->user = auth()->guard("api")->user();
  }

  public function resolveAuthorization(&$queryParams, &$formParams, &$headers)
  {
    $headers['Authorization'] = $this->resolveAccessToken();
  }

  public function decodeResponse($response)
  {
    return json_decode($response);
  }

  public function resolveAccessToken()
  {
    $credentials = base64_encode("{$this->clientId}:{$this->clientSecret}");

    return "Basic {$credentials}";
  }

  public function handlePayment(Request $request)
  {
    $total = getTotalShoppingCart();

    $referenceId = Str::uuid()->toString();

    $order = $this->createOrder($total, "usd", $referenceId);

    $orderLinks = collect($order->links);

    $approve = $orderLinks->where('rel', 'approve')->first();

    $customClaims = [
      "approval_id" => $order->id,
      "payment_platform" => $request->payment_platform,
      "reference_code" => $referenceId,
      "name" => $request->name,
      "lastname" => $request->lastname,
      "email" => $request->email,
      "phone_number" => $request->phone_number,
      "country_id" => $request->country_id,
      "department_id" => $request->department_id,
      "municipality_id" => $request->municipality_id,
    ];

    $token = tokenSign($customClaims);

    return response()->json([
      "redirect_to" => $approve->href,
      "token" => $token,
    ]);
  }

  public function handleApproval($request)
  {
    if ($request->approval_id) {
      $payment = $this->capturePayment($request->approval_id);

      $name = $request->name; /*$payment->payer->name->given_name*/
      $payment = $payment->purchase_units[0]->payments->captures[0]->amount;
      $amount = $payment->value;
      $currency = $payment->currency_code;

      $purchase = Purchase::create([
        "reference_code" => $request->reference_code,
        "name" => $name,
        "lastname" => $request->lastname,
        "email" => $request->email,
        "phone_number" => $request->phone_number,
        "total" => $amount,
        "user_id" => $this->user->id,
        "country_id" => $request->country_id,
        "department_id" => $request->department_id,
        "municipality_id" => $request->municipality_id,
      ]);

      $carts = $this->user->shopping_cart;

      foreach ($carts as $cart) {
        $items[] = [
          "course_name" => $cart["title"],
          "sale_price" => $cart["discount_price"] ?: $cart["price"],
          "regular_price" => $cart["price"],
          "image_course" => $cart["image"]
        ];
      }

      $purchase->purchase_items()->createMany($items);

      $this->user->enrolled_courses()->sync($carts->pluck("id"));

      Cart::destroy($carts->pluck("cart_id"));

      return response()->json([
        "message" => "Thanks, {$name}. We received your {$amount}{$currency} payment."
      ], Response::HTTP_OK);
    }

    return response()->json([
      "message" => "We cannot capture your payment. Try again, please"
    ], Response::HTTP_BAD_REQUEST);
  }

  public function handleSubscription(Request $request)
  {
    $subscription = $this->createSubscription(
      $request->plan,
      $request->user()->name,
      $request->user()->email
    );

    $subscriptionLinks = collect($subscription->links);

    $approve = $subscriptionLinks->where('rel', 'approve')->first();

    session()->put('subscriptionId', $subscription->id);

    return redirect($approve->href);
  }

  public function validateSubscription(Request $request)
  {
    if (session()->has('subscriptionId')) {
      $subscriptionId = session()->get('subscriptionId');

      session()->forget('subscriptionId');

      return $request->subscription_id == $subscriptionId;
    }

    return false;
  }

  public function createOrder($value, $currency, $referenceId)
  {
    return $this->makeRequest(
      'POST',
      '/v2/checkout/orders',
      [],
      [
        'intent' => 'CAPTURE',
        'purchase_units' => [
          0 => [
            'amount' => [
              'currency_code' => strtoupper($currency),
              'value' => round($value * $factor = $this->resolveFactor($currency)) / $factor,
            ]
          ]
        ],
        'application_context' => [
          'brand_name' => config('app.name'),
          'shipping_preference' => 'NO_SHIPPING',
          'user_action' => 'PAY_NOW',
          'return_url' => env("PAYMENT_CTA_URL") . "?cta=ok&ref_id=$referenceId",
          'cancel_url' => env("PAYMENT_CTA_URL") . "?cta=bad&ref_id=unk",
        ]
      ],
      [],
      $isJsonRequest = true,
    );
  }

  public function capturePayment($approvalId)
  {
    return $this->makeRequest(
      'POST',
      "/v2/checkout/orders/{$approvalId}/capture",
      [],
      [],
      [
        'Content-Type' => 'application/json',
      ],
    );
  }

  public function createSubscription($planSlug, $name, $email)
  {
    return $this->makeRequest(
      'POST',
      '/v1/billing/subscriptions',
      [],
      [
        'plan_id' => $this->plans[$planSlug],
        'subscriber' => [
          'name' => [
            'given_name' => $name,
          ],
          'email_address' => $email,
        ],
        'application_context' => [
          'brand_name' => config('app.name'),
          'shipping_preference' => 'NO_SHIPPING',
          'user_action' => 'SUBSCRIBE_NOW',
          'return_url' => route('subscribe.approval', ['plan' => $planSlug]),
          'cancel_url' => route('subscribe.cancelled'),
        ]
      ],
      [],
      $isJsonRequest = true,
    );
  }

  public function resolveFactor($currency)
  {
    $zeroDecimalCurrencies = ['JPY'];

    if (in_array(strtoupper($currency), $zeroDecimalCurrencies)) {
      return 1;
    }

    return 100;
  }
}
