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
            'status_code' =>  $this->orderStatusCode,
            'positions'   =>  $this->positions,
            'customer'    =>  $this->user_id,
            'created_at'  =>  $this->created_at,
            'updated_at'  =>  $this->updated_at
        ];
    }
}
