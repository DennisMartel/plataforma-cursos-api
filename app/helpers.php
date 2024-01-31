<?php

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
