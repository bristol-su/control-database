<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\GroupTag::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->text,
        'reference' => $faker->word
    ];
});
