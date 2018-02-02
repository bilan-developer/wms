<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Product::class, function (Faker $faker) {

    return [
        'tm' => $faker->name,
        'name' => $faker->name,
        'units' => $faker->name,
        'total' => $faker->randomNumber(2),
        'all' => $faker->randomNumber(2),
        'price' => $faker->randomNumber(2),
    ];
});
