<?php

namespace App\Http\Requests\Product;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ProductUpdateRequest extends ProductStoreRequest
{
    public function rules()
    {
        return parent::rules();
    }
}
