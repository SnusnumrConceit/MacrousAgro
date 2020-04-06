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
            'price'     => $this->price,
            'invoice'   => $this->invoice,
            'status'    => $this->status
        ];
    }
}
