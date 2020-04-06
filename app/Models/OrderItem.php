<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    const STATUSES = [
        'CREATED'   => 1,
        'CANCELED'  => 2,
        'PAYED'     => 3,
        'COMPLETED' => 4
    ];

    protected $fillable = ['product_id', 'order_id', 'order_item_status_code'];

    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
