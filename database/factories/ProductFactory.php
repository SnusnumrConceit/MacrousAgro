<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'category_id' => rand(3, 5),
        'description' => $faker->text(),
        'title'       => $faker->title(),
        'price'       => $faker->numberBetween(500, 10000)
    ];
});
