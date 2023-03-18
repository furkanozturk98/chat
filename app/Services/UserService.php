<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    /**
     * @param string $email
     *
     * @return User|null
     */
    public function findUserByEmail(string $email): ?User
    {
        return User::query()
            ->where('email', $email)
            ->first();
    }
}
