<?php

namespace App\Http\Requests\User;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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
