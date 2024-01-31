<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class Maintenance extends Controller
{
  public function __invoke()
  {
    $staticDir = "resources/maintenance.json";
    $jsonFileExist = Storage::disk("public")->exists($staticDir);

    if ($jsonFileExist) {
      $jsonFile = json_decode(Storage::disk("public")->get($staticDir));

      return response()->json($jsonFile);
    }

    return defaultEntryJsonNotFound();
  }
}
