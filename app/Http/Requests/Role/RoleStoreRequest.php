<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class RoleStoreRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:roles,name',
            'slug' => 'required|string|max:255|unique:roles,slug',
            'description' => 'required|string|max:255',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'required|exists:permissions,id'
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('roles.validation.attributes.name'),
            'slug' => __('roles.validation.attributes.slug'),
            'description' => __('roles.validation.attributes.description'),
            'permissions' => __('roles.validation.attributes.permissions')
        ];
    }

    public function messages()
    {
        return [
            'permissions.array' => __('roles.validation.messages.permissions.array'),
            'permissions.min' => __('roles.validation.messages.permissions.min'),
            'permissions.*.required' => __('roles.validation.messages.permissions.*.required'),
            'permissions.*.exists' => __('roles.validation.messages.permissions.*.exists'),
        ];
    }
}
