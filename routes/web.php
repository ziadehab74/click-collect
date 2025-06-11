<?php

use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/kitchen', function () {
    return view('kitchen');
});
Route::get('/menu', [MenuController::class, 'view'])->name('menu.landing');
Route::post('/checkout', [MenuController::class, 'checkout'])->name('checkout');
