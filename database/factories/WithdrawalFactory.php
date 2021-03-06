<?php

namespace Vandar\Cashier\Database\Factories;

use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Vandar\Cashier\Models\Mandate;
use Vandar\Cashier\Models\Withdrawal;


$factory->define(Withdrawal::class, function (Faker $faker) {
    return [
        'authorization_id' => factory(Mandate::class),
        'withdrawal_id' => Str::uuid(),
        'amount' => $faker->randomNumber(5, true),
        'wage_amount' => $faker->randomDigit(),
        'is_instant' => rand(0, 1),
        'retry_count' => rand(1, 16),
        'max_retry_count' => rand(1, 16),
        'gateway_transaction_id' => $faker->numerify('############'),
        'payment_number' => $faker->numerify('############'),
        'status' => $faker->randomElement(['INIT, PENDING, DONE, FAILED, CANCELED']),
        'description' => $faker->sentence(3),
        'withdrawal_date' => date('Y-m-d'),
        'error_code' => rand(0, 32),
        'error_message' => '',
    ];
});
