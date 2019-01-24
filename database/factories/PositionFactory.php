<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Position::class, function (Faker $faker) {
    return [
        'name' => $faker->city,
        'description' => $faker->realText(200)
    ];
});
