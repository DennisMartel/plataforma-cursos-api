<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class Common extends Controller
{
  public function __invoke()
  {
    $language = request("language");
    $staticDir = "resources/langs/$language/common.json";
    $jsonFileExist = Storage::disk("public")->exists($staticDir);

    if ($jsonFileExist) {
      $jsonFile = json_decode(Storage::disk("public")->get($staticDir));

      return response()->json($jsonFile, Response::HTTP_OK)
        ->header('Access-Control-Allow-Origin', '*');
    }

    return defaultEntryJsonNotFound();
  }
}
