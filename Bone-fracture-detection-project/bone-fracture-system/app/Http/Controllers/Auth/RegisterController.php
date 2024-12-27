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

        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'user_type' => 'required|in:regular_user,doctor',
            ]);

            // Use the service to create the user
            $user = $this->registerService->registerUser($validatedData);
            Log::info("User registered successfully.", ['user_id' => $user->id]);

            // If the request is an API call, return a JSON response
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Account created successfully!',
                    'user' => $user,
                ], 201);
            }

            // Otherwise, redirect to the home page with a success message
            return redirect()->route('analyze-fracture')->with('success', 'Account created successfully!');
        } catch (ValidationException $e) {
            Log::error("Validation failed during registration.", [
                'errors' => $e->errors()
            ]);

            // For API requests, return validation errors as JSON
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Validation failed.',
                    'errors' => $e->errors(),
                ], 422);
            }

            // For form submissions, redirect back with validation errors
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error("Unexpected error during registration.", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // For API requests, return a generic error as JSON
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Something went wrong. Please try again later.',
                ], 500);
            }

            // For form submissions, redirect back with an error message
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.');
        }
    }


}
