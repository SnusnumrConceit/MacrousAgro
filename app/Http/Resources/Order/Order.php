<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class Order extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'customer'  => $this->customer,
            'invoice'   => $this->invoice,
            'order_status_code' => $this->order_status_code,
            'display_created_at' => $this->display_created_at
        ];
    }
}
