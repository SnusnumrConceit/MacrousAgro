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
//        dd(str_replace('order_status_code', 'item_', strpos('order_status_code', 'status') + 0));
//        return array_map(function ($rule) {
//            return '';
//        }, parent::rules());
    }
}
