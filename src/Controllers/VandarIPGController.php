<?php

namespace Vandar\VandarCashier\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Vandar\VandarCashier\Models\VandarPayment;
use Vandar\VandarCashier\VandarIPG;

class VandarIPGController extends Controller
{
    const IPG_BASE_URL = "https://ipg.vandar.io";
    public static $data;


    /**
     * Retrieve {payment token} by sending payment parameters
     * Add initial data in the DB if response_status = 1
     * redirect user to payment page
     * 
     * @param array $params
     * 
     * @return  redirect  Payment Page
     */
    public static function pay(array $params = null, $payable_type = null, $payable_id = null, $paymentable_type = null, $paymentable_id = null)
    {
        $response = Http::asForm()->post(self::IPG_BASE_URL . '/api/v3/send', [
            'api_key' => $_ENV['VANDAR_API_KEY'],
            'amount' => $params['amount'],
            'callback_url' => $params['callback_url'] ?? ($_ENV['VANDAR_CALLBACK_URL']),
            'mobile_number' => $params['mobile_number'] ?? NULL,
            'factorNumber' => $params['factor_number'] ?? NULL,
            'description' => $params['description'] ?? NULL,
            'valid_card_number' => $params['valid_card_number'] ?? NULL,
        ]);

        if (!$response['status'])
            dd($response['errors']);

        $morphs = [
            'vandar_payable_type' => $payable_type,
            'vandar_payable_id' => $payable_id,
            'vandar_paymentable_type' => $paymentable_type,
            'vandar_paymentable_id' => $paymentable_id
        ];

        $payment_token = $response['token'];
        $params['token'] = $payment_token;
        $params = array_merge($params, $morphs);

        // dd($params);
        VandarPayment::create($params);

        # TODO => check Redirection (check "return")
        dd(self::IPG_BASE_URL . '/v3/' . $payment_token);
        // return redirect()->away(self::IPG_BASE_URL . '/v3/' . $payment_token);
        // return redirect(self::IPG_BASE_URL . '/v3/' . $payment_token);
    }



    /**
     * Verify the all transaction by sending {TOKEN & API_KEY} 
     *
     * @return bool 1:SUCCEED
     */
    public static function verifyTransaction($payment_token)
    {
        $response = Http::asForm()->post(self::IPG_BASE_URL . '/api/v3/verify', [
            'api_key' => $_ENV['VANDAR_API_KEY'],
            'token' => $payment_token,
        ]);

        if (!$response['status']) {
            VandarPayment::where('token', $payment_token)
                ->update([
                    'errors' => json_encode($response['errors']),
                    'status' => 'FAILED'
                ]);

            dd($response['errors']);
        }

        $response = (array)json_decode($response);

        # prepare response to compatible with DB
        $response = self::prepareStep4($response);

        $response['status'] = 'SUCCEED';

        VandarPayment::where('token', $payment_token)
            ->update($response);

        # return $response;
        dd($response);
    }


    /**
     * Check the payment status at the {CallBack Page}
     *
     * @return method verifyTransaction()
     */
    public static function verifyPayment()
    {
        $response = (\Request::query());
        # TODO => update payment status in database
        if ($response['payment_status'] != 'OK') {

            VandarPayment::where('token', $response['token'])
                ->update([
                    'errors' => json_encode('failed payment'),
                    'status' => 'FAILED'
                ]);


            echo "فرایند پرداخت با خطا مواجه شد <br> لطفا مجدداً تلاش کنید";

            return;
        }

        return self::verifyTransaction($response['token']);
    }




    private static function prepareStep4($array)
    {
        // realAmount / transId / factorNumber / mobile / cardNumber / paymentDate

        $array['trans_id'] = $array['transId'];
        unset($array['transId']);

        $array['factor_number'] = $array['factorNumber'];
        unset($array['factorNumber']);

        $array['mobile_number'] = $array['mobile'];
        unset($array['mobile']);

        $array['card_number'] = $array['cardNumber'];
        unset($array['cardNumber']);

        $array['payment_date'] = $array['paymentDate'];
        unset($array['paymentDate']);

        $array['real_amount'] = $array['realAmount'];
        unset($array['realAmount']);

        return $array;
    }
}
