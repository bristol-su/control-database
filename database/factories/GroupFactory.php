<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Group::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'unioncloud_id' => $faker->numberBetween(111111, 999999),
        'email' => $faker->email
    ];
});
