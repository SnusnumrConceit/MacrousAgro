<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'total'     => $this->total(),
            'last_page' => $this->lastPage()
        ];
    }

    public function with($request)
    {
        return [
            'meta' => [
                'total'     => $this->total(),
                'last_page' => $this->lastPage()
            ]
        ];
    }
}
