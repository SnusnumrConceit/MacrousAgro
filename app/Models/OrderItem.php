<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['product_id', 'user_id', 'order_item_status_code'];

    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
