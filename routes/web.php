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
