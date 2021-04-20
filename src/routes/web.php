<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\XapiController;

Route::get("/", ShareController::class . "@share");
Route::get("/xx-zw", HomeController::class . "@index");
Route::get("/oapi", XapiController::class . "@index");



