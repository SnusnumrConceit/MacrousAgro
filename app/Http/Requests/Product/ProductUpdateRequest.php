<?php

namespace App\Http\Requests\Product;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ProductUpdateRequest extends FormRequest
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
            'title' => 'required|max:100',
            'description' => 'required|between:10,2000',
            'price' => 'required|numeric',
            'url' => 'required',
            'category_id' => "required|exists:categories,id"
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
            'msg' => __('form_request_validation_failed_error'),
            'error' => $validator->errors()
        ], 500)));
    }
}
