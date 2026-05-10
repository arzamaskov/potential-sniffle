<?php

use App\Http\Controllers\HealthCheckController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/health', HealthCheckController::class)->name('health');
Route::post('/login', LoginController::class)->name('login');
Route::get('/', HomeController::class)->name('home');
