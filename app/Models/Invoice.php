<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    const STATUS_WAIT     = 'wait';
    const STATUS_CANCELED = 'canceled';
    const STATUS_PAYED    = 'payed';

    protected $fillable = ['order_id', 'payment_amount', 'invoice_status_code'];

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
}
