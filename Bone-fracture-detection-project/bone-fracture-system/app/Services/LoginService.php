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
        Log::info("gggggggggggggg");
        $user = $this->userRepository->findUserByEmail($validatedData['email']);
        Log::info($user);
        if (!Hash::check($validatedData['password'], $user->password)) {
            Log::info("llllllllllllllll");
            return false;
        }
        if (Auth::loginUsingId($user->id)) {
            session()->regenerate();
            Log::info("kkkkkkkkk");
            return $user;
        }

        Log::info("jjjjjjjjjjjjjjjjjjjjjjjj");
        return false;
    }
}
