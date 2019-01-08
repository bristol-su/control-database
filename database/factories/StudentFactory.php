<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Student::class, function (Faker $faker) {
    return [
        'uc_uid' => $faker->numberBetween(111111, 999999)
    ];
});
