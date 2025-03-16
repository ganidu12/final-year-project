<?php

namespace App\Services;

use App\Repository\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ForgotPasswordService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function resetCode($email,$code)
    {
      return $this->userRepository->setResetCode($email,$code);
    }

    public function resetPassword($email, $newPassword)
    {
        Log::info($newPassword);
        $newPassword = Hash::make($newPassword);
        $this->userRepository->resetPassword($email,$newPassword);
    }
}
