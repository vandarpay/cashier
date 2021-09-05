<?php

namespace Vandar\Cashier\Database\Factories;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Vandar\Cashier\Models\Payment;



$factory->define(Payment::class, function (Faker $faker) {
    return [
        'token' => Str::uuid(),
        'authorization_id' => Str::uuid(),
        'user_id' => factory(User::class),
        'name' => $faker->name,
        'mobile_number' => $faker->numerify('09#########'),
        'email' => $faker->unique()->safeEmail,
        'count' => rand(1, 30),
        'limit' => $faker->randomNumber(5, true),
        'expiration_date' => $faker->dateTimeThisDecade(),
        'bank_code' => $faker->randomElement([
            '012', '016', '021', '055', '056', '058', '059', '062', '063', '066', '069', '060'
        ]), rand(1),
        'status' => $faker->randomElement(['INIT, FAILED, SUCCEED, FAILED_TO_ACCESS_BANK']), rand(1),
        'is_active' => rand(0, 1),
        'errors' => [],
    ];
});
