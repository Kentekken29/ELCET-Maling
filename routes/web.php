<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('courses', CourseController::class)->only(['index', 'create', 'store', 'update']);
Route::resource('enrollments', EnrollmentController::class)->only(['index', 'create', 'store', 'update']);