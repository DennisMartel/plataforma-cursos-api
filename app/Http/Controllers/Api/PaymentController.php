<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Resolvers\PaymentPlatformResolver;

class PaymentController extends Controller
{
  protected $paymentPlatformResolver;

  public function __construct(PaymentPlatformResolver $paymentPlatformResolver)
  {
    $this->paymentPlatformResolver = $paymentPlatformResolver;
  }

  public function pay(Request $req)
  {
    $validator = Validator::make($req->all(), [
      "payment_platform" => "required",
    ], [
      "payment_platform.required" => "Elige un método de pago disponible."
    ]);

    if ($validator->fails()) {
      return response()->json($validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    $paymenPlatform = $this->paymentPlatformResolver->resolveService($req->payment_platform);

    session()->put("paymentPlatformId", $req->payment_platform);

    return $paymenPlatform->handlePayment($req);
  }

  public function approval()
  {
    if (session()->has("paymentPlatformId")) {
      $paymentPlatformId = session()->get("paymentPlatformId");

      $paymenPlatform = $this->paymentPlatformResolver->resolveService($paymentPlatformId);

      return $paymenPlatform->handleApproval();
    }

    return response()->json([
      "message" => "We cannot retrieve your payment platform. Try again, plase."
    ], Response::HTTP_BAD_REQUEST);
  }
}
