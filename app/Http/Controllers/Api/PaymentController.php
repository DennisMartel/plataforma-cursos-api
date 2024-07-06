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

    return $paymenPlatform->handlePayment($req);
  }

  public function approval(Request $request)
  {
    try {
      if ($request->pay_tkn) {
        $data = decodeToken($request->pay_tkn);

        $paymenPlatform = $this->paymentPlatformResolver
          ->resolveService($data->payment_platform);

        return $paymenPlatform->handleApproval($data);
      }

      return response()->json([
        "message" => "We cannot retrieve your payment platform. Try again, please."
      ], Response::HTTP_BAD_REQUEST);
    } catch (\Exception $e) {
      return response()->json([
        "message" => $e->getMessage()
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }
}
