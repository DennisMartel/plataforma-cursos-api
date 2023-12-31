<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
  public function __construct()
  {
    $this->middleware("auth:api", ["except" => ["signin", "signup"]]);
  }

  public function signin(Request $req)
  {
    try {
      $validator = Validator::make($req->all(), [
        "email" => "required|string|email|max:255",
        "password" => "required|string|min:8"
      ]);

      if ($validator->fails()) {
        return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
      }

      $credentials = $req->only("email", "password");

      $token = JWTAuth::attempt($credentials, $req->remember);

      try {
        if ($token == null) {
          return response()->json([
            "type" => "error",
            "success" => false,
            "isLogged" => false,
            "title" => "Lo sentimos",
            "message" => "Los datos ingresados no coinciden con nuestros registros",
          ], Response::HTTP_UNAUTHORIZED);
        }
      } catch (JWTException $e) {
        return response()->json([
          "type" => "error",
          "success" => false,
          "isLogged" => false,
          "title" => "Lo sentimos",
          "message" => $e->getMessage(),
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
      }

      $user = JWTAuth::user();

      return response()->json([
        "type" => "success",
        "success" => true,
        "isLogged" => true,
        "user" => $user,
        "authorization" => [
          "token" => $token,
          "type" => "Bearer"
        ]
      ], Response::HTTP_OK);
    } catch (Exception $e) {
      return response()->json([
        "type" => "error",
        "success" => false,
        "isLogged" => false,
        "title" => "Error",
        "message" => $e->getMessage()
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }
}
