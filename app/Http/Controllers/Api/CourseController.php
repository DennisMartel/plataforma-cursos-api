<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CourseController extends Controller
{
  public function index()
  {
    $courses = Course::where("status", Course::APPROVED)
      ->latest("id")
      ->take(8)
      ->get();

    return response()->json($courses, Response::HTTP_OK);
  }

  public function search_courses($term)
  {
    $courses = Course::where("status", Course::APPROVED)
      ->search($term)
      ->latest("id")
      ->get();

    return response()->json($courses, Response::HTTP_OK);
  }

  public function show_course(Request $request)
  {
    try {
      $course = Course::with(["category", "level", "sections.lessons"])->find($request->courseId);

      if ($course != null) {
        return response()->json($course, Response::HTTP_OK);
      }

      return response()->json([
        "message" => "not found"
      ], Response::HTTP_NOT_FOUND);
    } catch (Exception $e) {
      return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }
}