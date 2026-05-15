<?php

use App\Http\Controllers\HealthCheckController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ShowAthleteProfileEditFormController;
use App\Http\Controllers\ShowLoginFormController;
use App\Http\Controllers\ShowProfilePageController;
use Illuminate\Support\Facades\Route;

Route::get('/health', HealthCheckController::class)->name('health');

Route::get('/login', ShowLoginFormController::class)->name('login');
Route::post('/login', LoginController::class)->name('login.submit');

Route::post('/logout', LogoutController::class)->name('logout');

Route::get('/', HomeController::class)->name('home');

Route::get('/profile', ShowProfilePageController::class)
    ->middleware('auth')
    ->name('profile');
Route::get('/profile/edit', ShowAthleteProfileEditFormController::class)
    ->middleware('auth')
    ->name('profile.edit');
