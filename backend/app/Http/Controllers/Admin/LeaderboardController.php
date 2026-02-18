<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    public function index(Request $request): View
    {
        $query = Student::with(['school', 'class']);

        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        $leaderboard = $query->orderBy('total_xp', 'desc')
            ->orderBy('current_streak', 'desc')
            ->orderBy('updated_at', 'desc')
            ->limit(100)
            ->get()
            ->map(function ($student, $index) {
                return [
                    'rank' => $index + 1,
                    'student' => $student,
                ];
            });

        $schools = School::where('is_active', true)->get();

        return view('admin.leaderboard.index', compact('leaderboard', 'schools'));
    }
}

