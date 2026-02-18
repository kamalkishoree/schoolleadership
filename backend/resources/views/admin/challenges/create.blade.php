@extends('admin.layouts.app')

@section('title', 'Create Challenge')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Create Challenge</h2>

        <form action="{{ route('admin.challenges.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description') }}</textarea>
                    @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Option A</label>
                    <input type="text" name="option_a" value="{{ old('option_a') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('option_a')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Option B</label>
                    <input type="text" name="option_b" value="{{ old('option_b') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('option_b')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Option C</label>
                    <input type="text" name="option_c" value="{{ old('option_c') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('option_c')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Option D</label>
                    <input type="text" name="option_d" value="{{ old('option_d') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('option_d')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Correct Option</label>
                    <select name="correct_option" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Select</option>
                        <option value="a" {{ old('correct_option') == 'a' ? 'selected' : '' }}>A</option>
                        <option value="b" {{ old('correct_option') == 'b' ? 'selected' : '' }}>B</option>
                        <option value="c" {{ old('correct_option') == 'c' ? 'selected' : '' }}>C</option>
                        <option value="d" {{ old('correct_option') == 'd' ? 'selected' : '' }}>D</option>
                    </select>
                    @error('correct_option')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">XP Reward</label>
                    <input type="number" name="xp_reward" value="{{ old('xp_reward', 10) }}" min="1" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('xp_reward')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Active Date</label>
                    <input type="date" name="active_date" value="{{ old('active_date') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('active_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.challenges.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Create
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

