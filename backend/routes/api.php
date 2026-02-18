<?php

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\ChallengeController;
use App\Http\Controllers\API\V1\DashboardController;
use App\Http\Controllers\API\V1\LeaderboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        Route::get('/dashboard', [DashboardController::class, 'index']);

        Route::get('/challenge/today', [ChallengeController::class, 'getTodayChallenge']);
        Route::post('/challenge/submit', [ChallengeController::class, 'submitChallenge']);

        Route::get('/leaderboard/class', [LeaderboardController::class, 'getClassLeaderboard']);
        Route::get('/leaderboard/school', [LeaderboardController::class, 'getSchoolLeaderboard']);
    });
});

