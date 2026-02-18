<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChallengeController extends Controller
{
    public function index(): View
    {
        $challenges = Challenge::orderBy('active_date', 'desc')
            ->paginate(20);

        return view('admin.challenges.index', compact('challenges'));
    }

    public function create(): View
    {
        return view('admin.challenges.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:mcq',
            'option_a' => 'required|string|max:255',
            'option_b' => 'required|string|max:255',
            'option_c' => 'required|string|max:255',
            'option_d' => 'required|string|max:255',
            'correct_option' => 'required|in:a,b,c,d',
            'xp_reward' => 'required|integer|min:1',
            'active_date' => 'required|date|unique:challenges,active_date',
            'is_active' => 'boolean',
        ]);

        Challenge::create($validated);

        return redirect()->route('admin.challenges.index')
            ->with('success', 'Challenge created successfully');
    }

    public function edit(Challenge $challenge): View
    {
        return view('admin.challenges.edit', compact('challenge'));
    }

    public function update(Request $request, Challenge $challenge): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:mcq',
            'option_a' => 'required|string|max:255',
            'option_b' => 'required|string|max:255',
            'option_c' => 'required|string|max:255',
            'option_d' => 'required|string|max:255',
            'correct_option' => 'required|in:a,b,c,d',
            'xp_reward' => 'required|integer|min:1',
            'active_date' => 'required|date|unique:challenges,active_date,' . $challenge->id,
            'is_active' => 'boolean',
        ]);

        $challenge->update($validated);

        return redirect()->route('admin.challenges.index')
            ->with('success', 'Challenge updated successfully');
    }

    public function destroy(Challenge $challenge): RedirectResponse
    {
        $challenge->delete();

        return redirect()->route('admin.challenges.index')
            ->with('success', 'Challenge deleted successfully');
    }
}

