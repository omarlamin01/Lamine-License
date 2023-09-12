<?php

use Illuminate\Support\Facades\Route;
use Lamine\License\Controllers\Index;

Route::get('/activate-product', function () {
    return view('LicenseExpired');
})->name('license');

Route::post('/activate-product', [Index::class, 'webStore'])->name('license.store');

Route::post('api/license', [Index::class, 'store']);
