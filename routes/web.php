<?php

use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});
Route::get('/kds', function () {
    return view('kitchen');
});
Route::get('/menu', [MenuController::class, 'view'])->name('menu.landing');
Route::post('/checkout', [MenuController::class, 'checkout'])->name('checkout');
