<?php

namespace Vandar\VandarCashier\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Vandar\VandarCashier\VandarAuth;
use Illuminate\Support\Facades\Http;
use Vandar\VandarCashier\Models\VandarRefund;

class VandarRefundController extends Controller
{

    /**
     *Refund successfull payment to card number
     *
     * @param array $params
     * 
     * @return array $response->data
     */
    public static function refund(array $params)
    {
        $params['business_name'] = $params['business_name'] ?? $_ENV['VANDAR_BUSINESS_NAME'];
        $token = VandarAuth::token();

        self::REFUND_URL($params);

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->post(self::REFUND_URL($params), [
            'payment_number' => $params['payment_number'] ?? NULL,
            'description' => $params['description'] ?? NULL
        ]);

        $response = json_decode($response);

        # PROBLEM=> Refund from another Business-> return MESSAGE instead of STATUS(0, 1)
        if (!(isset($response->status) and $response->status)) {
            # PROBLEM=> Unauthenticated(error) - has refunded(errors)
            # return $response->error;
            dd($response->error ?? $response->errors);
        }

        # prepare data to compatible with database
        $response = self::prepareData($response, $params);

        VandarRefund::create($response);

        # return $response;
        dd($response);
    }




    /**
     * Prepare Business Url for sending request
     *
     * @param array $params
     * 
     * @return string $url
     */
    public static function REFUND_URL(array $params)
    {
        $url = "https://api.vandar.io/v2/business/{$params['business_name']}/transaction/{$params['trans_id']}/refund";
        return $url;
    }




    private static function prepareData($response, $params)
    {
        $data = array_merge((array)$response->data->results, $params);
        $data['message'] = $response->data->message;

        $data['refund_id'] = $data['id'];
        unset($data['id']);

        $data['refund_date_jalali'] = $data['refund_date'];
        unset($data['refund_date']);

        $data['created_at_jalali'] = $data['created_at'];
        unset($data['created_at']);

        return $data;
    }
}
