<?php

namespace Vandar\VandarCashier\Controllers;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
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
    public function pay(array $params = null, $morphs = null)
    {
        $params['callback_url'] = $params['callback_url'] ?? ($_ENV['VANDAR_CALLBACK_URL']);
        $params['api_key'] = $_ENV['VANDAR_API_KEY'];

        $response = $this->request('post', $this->IPG_URL('send'), false, $params);

        # compatible morphs with db structure
        foreach ($morphs as $key => $value) {
            $morphs["vandar_$key"] = $morphs[$key];
            unset($morphs[$key]);
        }

        # Add {payment_token} into $params
        $params['token'] = $response->json()['token'];
        $params = array_merge($params, $morphs);


        VandarPayment::create($params);


        return Redirect::away(self::IPG_REDIRECT_URL . $response->json()['token']);
    }



    /**
     * Verify the all transaction by sending {TOKEN & API_KEY} 
     *
     * @return bool 1:SUCCEED
     */
    public function verifyTransaction($payment_token)
    {
        $params = ['api_key' => $_ENV['VANDAR_API_KEY'], 'token' => $payment_token];

        $response = $this->request('post', $this->IPG_URL('verify'), false, $params);

        if ($response->status() != 200) {
            VandarPayment::where('token', $payment_token)
                ->update([
                    'errors' => json_encode($response->json()['errors']),
                    'status' => 'FAILED'
                ]);
            return $response->json()['errors'];
        }


        # prepare response for making compatible with DB
        $response = $this->prepareResponseFormat($response->json());

        $response['status'] = 'SUCCEED';

        VandarPayment::where('token', $payment_token)
            ->update($response);

        return $response;
    }



    /**
     * Make proper IPG Url for sending requests
     *
     * @param string|null $param
     * 
     * @return string 
     */
    private function IPG_URL(string $url_param)
    {
        return self::IPG_BASE_URL . $url_param;
    }




    private function prepareResponseFormat($params)
    {
        $keys = array_keys($params);
        foreach ($keys as $key) {
            $keys[array_search($key, $keys)] = Str::snake($key);
            $params = array_combine($keys, $params);
        }

        if (array_key_exists('mobile', $params)) {
            $params['mobile_number'] = $params['mobile'];
            unset($params['mobile']);
        }

        return $params;
    }
}
