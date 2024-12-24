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
}
