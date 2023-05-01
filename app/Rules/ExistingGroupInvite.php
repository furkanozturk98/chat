<?php

namespace App\Rules;

use App\Models\Group;
use App\Models\GroupInvite;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class ExistingGroupInvite implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(public ?User $user = null, public ?Group $group = null)
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
        if (is_null($this->user) || is_null($this->group)) {
            return true;
        }

        $invite = GroupInvite::query()
            ->where('group_id', $this->group->id)
            ->where('from', auth()->id())
            ->where('to', $this->user->id)
            ->first();

        return !isset($invite);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Already a group invite send to this email.';
    }
}
