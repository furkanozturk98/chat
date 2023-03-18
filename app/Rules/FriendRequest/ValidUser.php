<?php

namespace App\Rules\FriendRequest;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class ValidUser implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(public ?User $user)
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return isset($this->user);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The email is not exist.Please enter an exist email.';
    }
}
