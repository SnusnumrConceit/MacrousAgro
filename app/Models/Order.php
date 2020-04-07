<?php

namespace App\Models;

use Route;
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

    protected $appends = ['display_created_at', 'display_updated_at'];

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
        return $this->hasOne(Invoice::class);
    }

    public static function getStatuses()
    {
        return self::getConstants('STATUS_');
    }

    private static function getConstants(string $prefix)
    {
        $constants = (new \ReflectionClass(self::class))->getConstants();

        return array_filter($constants, function ($constant) use ($prefix) {
            return preg_match("/$prefix/", $constant);
        }, ARRAY_FILTER_USE_KEY);
    }

    public function getDisplayCreatedAtAttribute()
    {
        return $this->created_at->format('d.m.Y H:i:s');
    }

    public function getDisplayUpdatedAtAttribute()
    {
        return $this->updated_at->format('d.m.Y H:i:s');
    }

    public static function apiRoutes()
    {
        Route::get('order_status_codes', function () {
            return self::getStatuses();
        });
    }
}
