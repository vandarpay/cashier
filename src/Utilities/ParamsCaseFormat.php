<?php

namespace Vandar\VandarCashier\Utilities;

use Illuminate\Support\Str;

class ParamsCaseFormat
{
    /**
     * Convert Data CaseFormat 
     *
     * @param array $params
     * @param string $caseFormat
     * @param array|null $keys
     * @return array $params
     */
    public static function convert($params, $caseFormat, $keys = null)
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


        if (array_key_exists('mobile', $params)) {
            $params['mobile_number'] = $params['mobile'];
            unset($params['mobile']);
        }


        return $params;
    }
}
