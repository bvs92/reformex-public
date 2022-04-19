<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Demand;
use Faker\Generator as Faker;

$factory->define(Demand::class, function (Faker $faker) {
    return [
        'subject' => $faker->sentence,
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'phone' => $faker->phoneNumber,
        'city'  => $faker->city,
        'message' => $faker->text,
        'created_at'    => now(),
        'updated_at'    => now()
    ];
});
