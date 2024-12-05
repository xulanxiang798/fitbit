<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\FitbitController;
use App\Http\Controllers\UserComparisonController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('user.login');
Route::post('/login', [LoginController::class, 'login'])->name('user.login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('user.logout');
Route::get('/user/comparison', [UserComparisonController::class, 'showUserComparison'])->name('user.comparison');




Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('user.register');
Route::post('/register', [RegisterController::class, 'register'])->name('user.register.submit');




Route::get('/fitbit/redirect', [FitbitController::class, 'redirectToProvider'])->name('fitbit.redirect');

// 用户管理页面
Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.index');
Route::get('/admin/users/{user}/steps', [AdminController::class, 'showUserSteps'])->name('admin.user.steps');
Route::get('/admin/users/{user}/heart-rates', [AdminController::class, 'showUserHeartRates'])->name('admin.user.heartRates');
Route::get('/admin/users/{user}/sleeps', [AdminController::class, 'showUserSleeps'])->name('admin.user.sleeps');
Route::get('/admin/users/{user}/calories', [AdminController::class, 'showUserCalories'])->name('admin.user.calories');
Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.user.delete');

// Fitbit 数据展示
Route::get('/fitbit/profile', [FitbitController::class, 'profile'])->name('fitbit.profile');
Route::get('/fitbit/callback', [FitbitController::class, 'handleProviderCallback'])->name('fitbit.callback');

