<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\StudentTagCategory::class, function (Faker $faker) {
    return [
        'name' => $faker->safeColorName,
        'description' => $faker->text,
        'reference' => $faker->word
    ];
});
