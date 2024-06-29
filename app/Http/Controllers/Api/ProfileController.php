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

      $user = auth()->guard("api")->user()->makeHidden($hiddenUserFields);

      $carts = $user->cart()
        ->with(["course"])
        ->get()
        ->map(function ($cart) {
          return [
            "id" => $cart->course->id,
            "cart_id" => $cart->id,
            "title" => $cart->course->title,
            "slug" => $cart->course->slug,
            "rating" => $cart->course->rating,
            "price" => number_format((float)$cart->course->price, 2),
            "discount_price" => $cart->course->discount_price,
            "last_update" => $cart->course->last_update,
            "image" => $cart->course->image_course,
            "teacher_name" => $cart->course->teacher_name,
            "teacher_pic" => $cart->course->teacher_image,
            "lessons_count" => $cart->course->lessons_count,
            "is_enrolled" => $cart->course->is_enrolled,
            "category" => $cart->course->category->title,
            "level" => $cart->course->level->title
          ];
        })
        ->values();

      $data = new \stdClass;
      $data->profile_info = $user;
      $data->shopping_cart = $carts;

      return response()->json($data, Response::HTTP_OK);
    } catch (\Exception $e) {
      return response()->json(["message" => $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR]);
    }
  }
}
