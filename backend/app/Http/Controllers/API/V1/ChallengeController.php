<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use App\Models\Student;
use App\Models\Submission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChallengeController extends Controller
{
    public function getTodayChallenge(Request $request): JsonResponse
    {
        $challenge = Challenge::getTodayChallenge();

        if (!$challenge) {
            return response()->json([
                'message' => 'No challenge available for today',
            ], 404);
        }

        $student = $request->user();
        $submission = Submission::where('student_id', $student->id)
            ->where('challenge_id', $challenge->id)
            ->first();

        if ($submission) {
            return response()->json([
                'message' => 'Challenge already submitted',
                'submission' => [
                    'selected_option' => $submission->selected_option,
                    'score' => $submission->score,
                    'submitted_at' => $submission->submitted_at,
                ],
            ], 409);
        }

        return response()->json([
            'challenge' => [
                'id' => $challenge->id,
                'title' => $challenge->title,
                'description' => $challenge->description,
                'type' => $challenge->type,
                'option_a' => $challenge->option_a,
                'option_b' => $challenge->option_b,
                'option_c' => $challenge->option_c,
                'option_d' => $challenge->option_d,
                'xp_reward' => $challenge->xp_reward,
            ],
        ]);
    }

    public function submitChallenge(Request $request): JsonResponse
    {
        $request->validate([
            'challenge_id' => 'required|exists:challenges,id',
            'selected_option' => 'required|in:a,b,c,d',
        ]);

        $student = $request->user();
        $challenge = Challenge::findOrFail($request->challenge_id);

        if ($challenge->active_date->toDateString() !== today()->toDateString()) {
            return response()->json([
                'message' => 'This challenge is not active today',
            ], 400);
        }

        $existingSubmission = Submission::where('student_id', $student->id)
            ->where('challenge_id', $challenge->id)
            ->first();

        if ($existingSubmission) {
            return response()->json([
                'message' => 'Challenge already submitted',
            ], 409);
        }

        return DB::transaction(function () use ($student, $challenge, $request) {
            $isCorrect = $challenge->isCorrect($request->selected_option);
            $score = $isCorrect ? $challenge->xp_reward : 0;

            $submission = Submission::create([
                'student_id' => $student->id,
                'challenge_id' => $challenge->id,
                'selected_option' => $request->selected_option,
                'score' => $score,
                'submitted_at' => now(),
            ]);

            if ($isCorrect) {
                $student->addXp($challenge->xp_reward);
            }

            $student->updateStreak();

            $student->refresh();

            $rank = $this->calculateRank($student);

            return response()->json([
                'message' => $isCorrect ? 'Correct answer!' : 'Incorrect answer',
                'submission' => [
                    'id' => $submission->id,
                    'selected_option' => $submission->selected_option,
                    'score' => $submission->score,
                    'is_correct' => $isCorrect,
                ],
                'student' => [
                    'total_xp' => $student->total_xp,
                    'current_streak' => $student->current_streak,
                ],
                'rank' => $rank,
            ]);
        });
    }

    private function calculateRank(Student $student): array
    {
        $classRank = Student::where('class_id', $student->class_id)
            ->where('total_xp', '>', $student->total_xp)
            ->count() + 1;

        $schoolRank = Student::where('school_id', $student->school_id)
            ->where('total_xp', '>', $student->total_xp)
            ->count() + 1;

        return [
            'class_rank' => $classRank,
            'school_rank' => $schoolRank,
        ];
    }
}

