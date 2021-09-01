<?php

return [

    #------------------------
    # Vandar API Base Url
    #------------------------
    'api_base_url' =>  'https://api.vandar.io/',


    #------------------------
    # Vandar IPG Base Url
    #------------------------
    'ipg_base_url' =>  'https://ipg.vandar.io/',


    #------------------------
    # Registered mobile number in Vandar for login
    #------------------------
    'mobile' => env('VANDAR_MOBILE'),


    #------------------------
    # Vandar Account Password for login
    #------------------------
    'password' => env('VANDAR_PASSWORD'),


    #------------------------
    # Business name in vandar, is used for sending request
    #------------------------
    'business_slug' => env('VANDAR_BUSINESS_SLUG'),


    #------------------------
    # API Key of IPG for sending requests
    #------------------------
    'api_key' => env('VANDAR_API_KEY'),


    #------------------------
    # Callback Url for return requests from bank page (Inserted in the Vandar dashboard)
    #------------------------
    'callback_url' => env('VANDAR_CALLBACK_URL'),


    #------------------------
    # Notify Url for getting webhook request from Vandar
    #------------------------
    'notify_url' => env('VANDAR_NOTIFY_URL'),

    # Authentication information, filled and used automatically
    'auth' => [
        'token_type' => null,
        'expires_in' => null,
        'access_token' => null,
        'refresh_token' => null,
    ]
];
