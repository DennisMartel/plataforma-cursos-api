<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CartController extends Controller
{
  public function addCartItem(Request $req)
  {
    try {
      $courseId = $req->courseId;
      $user = auth("api")->user();

      $course = Course::find($courseId);

      if ($course == null) {
        return response()->json([
          "item" => null,
          "message" => "No es posible agregar este curso al carrito de compras, intenta nuevamente"
        ], Response::HTTP_BAD_REQUEST);
      }

      $inCart = Cart::where("course_id", $courseId)->where("user_id", $user->id)->exists();

      if ($inCart) {
        return response()->json([
          "item" => null,
          "message" => "El curso ya se encuentra dentro del carrito de compras"
        ], Response::HTTP_BAD_REQUEST);
      }

      $cart = Cart::create([
        "user_id" => $user->id,
        "course_id" => $course->id,
      ]);

      $item = new \stdClass;
      $item->id = $cart->course->id;
      $item->cart_id = $cart->id;
      $item->title = $cart->course->title;
      $item->slug = $cart->course->slug;
      $item->rating = $cart->course->rating;
      $item->price = number_format((float)$cart->course->price, 2);
      $item->discount_price = $cart->course->discount_price;
      $item->last_update = $cart->course->last_update;
      $item->image = $cart->course->image_course;
      $item->teacher_name = $cart->course->teacher_name;
      $item->teacher_pic = $cart->course->teacher_image;
      $item->lessons_count = $cart->course->lessons_count;
      $item->is_enrolled = $cart->course->is_enrolled;
      $item->category = $cart->course->category->title;
      $item->level = $cart->course->level->title;

      return response()->json([
        "item" => $item,
        "message" => "producto agregado al carrito"
      ], Response::HTTP_OK);
    } catch (\Exception $e) {
      return response()->json([
        "item" => null,
        "message" => $e->getMessage()
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  public function removeCartItem(Request $req)
  {
    try {
      $courseId = $req->courseId;
      $cartId = $req->cartId;
      $course = Course::find($courseId);
      $cart = Cart::find($cartId);

      if ($course == null || $cart == null) {
        return response()->json([
          "message" => "No es posible eliminar este curso del carrito de compras, intenta nuevamente"
        ], Response::HTTP_BAD_REQUEST);
      }

      $cart->delete();

      return response()->json([
        "message" => "producto eliminado del carrito"
      ], Response::HTTP_OK);
    } catch (\Exception $e) {
      return response()->json([
        "message" => $e->getMessage()
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }
}
