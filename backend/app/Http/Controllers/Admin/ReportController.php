<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Student;
use App\Models\Submission;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function weeklyReport(Request $request)
    {
        $request->validate([
            'school_id' => 'nullable|exists:schools,id',
            'week_start' => 'nullable|date',
        ]);

        $weekStart = $request->week_start 
            ? \Carbon\Carbon::parse($request->week_start)->startOfWeek()
            : now()->startOfWeek();
        
        $weekEnd = $weekStart->copy()->endOfWeek();

        $query = Student::with(['school', 'class']);

        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        $students = $query->get();

        $reportData = [];
        foreach ($students as $student) {
            $submissions = Submission::where('student_id', $student->id)
                ->whereBetween('submitted_at', [$weekStart, $weekEnd])
                ->get();

            $totalXpEarned = $submissions->sum('score');
            $correctAnswers = $submissions->where('score', '>', 0)->count();
            $totalChallenges = $submissions->count();

            $reportData[] = [
                'student' => $student,
                'total_xp_earned' => $totalXpEarned,
                'correct_answers' => $correctAnswers,
                'total_challenges' => $totalChallenges,
                'accuracy' => $totalChallenges > 0 ? round(($correctAnswers / $totalChallenges) * 100, 2) : 0,
            ];
        }

        usort($reportData, function ($a, $b) {
            return $b['total_xp_earned'] <=> $a['total_xp_earned'];
        });

        $school = $request->filled('school_id') 
            ? School::find($request->school_id) 
            : null;

        $pdf = Pdf::loadView('admin.reports.weekly', [
            'reportData' => $reportData,
            'weekStart' => $weekStart,
            'weekEnd' => $weekEnd,
            'school' => $school,
        ]);

        return $pdf->download('weekly-report-' . $weekStart->format('Y-m-d') . '.pdf');
    }
}

