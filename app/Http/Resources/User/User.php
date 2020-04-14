<?php

namespace App\Http\Resources\User;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'full_name'          => $this->full_name,
            'email'              => $this->email,
            'display_birthday'   => $this->display_birthday,
            'birthday'           => $this->birthday->format('Y-m-d'),
            'registration_date'  => $this->registration_date,
            'last_activity_date' => $this->last_activity_date,
            'last_name'          => $this->last_name,
            'first_name'         => $this->first_name,
            'role'               => $this->role
        ];
    }
}
