<?php

namespace Vandar\VandarCashier\Utilities;

class VandarValidationRules
{

    public static function ipg()
    {
        return [

            'pay' => [
                'api_key' => 'required|string',
                'amount' => 'required|numeric|gte:10000',
                'callback_url' => 'required|string|max:191',
                'mobile_number' => 'nullable|string',
                'factorNumber' => 'nullable|string',
                'description' => 'nullable|string|max:255',
                'valid_card_number' => 'nullable|string',
            ],

            'morphs' => [
                'payable_type' => 'nullable|string',
                'payable_id' => 'nullable|numeric',
                'paymentable_type' => 'nullable|string',
                'paymentable_id' => 'nullable|numeric',
            ]

        ];
    }


    public static function auth()
    {
        return [
            'login' => [
                'mobile' => 'required|string',
                'password' => 'required|string'
            ]
        ];
    }



    public static function business()
    {
        return [
           'users' => [
                'business' => 'nullable|string',
                'page' => 'nullable|numeric|gte:1',
                'per_page' => 'nullable|numeric|gte:1'
            ]
        ];
    }



    public static function bills()
    {
        return [
            'list' => [
                'page' => 'nullable|numeric|gte:1',
                'per_page' => 'nullable|numeric|gte:1'
            ]
        ];
    }



    public static function mandate()
    {
        return [
            'store' => [
                'bank_code' => 'required|string',
                'mobile' => 'required|string',
                'callback_url' => 'required|string',
                'count' => 'required|int',
                'limit' => 'required|int',
                'name' => 'nullable|string',
                'email' => 'nullable|string|email',
                'expiration_date' => 'required|date_format:Y-m-d',
                'wage_type' => 'nullable|',
            ]
        ];
    }



    public static function settlement()
    {
        return [
            'store' => [
                'amount' => 'required|numeric|gte:50000',
                'iban' => 'required|string',
                'notify_url' => 'nullable|string',
                'payment_number	' => 'nullable|numeric',
            ],

            'list' => [
                'page' => 'nullable|numeric|gte:1',
                'per_page' => 'nullable|numeric|gte:1'
            ]
        ];
    }



    public static function withdrawal()
    {
        return [
            'store' => [
                'authorization_id' => 'required|string',
                'amount' => 'required|string',
                'withdrawal_date' => 'nullable|string',
                'is_instant' => 'nullable|boolean',
                'notify_url' => 'nullable|string',
                'max_retry_count' => 'nullable|numeric|min:1|max:16',
            ]
        ];
    }
}
