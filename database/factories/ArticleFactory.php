<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Article;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {
    return [
        'title' => $faker->firstName,
        'description' => $faker->text(2000),
        'publication_date' => $faker->date(),
        'is_publicated' => $faker->boolean
    ];
});
