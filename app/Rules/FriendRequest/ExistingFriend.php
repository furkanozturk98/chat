<?php

namespace App\Rules\FriendRequest;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class ExistingFriend implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(public ?User $user = null)
    {
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
        if (is_null($this->user)) {
            return true;
        }

        $friend = Friend::query()
            ->where('user_id', auth()->user()->id)
            ->where('friend_id', $this->user->id)
            ->first();

        return !isset($friend);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The user of this email is already your friend';
    }
}
