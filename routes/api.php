<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\CourseStatusController;
use App\Http\Controllers\Api\FilterController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SocialAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

Route::middleware("x_api_key")->group(function () {
  Route::get("/get-all-filters", FilterController::class);
  Route::get("/courses", [CourseController::class, "index"]);
  Route::get("/search-course/{term}", [CourseController::class, "search_courses"]);
  Route::post("/show-course", [CourseController::class, "show_course"]);
  Route::post("/all-courses", [CourseController::class, "all_courses"]);

  Route::middleware(["jwt.verify", "auth:api"])->group(function () {
    Route::post("/course-learn-curriculum", [CourseStatusController::class, "curriculum"]);
    Route::get("/course-progress", [CourseStatusController::class, "progress"]);
    Route::post("/toggle-lesson-status", [CourseStatusController::class, "toggle_lesson_status"]);
    Route::post("/course-enroll", [CourseStatusController::class, "enroll"]);

    Route::prefix("account")->group(function () {
      Route::get("/profile", [ProfileController::class, "profile"]);
    });

    Route::prefix("shopping-cart")->group(function () {
      Route::post("/add-cart-item", [CartController::class, "addCartItem"]);
      Route::post("/remove-cart-item", [CartController::class, "removeCartItem"]);
    });
  });


  Route::prefix("authentication")->group(function () {
    Route::post("/sign-in", [AuthController::class, "signin"]);
    Route::post("/sign-up", [AuthController::class, "signup"]);
    Route::post("/logout", [AuthController::class, "logout"]);
    Route::post("/refresh", [AuthController::class, "refresh"]);
    Route::post("/send-code-verification", [ForgotPasswordController::class, "sendCodeVerification"]);
    Route::post("/forgot-password", [ForgotPasswordController::class, "forgotPassword"]);
    Route::post("/reset-password", [ForgotPasswordController::class, "resetPassword"]);
    Route::get("/provider/{driver}", [SocialAuthController::class, "redirectToAuth"]);
    Route::get("/provider/{driver}/callback", [SocialAuthController::class, "handleAuthCallback"]);
  });
});
