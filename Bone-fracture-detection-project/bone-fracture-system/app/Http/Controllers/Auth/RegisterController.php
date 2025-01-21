<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\RegisterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    protected $registerService;

    public function __construct(RegisterService $registerService)
    {
        $this->registerService = $registerService;
    }

    public function showRegistrationForm()
    {
        return view('auth.register'); // Points to resources/views/auth/register.blade.php
    }

    public function register(Request $request)
    {
        Log::info("Registration process started.");
        Log::info($request);
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'user_type' => 'required|in:regular_user,doctor',
                'age' => 'required_if:user_type,regular_user|nullable|integer|min:1'
            ]);
            $user = $this->registerService->registerUser($validatedData);
            Log::info("User registered successfully.", ['user_id' => $user->id]);
            return redirect()->route('analyze-fracture')->with('success', 'Account created successfully!');
        } catch (ValidationException $e) {
            Log::error("Validation failed during registration.", [
                'errors' => $e->errors()
            ]);
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error("Unexpected error during registration.", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }


}
