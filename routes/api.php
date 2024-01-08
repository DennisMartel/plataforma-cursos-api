<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\FilterController;
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
  Route::post("/all-courses", [CourseController::class, "all_courses"]);

  Route::get("/get-all-filters", FilterController::class);

  Route::prefix("authentication")->group(function () {
    Route::post("/sign-in", [AuthController::class, "signin"]);
  });
});
