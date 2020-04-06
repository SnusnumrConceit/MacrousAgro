<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    const STATUSES = [
        'WAIT'      => 1,
        'CANCELED'  => 2,
        'PAYED'     => 3
    ];

    protected $fillable = ['order_id', 'payment_amount', 'invoice_status_code'];
}
