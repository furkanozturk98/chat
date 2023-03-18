<?php

namespace App\Rules\FriendRequest;

use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class ExistingFriendRequest implements Rule
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
        if (is_null($this->user)) {
            return true;
        }

        $data = FriendRequest::query()
            ->where('from', auth()->id())
            ->where('to', $this->user->id)
            ->first();

        return !isset($data);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Already a friendship request send to this email.';
    }
}
