<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationCode;
use App\Notifications\SendCodeVerifyNotification;
use Exception;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
  public function sendCodeVerification(Request $req)
  {
    $validator = Validator::make($req->all(), [
      "email" => "required|string|email|max:255",
    ], [
      "email.required" => "El campo email es obligatorio",
      "email.email" => "Ingresa una dirección de correo válido"
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    $user = User::where("email", $req->email)->first();

    if ($user == null) {
      return response()->json([
        "type" => "error",
        "success" => false,
        "title" => "Lo sentimos",
        "message" => "Los datos ingresados no coinciden con nuestros registros",
      ], 401);
    }

    $randomNumber = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    if ($user->verificationCode == null) {
      $user->verificationCode()->create([
        'code' => $randomNumber,
      ]);
    } else {
      $user->verificationCode()->update([
        'code' => $randomNumber,
      ]);
    }

    $messages["greeting"] = "Hola " . $user->name . " " . $user->lastname;
    $messages["message"] = "Hemos recibido una solicitud para restablecer tu contraseña por favor, 
                          verifica el código para validar tu solicitud y continuar";
    $messages["code_verify"] = "Tu código de verificación es: {$randomNumber}";

    try {
      $user->notify(new SendCodeVerifyNotification($messages));
    } catch (Exception $e) {
      return response()->json([
        "type" => "error",
        "success" => false,
        "title" => "Lo sentimos",
        "message" => "No se pudo procesar la solicitud, intenta de nuevo",
      ], 500);
    }

    return response()->json([
      "type" => "success",
      "success" => true,
    ], 200);
  }

  public function forgotPassword(Request $req)
  {
    try {
      $validator = Validator::make($req->all(), [
        "email" => "required|string|email|max:255",
        "code" => "required|numeric|min:6",
      ], [
        "email.required" => "El campo email es obligatorio",
        "email.email" => "Ingresa una dirección de correo válido",
        "code.required" => "El campo de códifo es obligatorio",
        "code.numeric" => "Solo se aceptan valores numéricos",
        "code.min" => "El código digitado debe contener 6 digitos"
      ]);

      if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
      }

      $data = User::where("email", $req->email)
        ->whereHas("verificationCode", function ($query) use ($req) {
          $query->where("code", $req->code);
        })
        ->first();

      if ($data == null) {
        return response()->json([
          "type" => "error",
          "success" => false,
          "title" => "Lo sentimos",
          "message" => "No se pudo procesar la solicitud, intenta de nuevo",
        ], 500);
      }

      $status = Password::sendResetLink(
        $req->only("email")
      );

      if ($status === Password::RESET_LINK_SENT) {
        VerificationCode::where("user_id", $data->id)->where("code", $data->verificationCode->code)->delete();

        return response()->json([
          "type" => "success",
          "success" => true,
          "title" => "Accion realizada con exito",
          "message" => "Hemos enviado un enlace a tu correo para que puedas restablecer tu contraseña"
        ], 200);
      }

      throw ValidationException::withMessages([
        "email" => [trans($status)],
      ]);
    } catch (Exception $e) {
      return response()->json([
        "type" => "error",
        "success" => false,
        "title" => "Error inesperado",
        "message" => $e->getMessage(),
      ], 500);
    }
  }

  public function resetPassword(Request $request)
  {
    $validator = Validator::make($request->all(), [
      "token" => "required",
      "email" => "required",
      "password" => ["required", "confirmed"]
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    $user = User::where("email", $request->email)->first();

    if (!$user) {
      return response()->json([
        "type" => "error",
        "success" => false,
        "title" => "Lo sentimos",
        "message" => "Los datos ingresados no coinciden con nuestros registros",
      ], 400);
    }

    $token = Password::tokenExists($user, $request->token);

    if (!$token) {
      return response()->json([
        "type" => "error",
        "success" => false,
        "title" => "Lo sentimos",
        "message" => "El token de restablecimiento de contraseña es inválido o ha expirado. Solicite un nuevo enlace de restablecimiento de contraseña.",
      ], 400);
    }

    $status = Password::reset(
      $request->only("email", "password", "password_confirmation", "token"),
      function ($user) use ($request) {
        $user->forceFill([
          'password' => Hash::make($request->password)
        ])->setRememberToken(Str::random(60));

        $user->save();

        $user->tokens()->delete();

        event(new PasswordReset($user));
      }
    );

    if ($status === Password::PASSWORD_RESET) {
      return response()->json([
        "status" => true
      ], 200);
    }

    return response()->json(["message" => __($status), "status" => false], 500);
  }
}
