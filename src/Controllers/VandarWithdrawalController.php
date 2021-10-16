<?php

namespace Vandar\Cashier\Controllers;

use Illuminate\Routing\Controller;
use Vandar\Cashier\Client\Client;
use Vandar\Cashier\Models\Withdrawal;
use Vandar\Cashier\RequestsValidation\WithdrawalRequestValidation;

class VandarWithdrawalController extends Controller
{

    /**
     * Store new withdrawal
     *
     * @param array $params
     *
     * @return object $data
     */
    public function store(array $params)
    {
        # Request Validation
        $request = new WithdrawalRequestValidation($params);
        $request->validate($request->rules());


        $response = Client::request('post', $this->WITHDRAWAL_URL('store'), $params, true);


        # prepare data for DB structure
        $db_data = $response->json()['result']['withdrawal'];
        $db_data['withdrawal_id'] = $db_data['id'];
        unset($db_data['id']);

        Withdrawal::create($db_data);


        return $response->json();
    }

    /**
     * Prepare Withdrawal Url for sending requests
     *
     * @param string|null $param
     *
     * @return string
     */
    private function WITHDRAWAL_URL(string $param = null): string
    {
        return config('vandar.api_base_url') . "v2/business/" . config('vandar.business_name') . "/subscription/withdrawal/$param";
    }

    /**
     * Show the list of withdrawals
     *
     * @return array
     */
    public function list(): array
    {
        $response = Client::request('get', $this->WITHDRAWAL_URL(), [], true);

        return $response->json();
    }

    /**
     * Show the Deatils of stored withdrawals
     *
     * @param string $withdrawal_id
     *
     * @return array
     */
    public function show(string $withdrawal_id): array
    {
        $response = Client::request('get', $this->WITHDRAWAL_URL($withdrawal_id), [], true);

        return $response->json();
    }

    /**
     * Cancel the stored withdrawals
     *
     * @param string $withdrawal_id
     *
     * @return array
     */
    public function cancel(string $withdrawal_id): array
    {
        $response = Client::request('put', $this->WITHDRAWAL_URL($withdrawal_id), [], true);

        Withdrawal::where('withdrawal_id', $withdrawal_id)
            ->update(['status' => 'CANCELED']);


        return $response->json();
    }
}
