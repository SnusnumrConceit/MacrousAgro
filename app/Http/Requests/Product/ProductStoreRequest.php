<?php

namespace App\Http\Requests\Product;

use App\Models\Product;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ProductStoreRequest extends FormRequest
{
    private $minWidth, $minHeight, $maxWidth, $maxHeight, $maxSize;

    protected function prepareForValidation()
    {
        $this->minWidth  = Product::DIMENSIONS['min_width'];
        $this->minHeight = Product::DIMENSIONS['min_height'];
        $this->maxWidth  = Product::DIMENSIONS['max_width'];
        $this->maxHeight = Product::DIMENSIONS['max_height'];
        $this->maxSize   = Product::MAX_FILE_SIZE;
    }
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
            'category_id' => 'required|exists:categories,id',
            'image' =>  "nullable|file|mimes:jpg,jpeg,png|max:$this->maxSize|dimensions:min_width:$this->minWidth,min_height:$this->minHeight,max_width:$this->maxWidth,max_height:$this->maxHeight"
        ];
    }

    public function attributes()
    {
        return [
            'title' => __('products.validation.attributes.title'),
            'price' => __('products.validation.attributes.price'),
            'category_id' => __('products.validation.attributes.category_id'),
            'image' => __('products.validation.attributes.image'),
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
            'errors' => $validator->errors()
        ], 500)));
    }
}
