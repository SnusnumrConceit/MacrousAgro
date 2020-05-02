<?php

namespace App\Services;


use App\Exports\OrdersExport;

class OrderService
{
    public function export()
    {
        return new OrdersExport;
    }
}