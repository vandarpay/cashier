<?php

namespace Vandar\Cashier;

class Vandar
{
    const VERSION = '1.0.0-a';

    // URL Generation Functionality
    protected const API_VERSIONS = [
        'AUTH' => '3',
        'TRANSACTION' => '2',
        'SETTLEMENT' => '2.1',
        'IPG' => '3',
        'MANDATE' => '2',
        'WITHDRAWAL' => '2',
    ];

    /**
     * @param string $api can be AUTH, TRANSACTION, SETTLEMENT, IPG, MANDATE, or WITHDRAWAL
     * @param string $additional Additional portion to add to the url
     * @param string|null $version_number the api version to which the requests should be sent, for backwards compatibility reasons
     * @return string full url for the selected endpoint
     */
    public static function url(string $api, string $additional='', string $version_number=null): string
    {
        $api = strtoupper($api);
        $append_version = true;
        switch ($api) {
            case 'IPG':
                $base_url = config('vandar.ipg_base_url', 'https://ipg.vandar.io/');
                break;
            case 'IPG_API':
                $base_url = config('vandar.ipg_base_url', 'https://ipg.vandar.io/api/');
                $api = 'IPG';
                break;
            case 'MANDATE':
                $base_url = config('vandar.mandate_redirect_url', 'https://subscription.vandar.io/authorizations/');
                $append_version = false;
                break;
            case 'MANDATE_API':
                $base_url = config('vandar.api_base_url', 'https://api.vandar.io/');
                $additional = 'business/' . config('vandar.business_slug') . '/subscription/authorization/' . $additional;
                $api = 'MANDATE';
                break;
            case 'WITHDRAWAL':
                $base_url = config('vandar.api_base_url', 'https://api.vandar.io/');
                $additional = 'business/' . config('vandar.business_slug') . '/subscription/withdrawal/' . $additional;
                break;
            default:
                $base_url = config('vandar.api_base_url', 'https://api.vandar.io/');
        }

        if(! $append_version){
            return $base_url . $additional;
        }

        return $base_url . 'v' . ($version_number ?? self::API_VERSIONS[$api]) . '/' . $additional;
    }
}
