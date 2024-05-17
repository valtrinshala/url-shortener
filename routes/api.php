<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/v1/url/shorten', \App\Http\Controllers\Api\V1\Url\ShortenController::class);
