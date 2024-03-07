<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CourseStatusController extends Controller
{
  protected $user;

  public function __construct()
  {
    $this->user = auth()->guard("api")->user();
  }

  public function curriculum(Request $request)
  {
    try {
      $id = $request->courseId;
      $course = Course::find($id);

      if ($course == null) :
        return response()->json([
          "message" => __("messages.not_found"),
          "status" => false,
        ], Response::HTTP_BAD_REQUEST);
      endif;

      if ($this->user == null) :
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

      $data = Course::with(["sections.lessons"])->find($id);

      return response()->json($data, Response::HTTP_OK);
    } catch (\Exception $e) {
      return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  public function progress(Request $request)
  {
    try {
      $id = $request->headers->get("courseId");
      $course = Course::find($id);

      if ($course == null) :
        return response()->json([
          "message" => __("messages.not_found"),
          "status" => false,
        ], Response::HTTP_BAD_REQUEST);
      endif;

      if ($this->user == null) :
        return response()->json([
          "message" => __('messages.user_not_logged'),
          "status" => false,
          "isLogged" => false
        ], Response::HTTP_FORBIDDEN);
      endif;

      $progress = 0;
      foreach ($course->lessons as $lesson) :
        if ($lesson->completed) :
          $progress++;
        endif;
      endforeach;

      $current = null;
      foreach ($course->lessons as $lesson) :
        if (!$lesson->completed) :
          $current = $lesson->id;
          break;
        endif;
      endforeach;

      if ($current == null) :
        $current = $course->lessons->last()->id;
      endif;

      $data = new \stdClass;
      $data->progress = round(($progress * 100) / $course->lessons->count(), 1);
      $data->completed_lecture_ids = $this->user->lessons->pluck("id");
      $data->last_seen_lesson = $current;

      return response()->json($data, Response::HTTP_OK);
    } catch (\Exception $e) {
      return response()->json([
        "message" => __("messages.unexpected_error") . $e->getMessage()
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  public function enroll()
  {
    try {
      $course = Course::find(request()->headers->get("courseId"));

      if ($course == null) :
        return response()->json([
          "message" => __("messages.not_found"),
          "status" => false,
        ], Response::HTTP_BAD_REQUEST);
      endif;

      if ($this->user == null) :
        return response()->json([
          "message" => __('messages.user_not_logged'),
          "status" => false,
          "isLogged" => false
        ], Response::HTTP_FORBIDDEN);
      endif;

      if ($course->is_enrolled == false) :
        $course->students()->attach($this->user->id);
      else :
        return response()->json([
          "message" => __("messages.request_not_processed"),
          "status" => false,
        ], Response::HTTP_BAD_REQUEST);
      endif;
    } catch (\Exception $e) {
      return response()->json([
        "message" => __("messages.unexpected_error") . $e->getMessage()
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  public function toggle_lesson_status()
  {
    try {
      $course = Course::find(request()->headers->get("courseId"));
      $lesson = Lesson::find(request()->headers->get("lessonId"));

      if ($course == null || $lesson == null) :
        return response()->json([
          "message" => __("messages.not_found"),
          "status" => false,
        ], Response::HTTP_BAD_REQUEST);
      endif;

      if ($this->user == null) :
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

      if ($course->lessons->contains($lesson->id)) :
        if ($lesson->completed) :
          $lesson->users()->detach($this->user->id);
        else :
          $lesson->users()->attach($this->user->id);
        endif;
      else :
        return response()->json([
          "message" => __("messages.request_not_processed"),
          "status" => false,
        ], Response::HTTP_BAD_REQUEST);
      endif;
    } catch (\Exception $e) {
      return response()->json([
        "message" => __("messages.unexpected_error") . $e->getMessage()
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }
}
