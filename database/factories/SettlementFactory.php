<?php

namespace Vandar\Cashier\Database\Factories;

use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Vandar\Cashier\Models\Settlement;


$factory->define(Settlement::class, function (Faker $faker) {
    return [
        'settlement_id' => Str::uuid(),
        'amount' => $faker->randomNumber(5, true),
        'amount_toman' => $faker->randomNumber(4, true),
        'wage_toman' => $faker->randomDigit(),
        'iban' => $faker->numerify('IR############'),
        'iban_id' => Str::uuid(),
        'track_id' => Str::uuid(),
        'payment_number' => $faker->numerify('############'),
        'transaction_id' => $faker->numerify('############'),
        'status' => $faker->randomElement(['INIT, PENDING, DONE, FAILED, CANCELED']),
        'wallet' => $faker->randomNumber(5, true),
        'is_instant' => rand(0, 1),
        'settlement_date' => $faker->date('Y-m-d'),
        'settlement_time' => $faker->time('H:i:s'),
        'settlement_date_jalali' => rand(1400, 1410) . '/' . rand(1, 12) . '/' . rand(1, 30),
        'settlement_done_time_prediction' => $faker->dateTimeThisMonth(),
        'errors' => json_encode([])
    ];
});
