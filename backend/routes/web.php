<?php

use App\Http\Controllers\Admin\ChallengeController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\LeaderboardController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.schools.index');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.schools.index');
    });

    Route::resource('schools', SchoolController::class);
    Route::get('classes/by-school', [ClassController::class, 'getBySchool'])->name('classes.by-school');
    Route::resource('classes', ClassController::class);
    Route::resource('students', StudentController::class);
    Route::resource('challenges', ChallengeController::class);
    
    Route::get('leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
    Route::get('reports/weekly', [ReportController::class, 'weeklyReport'])->name('reports.weekly');
});
