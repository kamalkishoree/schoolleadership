<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $student = $request->user();
        $todayChallenge = Challenge::getTodayChallenge();

        $hasSubmittedToday = false;
        if ($todayChallenge) {
            $hasSubmittedToday = $student->submissions()
                ->where('challenge_id', $todayChallenge->id)
                ->exists();
        }

        $classRank = $this->calculateRank($student, 'class');
        $schoolRank = $this->calculateRank($student, 'school');

        return response()->json([
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'total_xp' => $student->total_xp,
                'current_streak' => $student->current_streak,
                'last_active_date' => $student->last_active_date?->toDateString(),
            ],
            'today_challenge' => $todayChallenge ? [
                'id' => $todayChallenge->id,
                'title' => $todayChallenge->title,
                'xp_reward' => $todayChallenge->xp_reward,
                'has_submitted' => $hasSubmittedToday,
            ] : null,
            'rank' => [
                'class_rank' => $classRank,
                'school_rank' => $schoolRank,
            ],
        ]);
    }

    private function calculateRank($student, string $type): int
    {
        $query = $type === 'class'
            ? \App\Models\Student::where('class_id', $student->class_id)
            : \App\Models\Student::where('school_id', $student->school_id);

        return $query->where('total_xp', '>', $student->total_xp)->count() + 1;
    }
}

