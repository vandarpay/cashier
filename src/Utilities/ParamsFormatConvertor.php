<?php

namespace Vandar\Cashier\Utilities;

use Illuminate\Support\Str;

class ParamsFormatConvertor
{
    /**
     * Convert Data CaseFormat 
     *
     * @param array $params
     * @param string $caseFormat
     * @param array|null $keys
     * @return array $params
     */
    public static function caseFormat(array $params, string $caseFormat, array $keys = null)
    {
        $keys = $keys ?? array_keys($params);
        foreach ($keys as $key) {
            if (array_key_exists($key, $params)) {
                $new_case_key = Str::$caseFormat($key);
                if ($new_case_key != $key) {
                    $params[$new_case_key] = $params[$key];
                    unset($params[$key]);
                }
            }
        }



        return $params;
    }



    /**
     * Convert Mobile Number Format
     *
     * @param array $params
     * 
     * @return array $params
     */
    public static function mobileFormat($params)
    {

        if (array_key_exists('mobile', $params)) {
            $params['mobile_number'] = $params['mobile'];
            unset($params['mobile']);
        } else if (array_key_exists('mobile_number', $params)) {
            $params['mobile'] = $params['mobile_number'];
            unset($params['mobile_number']);
        }


        return $params;
    }
}
