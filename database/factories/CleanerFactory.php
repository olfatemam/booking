<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Cleaner;
use Faker\Generator as Faker;

$factory->define(Cleaner::class, function (Faker $faker) {
    return [
     'name' => $faker->name,
       //
    ];
});
