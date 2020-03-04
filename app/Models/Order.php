<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['product_id', 'user_id', 'order_status_code'];

    protected $dates = ['created_at', 'updated_at'];

    public function customer()
    {
        return $this->hasOne(User::class);
    }

    public function positions()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function getCreatedAtAtrribute()
    {
        return $this->created_at->format('d.m.Y');
    }

    public function orderStatusCode()
    {
        return $this->hasOne(OrderStatusCode::class, 'order_status_code', 'id');
    }
}
