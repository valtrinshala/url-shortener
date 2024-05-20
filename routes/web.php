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


Route::get('/{redirect:hash}', [\App\Http\Controllers\Web\RedirectController::class, 'redirect'])->name('redirects.redirect');

Route::get('/{subfolder}/{hash}', [\App\Http\Controllers\Web\RedirectController::class, 'redirectWithSubfolder'])->name('redirects.redirectWithSubfolder');