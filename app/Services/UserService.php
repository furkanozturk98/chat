<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    /**
     * @param string|null $email
     *
     * @return User|null
     */
    public function findUserByEmail(?string $email): ?User
    {
        if (empty($email)) {
            return null;
        }

        return User::query()
            ->where('email', $email)
            ->first();
    }

    /**
     * @param string $id
     *
     * @return User|null
     */
    public function findUserById(string $id): ?User
    {
        return User::query()
            ->find($id);
    }
}
