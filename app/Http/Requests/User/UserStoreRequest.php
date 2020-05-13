<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;

class UserStoreRequest extends FormRequest
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
            'last_name' => 'required|between:2,100',
            'first_name' => 'required|between:2,60',
            'password' => 'required|between:8,60|confirmed',
            'email' => 'required|email|between:10,100',
            'birthday' => 'required|date',
        ];
    }

    public function attributes()
    {
        return [
            'last_name' => __('users.validation.attributes.last_name'),
            'first_name' => __('users.validation.attributes.first_name'),
            'birthday' => __('users.validation.attributes.birthday')
        ];
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException(__('form_request_authorization_error'), 403);
    }

    protected function failedValidation(Validator $validator)
    {
        throw (new ValidationException($validator, response()->json([
            'status' => 'error',
            'message' => __('form_request_validation_failed_error'),
            'errors' => $validator->errors()
        ], 500)));
    }
}
