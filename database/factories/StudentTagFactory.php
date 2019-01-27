<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\StudentTag::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->text,
        'reference' => $faker->word
    ];
});
