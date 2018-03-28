<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
/*
$factory->define(App\Task::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->title,
    ];
});
*/

$factory->define(App\Link::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->name,
        'url' => $faker->url,
        'description' => $faker->paragraph,
    ];
});

$factory->define(App\Balances::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->id,
        'date' => $faker->date,
        'balance' => $faker->balance,
        'spent' => $faker->spent,
    ];
});
