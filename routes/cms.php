<?php

use App\Http\Controllers\Resources\Maintenance;
use Illuminate\Support\Facades\Route;

Route::get("/maintenance.json", Maintenance::class);
