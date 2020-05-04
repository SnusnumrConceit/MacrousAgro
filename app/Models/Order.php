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
     * Customer
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
     * Positions
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
     * Invoice
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    /**
     * Активные заказы
     *
     * Active orders
     *
     * @param $q
     * @return mixed
     */
    public function scopeActive($q)
    {
        return $q->where(function($q) {
            return $q->where(['order_status_code' => self::STATUS_CREATED])
                ->orWhere(['order_status_code' => self::STATUS_PAYED])
                ->orWhere(['order_status_code' => self::STATUS_DELIVERY]);
        });
    }

    /**
     * Завершённые заказы
     *
     * Completed orders
     *
     * @param $q
     * @return mixed
     */
    public function scopeCompleted($q)
    {
        return $q->where(['order_status_code' => self::STATUS_COMPLETED])
            ->orWhere(['order_status_code' => self::STATUS_CANCELED]);
    }

    /**
     * Отменённые заказы
     *
     * Canceled orders
     *
     * @param $q
     * @return mixed
     */
    public function scopeCanceled($q)
    {
        return $q->where(['order_status_code' => self::STATUS_CANCELED]);
    }

    /**
     * Получение статусов
     *
     * Get list of the order statuses
     *
     * @return array
     * @throws \ReflectionException
     */
    public static function getStatuses()
    {
        return self::getConstants('STATUS_');
    }

    /**
     * Получение списка констант по префиксу
     *
     * Get list of the constants by prefix
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
     * Display created_at date in not UTC format
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
     * Display updated_at date in not UTC format
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
     * API-routes
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
