<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use App\User;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    $title = $faker->sentence;
    $slug = str_slug($title, '-');
    $user_id = rand(1, 10);
    return [
        'title' => $title,
        'user_id' => factory(User::class),
        'slug' => $slug,
        'body' => $faker->text,
        'created_at' => now(),
        'updated_at' => now()
    ];
});
