<?php

namespace Vandar\Cashier\Client;

use Illuminate\Support\Str;

class CasingFormatter
{
    /**
     * Convert key naming format to snake or camel case
     *
     * @param string $mode can be 'snake' or 'camel', destination naming format
     * @param array $array
     * @param array|null $only_keys if set, only the given keys will be converted
     * @return array
     */
    public static function convertKeyFormat(string $mode, array $array, array $only_keys = null): array
    {
        $keys = $only_keys ?? array_keys($array);

        foreach ($keys as $key) {
            if (array_key_exists($key, $array)) {

                $new_case_key = Str::$mode($key);

                if ($new_case_key != $key) {
                    $array[$new_case_key] = $array[$key];
                    unset($array[$key]);
                }
            }
        }

        return $array;
    }

    /**
     * Convert all array keys to snake-case naming
     * @param $array
     * @return array
     */
    public static function convertKeysToSnake($array): array
    {
        return self::convertKeyFormat('snake', $array);
    }



    /**
     * Convert Mobile Number Format
     *
     * @param array $array
     * @return array
     */
    public static function mobileKeyFormat($array)
    {

        if (array_key_exists('mobile', $array)) {
            $array['mobile_number'] = $array['mobile'];
            unset($array['mobile']);
        } else if (array_key_exists('mobile_number', $array)) {
            $array['mobile'] = $array['mobile_number'];
            unset($array['mobile_number']);
        }


        return $array;
    }



    /**
     * Convert response error message key format 
     */
    public static function convertFailedResponseFormat($response)
    {
        if (array_key_exists('error', $response)) {
            $response['errors'] = $response['error'];
            unset($response['error']);
        }


        if (array_key_exists('message', $response)) {
            $response['errors'] = $response['message'];
            unset($response['message']);
        }

        return $response;
    }
}
