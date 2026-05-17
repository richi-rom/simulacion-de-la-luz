<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RefleRefraLuzController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/getImage/{imagen}', [RefleRefraLuzController::class, 'getImage'])->name('getImage');

Route::post('/simulation',  [RefleRefraLuzController::class, 'simulation'])->name('simulation');
