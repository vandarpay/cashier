<?php

namespace Vandar\Cashier\Database\Factories;

use Faker\Generator as Faker;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Str;
use Vandar\Cashier\Models\Mandate;


$factory->define(Mandate::class, function (Faker $faker) {
    return [
        'token' => Str::uuid(),
        'authorization_id' => Str::uuid(),
        'user_id' => factory(User::class),
        'name' => $faker->name,
        'mobile_number' => $faker->numerify('09#########'),
        'email' => $faker->unique()->safeEmail,
        'count' => rand(1, 30),
        'limit' => $faker->randomNumber(5, true),
        'expiration_date' => now()->addDays(3),
        'bank_code' => $faker->randomElement([
            '012', '016', '021', '055', '056', '058', '059', '062', '063', '066', '069', '060'
        ]),
        'status' => $faker->randomElement(['INIT, FAILED, SUCCEED, FAILED_TO_ACCESS_BANK']),
        'is_active' => rand(0, 1),
        'errors' => json_encode([]),
    ];
});
