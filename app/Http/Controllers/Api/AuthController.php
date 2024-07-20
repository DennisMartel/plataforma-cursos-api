<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
  public function __construct()
  {
    $this->middleware("auth:api", ["except" => ["signin", "signup", "refresh"]]);
  }

  public function signin(Request $req)
  {
    try {
      $validator = Validator::make($req->all(), [
        "email" => "required|string|email|max:255",
        "password" => "required|string|min:8"
      ]);

      if ($validator->fails()) {
        return response()->json($validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
      }

      $credentials = $req->only("email", "password");

      $token = JWTAuth::claims([
        "login_type" => "normal",
        "social_id" => "none"
      ])->attempt($credentials, $req->remember);

      try {
        if ($token == null) {
          return response()->json([
            "type" => "error",
            "success" => false,
            "isLogged" => false,
            "title" => __("messages.sorry"),
            "message" => __("messages.credentials_not_match"),
          ], Response::HTTP_UNAUTHORIZED);
        }
      } catch (JWTException $e) {
        return response()->json([
          "type" => "error",
          "success" => false,
          "isLogged" => false,
          "title" => __("messages.sorry"),
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

  public function signup(StoreRequest $req)
  {
    try {
      $user = User::create([
        "name" => $req->name,
        "email" => $req->email,
        "password" => Hash::make($req->password)
      ]);

      $jwt = JWTAuth::claims([
        "login_type" => "normal",
        "social_id" => "none"
      ])->fromUser($user);

      return response()->json([
        "type" => "success",
        "success" => true,
        "isLogged" => true,
        "user" => $user,
        "authorization" => [
          "token" => $jwt,
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

  public function logout()
  {
    try {
      JWTAuth::invalidate(JWTAuth::parseToken());
      return response()->json([
        "message" => "Successfully logged out",
        "logout" => true
      ], Response::HTTP_OK);
    } catch (\Exception $e) {
      return response()->json([
        "message" => "Failed to logout",
        "logout" => false
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  public function refresh()
  {
    try {
      $refreshToken = JWTAuth::refresh(JWTAuth::getToken());

      return response()->json([
        "authorization" => [
          "token" => $refreshToken,
          "type" => "Bearer"
        ]
      ]);
    } catch (JWTException $e) {
      if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException) {
        return response()->json(["message" => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
      }

      if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
        return response()->json(["message" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
      }

      return response()->json(["message" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
    } catch (Exception $e) {
      return response()->json(["message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }
}
