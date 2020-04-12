<?php

namespace App\Http\Requests\Order\Item;

use App\Http\Requests\Order\OrderUpdateRequest;
use Illuminate\Foundation\Http\FormRequest;

class OrderItemUpdateRequest extends OrderUpdateRequest
{
    public function rules()
    {
        return [
            'order_item_status_code' => parent::rules()['order_status_code']
        ];
    }
}
