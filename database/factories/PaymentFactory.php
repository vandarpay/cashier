<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Vandar\Cashier\Models\Payment;



$factory->define(Payment::class, function (Faker $faker) {
    return [
        'token' => Str::uuid(),
        'user_id' => $faker->randomDigit(),
        'amount' => $faker->randomDigitNotZero(),
        'real_amount' => $faker->randomDigitNotZero(),
        'wage' => $faker->randomDigit(),
        'status' => $faker->randomElement(['INIT', 'SUCCEED', 'FAILED']),
        'mobile_number' => $faker->numerify('09#########'),
        'trans_id' => $faker->unique()->randomDigit(),
        'ref_number' => Str::uuid(),
        'tracking_code' => $faker->randomDigit(),
        'factor_number' => $faker->randomDigit(),
        'description' => Str::limit(15),
        'valid_card_number' => $faker->randomNumber(16),
        'card_number' => $faker->numerify('######******####'),
        'cid' => Str::uuid(),
        'payment_date' => $faker->dateTime(),
        'errors' => json_encode($faker->randomElement([
            "نتیجه تراکنش قبلا از طرف وندار اعلام گردیده.",
            "وارد کردن api key الزامی است",
            "api_key معتبر نیست",
            "IP پذیرنده معتبر نیست",
            "وارد کردن token الزامی است",
            "token معتبر نیست",
            "تراکنش با خطا مواجه شده است",
            "وارد کردن callback_url الزامی است",
            "callback_url معتبر نیست",
            "وارد کردن amount الزامی است",
            "amount نباید کوچکتر از 1000 باشد",
            "amount باید عدد یا رشته‌ای از اعداد باشد",
            "کد ملی قابل قبول نیست.",
            "شماره کارت قابل قبول نیست.",
          ]), rand(1, 14)),
    ];
});
