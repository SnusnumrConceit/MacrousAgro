<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\News;
use Faker\Generator as Faker;

$factory->define(News::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'description' => $faker->text(100),
        'image' => $faker->url,
        'publication_date' => $faker->date(),
        'is_publicated' => $faker->boolean
    ];
});
