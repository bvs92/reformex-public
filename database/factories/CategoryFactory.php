<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use Illuminate\Support\Str;
use Faker\Generator as Faker;


$factory->define(Category::class, function (Faker $faker) {
    $name = $faker->word;

    return [
        'name'  => $name,
        'slug'  => Str::slug($name),
        'description' => $faker->text
    ];
});