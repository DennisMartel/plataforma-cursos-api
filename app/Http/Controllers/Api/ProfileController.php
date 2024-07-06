<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
  public function profile()
  {
    try {
      $hiddenUserFields = ["updated_at", "created_at", "carts"];

      $data = auth()->guard("api")->user()->makeHidden($hiddenUserFields);

      return response()->json($data, Response::HTTP_OK);
    } catch (\Exception $e) {
      return response()->json(["message" => $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR]);
    }
  }
}
