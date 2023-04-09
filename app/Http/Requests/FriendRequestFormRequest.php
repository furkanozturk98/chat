<?php

namespace App\Http\Requests;

use App\Rules\FriendRequest\ExistingFriend;
use App\Rules\FriendRequest\ExistingFriendRequest;
use App\Rules\FriendRequest\SendYourself;
use App\Rules\FriendRequest\ValidUser;
use App\Services\UserService;
use Illuminate\Foundation\Http\FormRequest;

class FriendRequestFormRequest extends FormRequest
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

        return [
            'user_id' => [
                $user ? 'nullable' : 'required',
            ],
            'email' => [
                'required',
                'email:filter',
                new SendYourself(),
                new ValidUser($user),
                new ExistingFriend($user),
                new ExistingFriendRequest($user),
            ],
        ];
    }
}
