<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\School;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassController extends Controller
{
    public function index(Request $request): View
    {
        $query = ClassModel::with(['school']);

        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        $classes = $query->withCount('students')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $schools = School::where('is_active', true)->get();

        return view('admin.classes.index', compact('classes', 'schools'));
    }

    public function create(): View
    {
        $schools = School::where('is_active', true)->get();
        return view('admin.classes.create', compact('schools'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
        ]);

        ClassModel::create($validated);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class created successfully');
    }

    public function edit(ClassModel $class): View
    {
        $schools = School::where('is_active', true)->get();
        return view('admin.classes.edit', compact('class', 'schools'));
    }

    public function update(Request $request, ClassModel $class): RedirectResponse
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
        ]);

        $class->update($validated);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class updated successfully');
    }

    public function destroy(ClassModel $class): RedirectResponse
    {
        $class->delete();

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class deleted successfully');
    }

    public function getBySchool(Request $request): JsonResponse
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
        ]);

        $classes = ClassModel::where('school_id', $request->school_id)
            ->get(['id', 'name']);

        return response()->json($classes);
    }
}

