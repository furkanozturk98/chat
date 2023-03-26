<?php

namespace App\Rules;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class ExistingMember implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(public ?User $user, public ?Group $group)
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

        $groupMember = GroupMember::query()
            ->where('group_id', $this->group->id)
            ->where('member_id', $this->user->id)
            ->first();

        return !isset($groupMember);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'You cannot send a group invite to already member of this group';
    }
}
