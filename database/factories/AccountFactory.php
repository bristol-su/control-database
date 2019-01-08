<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Account::class, function (Faker $faker) {
    $letters = array_merge(range('A','Z'));
    return [
        'description' => $faker->paragraph,
        'is_department_code' => true,
        'code' => $letters[rand(0,25)].$letters[rand(0,25)].$letters[rand(0,25)]
    ];
});
