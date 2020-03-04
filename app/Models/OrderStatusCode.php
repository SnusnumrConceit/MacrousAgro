<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Route;

class OrderStatusCode extends Model
{
    public static function apiRoutes()
    {
        Route::get('order_status_codes', function () {
            return self::all()->toJson();
        });
    }
}
