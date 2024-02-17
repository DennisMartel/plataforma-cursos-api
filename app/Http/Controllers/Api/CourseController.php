<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use stdClass;

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
        "message" => __("messages.not_found")
      ], Response::HTTP_NOT_FOUND);
    } catch (Exception $e) {
      return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  public function status_course($id)
  {
    try {
      $course = Course::find($id);
      $user = auth()->user();

      if ($user == null) :
        return response()->json([
          "message" => __('messages.user_not_logged'),
          "status" => false,
          "isLogged" => false
        ], Response::HTTP_FORBIDDEN);
      endif;

      if ($course->is_enrolled == false) :
        return response()->json([
          "message" => __('messages.user_not_enrolled'),
          "status" => false,
          "unauthorized" => true
        ], Response::HTTP_FORBIDDEN);
      endif;

      $i = 0;
      foreach ($course->lessons as $lesson) :
        if ($lesson->completed) :
          $i++;
        endif;
      endforeach;

      $data = new stdClass;
      $data->data = Course::with(["sections.lessons"])->find($id);
      $data->advance = round(($i * 100) / $course->lessons->count(), 1);

      return response()->json($data, Response::HTTP_OK);
    } catch (Exception $e) {
      return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  public function all_courses()
  {
    try {
      $cats = request("categories");
      $levels = request("levels");
      $prices = request("prices");
      $page = request("page");

      $courses = Course::where("status", Course::APPROVED)
        ->whereHas("category", function ($query) use ($cats) {
          $query->when($cats, function ($query) use ($cats) {
            $query->whereIn("title", $cats);
          });
        })
        ->whereHas("level", function ($query) use ($levels) {
          $query->when($levels, function ($query) use ($levels) {
            $query->whereIn("title", $levels);
          });
        })
        ->latest("id")
        ->paginate(12, ["*"], "page", $page);

      return response()->json($courses, Response::HTTP_OK);
    } catch (Exception $e) {
      return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }
}
