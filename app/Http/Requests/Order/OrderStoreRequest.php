<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
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
            'products' => 'required|array|min:1',
//            'products.*'  => 'required|string|unique'
        ];
    }

    public function attributes()
    {
        return [
            'products' => __('orders.validation.attributes.products')
        ];
    }

    public function messages()
    {
        return [
            'products.array' => __('orders.validation.messages.invalid_format'),
            'products.*.string' => __('orders.validation.messages.invalid_format')
        ];
    }
}
