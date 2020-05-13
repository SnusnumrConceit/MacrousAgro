<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'last_name'  => 'required|between:2,100',
            'first_name' => 'required|between:2,60',
            'password'   => 'required|between:8,60|confirmed',
            'email'      => 'required|email|between:10,100',
            'birthday'   => 'required|date',
        ];
    }

    public function attributes()
    {
        return array_merge(
            parent::attributes(),
            ['birthday' => __('users.validation.attributes.birthday')]
        );
    }
}
