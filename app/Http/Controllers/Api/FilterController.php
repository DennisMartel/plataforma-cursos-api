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
      $search = request("search");

      $categories = Category::whereHas('courses', function ($query) use ($search) {
        $query->search($search)->where('courses.status', Course::APPROVED);
      })->where('categories.status', Category::ACTIVE)->get();

      $levels = Level::whereHas("courses", function ($query) use ($search) {
        $query->search($search)->where("courses.status", Course::APPROVED);
      })->get();

      $prices = [
        [
          "title" => "Pago",
          "courses_count" => Course::where("price", "!=", null)->where("status", Course::APPROVED)->count()
        ],
        [
          "title" => "Gratis",
          "courses_count" => Course::where("price", null)->where("status", Course::APPROVED)->count()
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
