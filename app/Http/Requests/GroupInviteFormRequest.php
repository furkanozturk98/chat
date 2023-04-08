<?php

namespace App\Http\Requests;

use App\Rules\ExistingGroupInvite;
use App\Rules\ExistingMember;
use App\Rules\FriendRequest\ValidUser;
use App\Services\UserService;
use Illuminate\Foundation\Http\FormRequest;

class GroupInviteFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $userService = app(UserService::class);

        $user = $userService->findUserByEmail($this->input('email'));

        $group = $this->input('group_id');

        return [
            'group_id' => [
                'required',
            ],
            'email' => [
                'required',
                'email',
                new ValidUser($user),
                new ExistingMember($user, $group),
                new ExistingGroupInvite($user, $group),
            ],
        ];
    }
}
