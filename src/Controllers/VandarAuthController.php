<?php

namespace Vandar\Cashier\Controllers;

use Illuminate\Routing\Controller;
use Vandar\Cashier\Models\AuthList;
use Vandar\Cashier\RequestsValidation\AuthRequestValidation;

class VandarAuthController extends Controller
{
    // TODO check overriding methods with Request::request()
    use \Vandar\Cashier\Utilities\Request;


    /**
     * Get the access token for accessing account
     *
     * @return string
     */
    public function token(): string
    {
        if (!(AuthList::count()))
            return ($this->login()['access_token']);

        $authData = AuthList::get()->last();

        if (!$this->isTokenValid($authData['expires_in']))
            return $this->refreshToken($authData['refresh_token'])['access_token'];

        return $authData['access_token'];
    }



    /**
     * Login into Vandar account
     *
     * @return array 
     */
    protected function login(): array
    {
        $params = ['mobile' => config('vandar.mobile'), 'password' => config('vandar.password')];

        # Validate Login Request
        $request = new AuthRequestValidation($params);
        $request->validate($request->rules());


        $response = $this->request('post', $this->LOGIN_URL('login'), false, $params);

        $this->addAuthData($response->json());

        return $response->json();
    }




    /**
     * Refresh Current Token by refresh_token parameter
     *
     * @param string $refresh_token
     * 
     * @return array
     */
    protected function refreshToken(string $refresh_token = null): array
    {
        $refresh_token = $refresh_token ?? (AuthList::get('refresh_token')->last())->refresh_token;

        $params = ['refreshtoken' => $refresh_token];
        $response = $this->request('post', $this->LOGIN_URL('refreshtoken'), false, $params);

        $this->addAuthData($response->json());

        return $response->json();
    }




    /**
     * Check the current token validation
     *
     * @param int $expirationTime
     * 
     * @return bool
     */
    protected function isTokenValid(int $expirationTime = null): bool
    {
        ($expirationTime) ?? $expirationTime = AuthList::get('expires_in')->last();

        return (time() < $expirationTime);
    }



    /**
     * Add new authentication data into database
     *
     * @param array $response
     */
    private function addAuthData(array $response)
    {
        $auth_id = (AuthList::get('id')->last())['id'] ?? 1;

        $response['expires_in'] += time();

        AuthList::updateOrCreate(['id' => $auth_id], $response);
    }




    /**
     * Login URL
     *
     * @param string|null $param
     * 
     * @return string 
     */
    private function LOGIN_URL(string $param = null)
    {
        return config('vandar.api_base_url') . 'v3/' . $param;
    }
}
