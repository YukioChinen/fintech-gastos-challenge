<?php

use Illuminate\Support\Facades\Route;

$resolveSpaIndex = static function (): ?string {
    $candidates = [
        public_path('build/index.html'),
        base_path('frontend/dist/index.html'),
    ];

    foreach ($candidates as $path) {
        if (file_exists($path)) {
            return $path;
        }
    }

    return null;
};

Route::get('/', function () use ($resolveSpaIndex) {
    $spaIndex = $resolveSpaIndex();

    if ($spaIndex) {
        return response()->file($spaIndex);
    }

    return view('welcome');
});

Route::get('/{any}', function () use ($resolveSpaIndex) {
    $spaIndex = $resolveSpaIndex();

    if ($spaIndex) {
        return response()->file($spaIndex);
    }

    return view('welcome');
})->where('any', '^(?!api|up).*$');
