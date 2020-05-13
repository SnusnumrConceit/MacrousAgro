<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rule;

class UserUpdateRequest extends UserStoreRequest
{
    public function rules()
    {
        return [
            'last_name' => 'required|between:2,100',
            'first_name' => 'required|between:2,60',
            'birthday' => 'required|date',
            'email' => ['required','email', 'between:10,100',
                Rule::exists('users')->where('email', $this->email)
            ],
        ];
    }
}
