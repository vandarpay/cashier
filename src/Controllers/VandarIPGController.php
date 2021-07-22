<?php

namespace Vandar\VandarCashier\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Vandar\VandarCashier\Models\VandarPayment;

class VandarIPGController extends Controller
{
    use \Vandar\VandarCashier\Utilities\Request;
    

    const IPG_BASE_URL = "https://ipg.vandar.io/api/v3/";
    const IPG_REDIRECT_URL = "https://ipg.vandar.io/v3/";


    /**
     * Send payment parameters to get Payment Token
     * 
     * @param array $params
     * @param array $morphs
     */
    public static function pay(array $params = null, $morphs = null)
    {
        $params['callback_url'] = $params['callback_url'] ?? ($_ENV['VANDAR_CALLBACK_URL']);
        $params['api_key'] = $_ENV['VANDAR_API_KEY'];
        
        $response = self::request('post', self::IPG_URL('send'), false, $params);

        if ($response->status() != 200)
            dd($response->object()->errors);


        # compatible morphs with db structure
        foreach ($morphs as $key => $value) {
            $morphs["vandar_$key"] = $morphs[$key];
            unset($morphs[$key]);
        }

        # Add {payment_token} into $params
        $params['token'] = $response->object()->token;
        $params = array_merge($params, $morphs);


        VandarPayment::create($params);


        // return redirect()->away(self::IPG_REDIRECT_URL . $response->object()->token);
        // return redirect(self::IPG_REDIRECT_URL . $response->object()->token);
        dd(self::IPG_REDIRECT_URL . $response->object()->token);
    }



    /**
     * Verify the all transaction by sending {TOKEN & API_KEY} 
     *
     * @return bool 1:SUCCEED
     */
    public static function verifyTransaction($payment_token)
    {
        $params = ['api_key' => $_ENV['VANDAR_API_KEY'], 'token' => $payment_token];

        $response = self::request('post', self::IPG_URL('verify'), false, $params);

        if ($response->status() != 200) {
            VandarPayment::where('token', $payment_token)
                ->update([
                    'errors' => json_encode($response->object()->errors),
                    'status' => 'FAILED'
                ]);
            dd($response->object()->errors);
        }


        # prepare response for making compatible with DB
        $response = self::prepareResponseFormat($response->json());

        $response['status'] = 'SUCCEED';

        VandarPayment::where('token', $payment_token)
            ->update($response);

        # return $response;
        dd($response);
    }



    /**
     * Make proper IPG Url for sending requests
     *
     * @param string|null $param
     * 
     * @return string 
     */
    private static function IPG_URL(string $url_param)
    {
        return self::IPG_BASE_URL . $url_param;
    }




    private static function prepareResponseFormat($params)
    {
        $keys = array_keys($params);
        foreach ($keys as $key) {
            $keys[array_search($key, $keys)] = Str::of($key)->snake();
            $params = array_combine($keys, $params);
        }

        if (array_key_exists('mobile', $params)) {
            $params['mobile_number'] = $params['mobile'];
            unset($params['mobile']);
        }

        return $params;
    }
}
