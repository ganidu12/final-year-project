<?php

namespace App\Services;

use App\Repository\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Register a new user after validation.
     */
    public function registerUser(array $data)
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'user_type' => $data['user_type'],
            'age' => $data['age'] ?? null,
        ];

        $user = $this->userRepository->createUser($userData);

        if (Auth::loginUsingId($user->id)) {
            session()->regenerate();
            return $user;
        }

        return false;
    }
}

