<?php

use App\Http\Controllers\CardGeneratorController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/card-generator', [CardGeneratorController::class, 'index'])->name('card-generator');
