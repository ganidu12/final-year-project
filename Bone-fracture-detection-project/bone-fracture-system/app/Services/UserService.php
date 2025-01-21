<?php

namespace App\Services;


use App\Repository\UserRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Update the user's profile.
     */
    public function updateUser($userId, $data)
    {
        if (isset($data['profile_img']) && $data['profile_img']->isValid()) {
            $fileName = time() . '_' . $data['profile_img']->getClientOriginalName();
            $filePath = $data['profile_img']->storeAs('profile_img', $fileName, 'public');
            $data['profile_img'] = $filePath;
        }
        return $this->userRepository->updateUser($userId, $data);
    }

    public function fetchUserDetails($search,$type){
        return $this->userRepository->fetchUserDetails($search,$type);
    }

}
