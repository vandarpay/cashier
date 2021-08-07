<?php

namespace Vandar\VandarCashier\Controllers;

use App\Http\Controllers\Controller;
use Vandar\VandarCashier\Models\VandarWithdrawal;
use Vandar\VandarCashier\RequestsValidation\WithdrawalRequestValidation;

class VandarWithdrawalController extends Controller
{
    use \Vandar\VandarCashier\Utilities\Request;


    /**
     * Store new withdrawal
     *
     * @param array $params
     * 
     * @return object $data
     */
    public function store($params)
    {
        $params['notify_url'] = $params['notify_url'] ?? $_ENV['VANDAR_NOTIFY_URL'];

        # Request Validation
        $request = new WithdrawalRequestValidation($params);
        $request->validate($request->rules());


        $response = $this->request('post', $this->WITHDRAWAL_URL('store'), true, $params);


        # prepare data for DB structure
        $db_data = $response->json()['result']['withdrawal'];
        $db_data['withdrawal_id'] = $db_data['id'];
        unset($db_data['id']);

        VandarWithdrawal::create($db_data);


        return $response->json();
    }



    /**
     * Show the list of withdrawals
     *
     * @return array
     */
    public function list(): array
    {
        $response = $this->request('get', $this->WITHDRAWAL_URL(), true);

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
        $response = $this->request('get', $this->WITHDRAWAL_URL($withdrawal_id), true);

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
        $response = $this->request('put', $this->WITHDRAWAL_URL($withdrawal_id), true);

        VandarWithdrawal::where('withdrawal_id', $withdrawal_id)
            ->update(['status' => 'CANCELED']);


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
        return "https://api.vandar.io/v2/business/$_ENV[VANDAR_BUSINESS_NAME]/subscription/withdrawal/$param";
    }
}
