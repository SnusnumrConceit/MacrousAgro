<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\Order::class, function (Faker $faker) {
    $user = App\User::inRandomOrder()->limit(1)->first();

    return [
        'user_id' => $user->id,
        'order_status_code' => array_rand(array_flip(\App\Models\Order::getStatuses()))
    ];
});
