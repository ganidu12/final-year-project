<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\LoginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    protected $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function showLoginForm()
    {
        return view('auth.login'); // Points to resources/views/auth/login.blade.php
    }

    public function login(Request $request)
    {
        try {
            // Validate the request
            $validatedData = $request->validate([
                'email' => [
                    'required',
                    'email',
                    'exists:users,email',
                ],
                'password' => 'required',
            ]);

            // Use the LoginService to handle user authentication
            $user = $this->loginService->loginUser($validatedData);
            if ($user->isSuccessful()) {
                return redirect()->route('home')->with('success', 'Login successful!');
            }

            // If authentication fails, redirect back with an error message
            return redirect()->back()->withErrors(['password' => 'Invalid credentials. Please try again.']);

        } catch (ValidationException $e) {
            // Redirect back with validation errors
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Handle unexpected errors
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.');
        }
    }

}
