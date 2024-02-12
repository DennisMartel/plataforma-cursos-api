<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\FilterController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\SocialAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

Route::middleware("x_api_key")->group(function () {
  Route::get("/courses", [CourseController::class, "index"]);
  Route::get("/search-course/{term}", [CourseController::class, "search_courses"]);
  Route::post("/show-course", [CourseController::class, "show_course"]);
  Route::post("/{id}/status-course", [CourseController::class, "status_course"]);
  Route::post("/all-courses", [CourseController::class, "all_courses"]);

  Route::get("/get-all-filters", FilterController::class);

  Route::prefix("authentication")->group(function () {
    Route::post("/sign-in", [AuthController::class, "signin"]);
    Route::post("/sign-up", [AuthController::class, "signup"]);
    Route::post("/send-code-verification", [ForgotPasswordController::class, "sendCodeVerification"]);
    Route::post("/forgot-password", [ForgotPasswordController::class, "forgotPassword"]);
    Route::post("/reset-password", [ForgotPasswordController::class, "resetPassword"]);
    Route::get("/provider/{driver}", [SocialAuthController::class, "redirectToAuth"]);
    Route::get("/provider/{driver}/callback", [SocialAuthController::class, "handleAuthCallback"]);
  });
});
