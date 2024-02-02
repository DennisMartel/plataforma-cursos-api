<?php

use App\Http\Controllers\Resources\Common;
use App\Http\Controllers\Resources\Maintenance;
use Illuminate\Support\Facades\Route;

Route::get("/maintenance.json", Maintenance::class);
Route::get("/{language}/common.json", Common::class);
