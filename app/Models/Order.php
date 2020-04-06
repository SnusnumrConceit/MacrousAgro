<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const STATUS_CREATED   = 'created';
    const STATUS_CANCELED  = 'canceled';
    const STATUS_PAYED     = 'payed';
    const STATUS_DELIVERY  = 'delivery';
    const STATUS_COMPLETED = 'completed';

    protected $fillable = ['product_id', 'user_id', 'order_status_code'];

    protected $dates = ['created_at', 'updated_at'];

    protected $perPage = 15;

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function positions()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class)->select(['payment_amount']);
    }
}
