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

    /**
     * Покупатель заказа
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Позиции заказа
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function positions()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Счёт-наряд
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    /**
     * Получение статусов
     *
     * @return array
     */
    public static function getStatuses()
    {
        return self::getConstants('STATUS_');
    }

    /**
     * Получение списка констант
     *
     * @param string $prefix
     * @return array
     * @throws \ReflectionException
     */
    private static function getConstants(string $prefix)
    {
        $constants = (new \ReflectionClass(self::class))->getConstants();

        return array_filter($constants, function ($constant) use ($prefix) {
            return preg_match("/$prefix/", $constant);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Дата создания заказа для отображения
     *
     * @return string in date format: d.m.Y H:i:s
     */
    public function getDisplayCreatedAtAttribute()
    {
        return $this->created_at->format('d.m.Y H:i:s');
    }

    /**
     * Дата обновления заказа для отображения
     *
     * @return string in date format: d.m.Y H:i:s
     */
    public function getDisplayUpdatedAtAttribute()
    {
        return $this->updated_at->format('d.m.Y H:i:s');
    }

    /**
     * Список API-маршрутов
     *
     * @return \Route
     */
    public static function apiRoutes()
    {
        Route::get('order_status_codes', function () {
            return array_values(self::getStatuses());
        });
    }
}
