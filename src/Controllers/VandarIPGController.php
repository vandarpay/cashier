<?php

namespace Vandar\VandarCashier\Controllers;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Vandar\VandarCashier\Models\VandarPayment;
use Illuminate\Support\Facades\Validator;
use Vandar\VandarCashier\Utilities\VandarValidationRules;

class VandarIPGController extends Controller
{
    use \Vandar\VandarCashier\Utilities\Request;

    private $ipg_validation_rules;

    const IPG_BASE_URL = 'https://ipg.vandar.io/api/v3/';
    const IPG_REDIRECT_URL = 'https://ipg.vandar.io/v3/';



    /**
     * Set related validation rules
     */
    public function __construct()
    {
        $this->ipg_validation_rules = VandarValidationRules::ipg();
    }




    /**
     * Send payment parameters to get Payment Token
     * 
     * @param array $params
     * @param array|null $morphs
     */
    public function pay(array $params, array $morphs = null)
    {
        $params['callback_url'] = $params['callback_url'] ?? (env('VANDAR_CALLBACK_URL'));
        $params['api_key'] = env('VANDAR_API_KEY');


        # Validate {params} and {morphs} by their rules
        $morphs_validator = Validator::make($morphs, $this->ipg_validation_rules['morphs']);
        $params_validator = Validator::make($params, $this->ipg_validation_rules['pay']);

        # Show {error message} if there is any incompatibility with rules 
        if ($params_validator->fails() or $morphs_validator->fails())
            return $params_validator->errors()->messages() ?? $morphs_validator->errors()->messages();


        $response = $this->request('post', $this->IPG_URL('send'), false, $params);


        # Create {morphs} compatibility with db structure
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
     * @return array 
     */
    public function verifyTransaction(string $payment_token): array
    {
        $params = ['api_key' => env('VANDAR_API_KEY'), 'token' => $payment_token];

        $response = $this->request('post', $this->IPG_URL('verify'), false, $params);

        if ($response->status() != 200) {
            VandarPayment::where('token', $payment_token)
                ->update([
                    'errors' => json_encode($response->json()['errors']),
                    'status' => 'FAILED'
                ]);
            return $response->json();
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
    private function IPG_URL(string $url_param): string
    {
        return self::IPG_BASE_URL . $url_param;
    }




    /**
     * Prepare final response format 
     *
     * @param array $params
     * 
     * @return array $params
     */
    private function prepareResponseFormat(array $params): array
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
