<?php

namespace App\Http\Requests\Order;

use App\Models\Order;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
{
    private $statuses;

    protected function prepareForValidation()
    {
        $this->statuses = Order::getStatuses();
    }

    public function rules()
    {
        return [
            'order_status_code' => 'required|boolean|in_statuses_array'
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->addExtension('in_statuses_array', function ($attribute, $value) {
           return in_array($value,$this->statuses);
        });
    }

    public function messages()
    {
        return [
            'order_status.in_statuses_array' => __('orders.validation.messages.invalid_status')
        ];
    }

    public function attributes()
    {
        return [
            'order_status_code' => __('orders.validation.attributes.order_status_code')
        ];
    }
}
