<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetail extends JsonResource
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
            'id'          =>  $this->id,
            'order_status_code' =>  $this->order_status_code,
            'positions'   =>  $this->positions->load('product'),
            'customer'    =>  $this->customer,
            'created_at'  =>  $this->display_created_at,
            'updated_at'  =>  $this->display_updated_at
        ];
    }
}
