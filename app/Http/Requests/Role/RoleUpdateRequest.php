<?php

namespace App\Http\Requests\Role;

use Illuminate\Validation\Rule;

class RoleUpdateRequest extends RoleStoreRequest
{
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('roles')->ignore($this->role->id),
                ],
                'slug' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('roles')->ignore($this->role->id),
                ],
            ]);
    }
}
