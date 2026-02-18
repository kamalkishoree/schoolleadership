<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email_or_mobile' => 'required|string',
            'password' => 'required|string',
        ]);

        $student = Student::where('email_or_mobile', $request->email_or_mobile)->first();

        if (!$student || !Hash::check($request->password, $student->password)) {
            throw ValidationException::withMessages([
                'email_or_mobile' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $student->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'email_or_mobile' => $student->email_or_mobile,
                'total_xp' => $student->total_xp,
                'current_streak' => $student->current_streak,
                'school_id' => $student->school_id,
                'class_id' => $student->class_id,
            ],
            'token' => $token,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function me(Request $request): JsonResponse
    {
        $student = $request->user();

        return response()->json([
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'email_or_mobile' => $student->email_or_mobile,
                'total_xp' => $student->total_xp,
                'current_streak' => $student->current_streak,
                'last_active_date' => $student->last_active_date?->toDateString(),
                'school_id' => $student->school_id,
                'class_id' => $student->class_id,
                'school' => [
                    'id' => $student->school->id,
                    'name' => $student->school->name,
                ],
                'class' => [
                    'id' => $student->class->id,
                    'name' => $student->class->name,
                ],
            ],
        ]);
    }
}

