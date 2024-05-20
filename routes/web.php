<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::get('/{hash}', function(string $hash){
    $route = \App\Models\Redirect::where('hash', $hash)->firstOrFail();
    return redirect()->to($route->url);
});


Route::get('/{subfolder}/{hash}', function (string $subfolder, string $hash) {
    $route = \App\Models\Redirect::where('hash', $hash)->where('subfolder', $subfolder)->firstOrFail();
    return redirect()->to($route->url);
})->where('subfolder', '.*')->where('hash', '[a-zA-Z0-9]{6}');