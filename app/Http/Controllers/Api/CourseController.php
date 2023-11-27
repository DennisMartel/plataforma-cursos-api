<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
  public function index()
  {
    $courses = Course::where("status", Course::APPROVED)
      ->latest("id")
      ->take(8)
      ->get();

    return response()->json($courses, 200);
  }

  public function search_courses($term)
  {
    $courses = Course::where("status", Course::APPROVED)
      ->search($term)
      ->latest("id")
      ->get();

    return response()->json($courses, 200);
  }
}
