<?php

namespace App\Repository;

use App\Models\User;
use Illuminate\Support\Str;

class UserRepository
{
    /**
     * Create a new user in the database.
     */
    public function createUser(array $data): User
    {
        $user = new User();
        $user->id = (string) Str::uuid();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->user_type = $data['user_type'];
        $user->age = $data['age'];
        $user->save();
        return $user;
    }

    /**
     * Check if a user exists by email.
     */
    public function findUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }


    public function updateUser($userId, $data)
    {

        $user = User::findOrFail($userId);
        $user->update([
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ??null,
            'profile_img' => $data['profile_img'] ?? $user->profile_img,
            'age' => $data['age'] ?? $user->age,
        ]);
        $user->save();
        return $user;
    }

    public function fetchUserDetails($search,$type)
    {
        if ($type == 'email'){
            $patients = User::where('email', 'LIKE', "%{$search}%")
                ->where('user_type', 'regular_user')
                ->get(['id', 'email', 'name', 'age']);
        }else{
            $patients = User::where('name', 'LIKE', "%{$search}%")
                ->where('user_type', 'regular_user')
                ->get(['id', 'email', 'name', 'age']);
        }
        return $patients;
    }

    public function setResetCode($email,$code)
    {
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->update(['reset_code' => $code]);
        }
        return $user;
    }

    public function resetPassword($email, $newPassword)
    {
        $user = User::where('email',$email)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found!']);
        }

        return $user->update(['password' => $newPassword, 'reset_code' => null]);
    }


}
