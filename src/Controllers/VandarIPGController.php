<?php

namespace Vandar\Cashier\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Vandar\Cashier\Models\Payment;
use Vandar\Cashier\RequestsValidation\IPGRequestValidation;
use Vandar\Cashier\RequestsValidation\MorphsRequestValidation;
use Vandar\Cashier\Utilities\ParamsFormatConvertor;

class VandarIPGController extends Controller
{
    use \Vandar\Cashier\Utilities\Request;


    /**
     * Send payment parameters to get Payment Token
     * 
     * @param array $params
     * @param array|null $morphs
     */
    public function pay(array $params, array $morphs = [])
    {
        $params['callback_url'] = $params['callback_url'] ?? config('vandar.callback_url');
        $params['api_key'] = config('vandar.api_key');

        
        # Request Validation 
        $ipg_request = new IPGRequestValidation($params);
        $ipg_request->validate($ipg_request->rules());

        # Request Validation
        $morphs_request = new MorphsRequestValidation($morphs);
        $morphs_request->validate($morphs_request->rules());

        $params = ParamsFormatConvertor::caseFormat($params, 'camel', ['factor_number']);
        
        $response = $this->request('post', $this->IPG_URL('send'), false, $params);


        # Create {morphs} compatibility with db structure
        foreach ($morphs as $key => $value) {
            $morphs["vandar_$key"] = $morphs[$key];
            unset($morphs[$key]);
        }

        # Add {payment_token} into $params
        $params['token'] = $response->json()['token'];
        $params = array_merge($params, $morphs);


        Payment::create($params);


        return Redirect::away($this->REDIRECT_URL($response->json()['token']));
    }




    /**
     * Verify the all transaction by sending {TOKEN & API_KEY} 
     *
     * @return array 
     */
    public function verifyTransaction(string $payment_token): array
    {
        $params = ['api_key' => config('vandar.api_key'), 'token' => $payment_token];

        $response = $this->request('post', $this->IPG_URL('verify'), false, $params);

        if ($response->status() != 200) {
            Payment::where('token', $payment_token)
                ->update([
                    'errors' => json_encode($response->json()['errors']),
                    'status' => 'FAILED'
                ]);
            return $response->json();
        }


        # prepare response for making compatible with DB
        $response = ParamsFormatConvertor::caseFormat($response->json(), 'snake');
        $response = ParamsFormatConvertor::mobileFormat($response);

        $response['status'] = 'SUCCEED';

        Payment::where('token', $payment_token)
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
    private function IPG_URL(string $url_param): string
    {
        return config('vandar.ipg_base_url') . 'api/v3/' . $url_param;
    }


    /**
     * Make proper Redirect Url for payment page
     *
     * @param string $token
     * 
     * @return string
     */
    private function REDIRECT_URL($token): string
    {
        return config('vandar.ipg_base_url') . "v3/$token";
    }
}
