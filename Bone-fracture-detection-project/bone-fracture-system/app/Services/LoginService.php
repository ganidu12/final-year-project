<?php

namespace App\Services;

use App\Repository\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Register a new user after validation.
     */
//    public function loginUser($validatedData)
//    {
//        $user = $this->userRepository->findUserByEmail($validatedData['email']);
//        if ($user && Hash::check($validatedData['password'], $user->password)) {
//            return response()->json([
//                'message' => 'Login successful.',
//                'user' => $user,
//            ], 200);
//        }
//        return response()->json([
//            'message' => 'Invalid password.',
//        ], 401);
//    }

    public function loginUser(array $validatedData)
    {
        $user = $this->userRepository->findUserByEmail($validatedData['email']);
        Log::info($user);
        if (!Hash::check($validatedData['password'], $user->password)) {
            return false;
        }
        if (Auth::loginUsingId($user->id)) {
            session()->regenerate();
            return $user;
        }
        return false;
    }

    public function logoutUser()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/login')->with('success', 'You have been logged out successfully.');
    }
}
