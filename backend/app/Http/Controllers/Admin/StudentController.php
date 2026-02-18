<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\School;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StudentController extends Controller
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

        $students = $query->orderBy('total_xp', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $schools = School::where('is_active', true)->get();
        $classes = ClassModel::when($request->filled('school_id'), function ($q) use ($request) {
            $q->where('school_id', $request->school_id);
        })->get();

        return view('admin.students.index', compact('students', 'schools', 'classes'));
    }

    public function create(): View
    {
        $schools = School::where('is_active', true)->get();
        return view('admin.students.create', compact('schools'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'class_id' => [
                'required',
                'exists:classes,id',
                function ($attribute, $value, $fail) use ($request) {
                    $class = ClassModel::find($value);
                    if ($class && $class->school_id != $request->school_id) {
                        $fail('The selected class does not belong to the selected school.');
                    }
                },
            ],
            'name' => 'required|string|max:255',
            'email_or_mobile' => 'required|string|unique:students,email_or_mobile',
            'password' => 'required|string|min:6',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        Student::create($validated);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student created successfully');
    }

    public function edit(Student $student): View
    {
        $schools = School::where('is_active', true)->get();
        $classes = ClassModel::where('school_id', $student->school_id)->get();
        return view('admin.students.edit', compact('student', 'schools', 'classes'));
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'class_id' => 'required|exists:classes,id',
            'name' => 'required|string|max:255',
            'email_or_mobile' => 'required|string|unique:students,email_or_mobile,' . $student->id,
            'password' => 'nullable|string|min:6',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $student->update($validated);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully');
    }
}

