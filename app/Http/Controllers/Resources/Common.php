<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use stdClass;

class Common extends Controller
{
  public function __invoke()
  {
    $language = request("language");
    $queryParamHeader = request("header");
    $queryParamFooter = request("footer");
    $staticDir = "resources/langs/$language/common.json";
    $jsonFileExist = Storage::disk("public")->exists($staticDir);

    if ($jsonFileExist) {
      $jsonFile = json_decode(Storage::disk("public")->get($staticDir), true);

      $jsonObject = new stdClass();
      $queryParamHeader === "true" && $jsonObject->header = $jsonFile["header"];
      $queryParamFooter === "true" && $jsonObject->footer = $jsonFile["footer"];

      return response()->json($jsonObject, Response::HTTP_OK)
        ->header('Access-Control-Allow-Origin', '*');
    }

    return defaultEntryJsonNotFound();
  }
}
