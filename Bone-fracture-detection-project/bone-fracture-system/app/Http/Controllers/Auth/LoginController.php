<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\LoginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        Log::info("seds");
        try {
            $validatedData = $request->validate([
                'email' => [
                    'required',
                    'email',
                    'exists:users,email',
                ],
                'password' => 'required',
            ]);
            $user = $this->loginService->loginUser($validatedData);
            Log::info($user);
            if ($user) {
                return redirect()->route('analyze-fracture')->with('success', 'Login successful!');
            }
            return redirect()->back()->withErrors(['password' => 'Invalid credentials. Please try again.']);

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.');
        }
    }

}
