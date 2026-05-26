<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $spaIndex = base_path('frontend/dist/index.html');

    if (file_exists($spaIndex)) {
        return response()->file($spaIndex);
    }

    return view('welcome');
});

Route::get('/{any}', function () {
    $spaIndex = base_path('frontend/dist/index.html');

    if (file_exists($spaIndex)) {
        return response()->file($spaIndex);
    }

    return view('welcome');
})->where('any', '^(?!api|up).*$');
