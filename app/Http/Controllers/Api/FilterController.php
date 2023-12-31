<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Level;
use Exception;
use Illuminate\Http\Response;

class FilterController extends Controller
{
  public function __invoke()
  {
    try {
      $categories = Category::where("status", Category::ACTIVE)->get();
      $levels = Level::all();
      $prices = [
        [
          "title" => "Pago",
          "courses_count" => Course::where("price", "!=", null)->count()
        ],
        [
          "title" => "Gratis",
          "courses_count" => Course::where("price", null)->count()
        ]
      ];

      return response()->json([
        "categories" => $categories,
        "levels" => $levels,
        "prices" => $prices,
      ], Response::HTTP_OK);
    } catch (Exception $e) {
      return response()->json([
        "message" => $e->getMessage()
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }
}
