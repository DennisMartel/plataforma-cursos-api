<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

function defaultEntryJsonNotFound()
{
  $staticDir = "resources/noentry.json";
  if (Storage::disk("public")->exists($staticDir)) {
    $jsonFile = json_decode(Storage::disk("public")->get($staticDir));
    return response()->json($jsonFile, Response::HTTP_NOT_FOUND)->header('Access-Control-Allow-Origin', '*');
  }

  return response()->json([
    "message" => "resource not found",
    "error" => "not entry",
    "code" => 404
  ], Response::HTTP_NOT_FOUND);
}

function tokenSign($claims)
{
  return JWT::encode($claims, env("JWT_SECRET"), "HS256");
}

function decodeToken($token)
{
  try {
    return JWT::decode($token, new Key(env("JWT_SECRET"), "HS256"));
  } catch (\Exception) {
    return null;
  }
}

function getTotalShoppingCart()
{
  $user = auth()->guard("api")->user();

  $values = $user->cart()->with(["course"])->get()->map(function ($cart) {
    return $cart->course->discount_price ?: $cart->course->price;
  });

  $total = 0;

  foreach ($values as $value) {
    $total += number_format($value, 2);
  }

  return floatval($total);
}
