<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function getClassLeaderboard(Request $request): JsonResponse
    {
        $student = $request->user();

        $leaderboard = \App\Models\Student::where('class_id', $student->class_id)
            ->orderBy('total_xp', 'desc')
            ->orderBy('current_streak', 'desc')
            ->orderBy('updated_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($student, $index) {
                return [
                    'rank' => $index + 1,
                    'id' => $student->id,
                    'name' => $student->name,
                    'total_xp' => $student->total_xp,
                    'current_streak' => $student->current_streak,
                ];
            });

        return response()->json([
            'leaderboard' => $leaderboard,
            'type' => 'class',
        ]);
    }

    public function getSchoolLeaderboard(Request $request): JsonResponse
    {
        $student = $request->user();

        $leaderboard = \App\Models\Student::where('school_id', $student->school_id)
            ->orderBy('total_xp', 'desc')
            ->orderBy('current_streak', 'desc')
            ->orderBy('updated_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($student, $index) {
                return [
                    'rank' => $index + 1,
                    'id' => $student->id,
                    'name' => $student->name,
                    'total_xp' => $student->total_xp,
                    'current_streak' => $student->current_streak,
                    'class_name' => $student->class->name,
                ];
            });

        return response()->json([
            'leaderboard' => $leaderboard,
            'type' => 'school',
        ]);
    }
}

