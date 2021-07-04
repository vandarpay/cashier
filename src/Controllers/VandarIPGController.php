<?php

namespace Vandar\VandarCashier\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Vandar\VandarCashier\Models\VandarPayment;

class VandarIPGController extends Controller
{
    const IPG_BASE_URL = "https://ipg.vandar.io";
    public static $data;
    private static $payment_token;


    /**
     * Retrieve {payment token} by sending payment parameters
     * Add initial data in the DB if response_status = 1
     * redirect user to payment page
     * 
     * @param array $params
     * 
     * @return  redirect  Payment Page
     */
    public static function pay(array $params)
    {
        $response = Http::asForm()->post(self::IPG_BASE_URL . '/api/v3/send', [
            'api_key' => $_ENV['VANDAR_API_KEY'],
            'amount' => $params['amount'],
            'callback_url' => $params['callback_url'] ?? ($_ENV['VANDAR_CALLBACK_URL']),
            'mobile_number' => $params['mobile_number'] ?? NULL,
            'factorNumber' => $params['factorNumber'] ?? NULL,
            'description' => $params['description'] ?? NULL,
            'valid_card_number' => $params['valid_card_number'] ?? NULL,
        ]);

        if (!$response['status'])
            dd($response['errors']);


        self::$payment_token = $response['token'];

        $token_status = ['token' => self::$payment_token, 'status' => 0];
        self::$data = array_merge($params, $token_status);


        VandarPayment::create(self::$data);


        # TODO => check Redirection (check "return")
        return redirect(self::IPG_BASE_URL . '/v3/' . self::$payment_token);
    }



    /**
     * Retrieve Transaction data by sending {TOKEN & API_KEY} 
     * Save them into Database
     *
     * @return method verifyTransaction()
     */
    public static function addTransactionData()
    {
        $response = Http::asForm()->post(self::IPG_BASE_URL . '/api/ipg/2step/transaction', [
            'api_key' => $_ENV['VANDAR_API_KEY'],
            'token' => self::$payment_token,
        ]);

        $response = (array)json_decode($response);
        self::$data = array_merge(self::$data, $response);

        VandarPayment::where('token', self::$payment_token)
            ->update(self::$data);


        return self::verifyTransaction();
    }



    /**
     * Verify the all transaction by sending {TOKEN & API_KEY} 
     *
     * @return bool 1:SUCCEED
     */
    public static function verifyTransaction()
    {
        $response = Http::asForm()->post(self::IPG_BASE_URL . '/api/v3/verify', [
            'api_key' => $_ENV['VANDAR_API_KEY'],
            'token' => self::$payment_token,
        ]);


        # Add response parameters into DB
        $response = (array)json_decode($response);
        self::$data = array_merge(self::$data, $response);

        VandarPayment::where('token', self::$payment_token)
            ->update(self::$data);

        return 1;
    }


    /**
     * Check the payment status at the {CallBack Page}
     *
     * @return string show message if payment is not successfull
     * @return method addTransactionData()
     */
    public static function verifyPayment()
    {
        $payment_status = (\Request::query())['payment_status'];
        # TODO => update payment status in database
        if ($payment_status != 'OK') {
            echo "پرداخت موفقیت آمیز نبود";
            return;
        }

        return self::addTransactionData();
    }
}
