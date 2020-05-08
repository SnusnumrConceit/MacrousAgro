<?php

namespace App\Http\Resources\Permission;

use Illuminate\Http\Resources\Json\JsonResource;

class Permission extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $collection = $this->map(function ($permission) {
           return  [
               'id' => $permission->id,
               'description' => __('permissions.' . $permission->name)
           ];
        });

        return $collection;
    }
}
