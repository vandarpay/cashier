<?php

namespace Vandar\Cashier\RequestsValidation;

use Illuminate\Foundation\Http\FormRequest;


class WithdrawalRequestValidation extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }



    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'authorization_id' => 'required|string',
            'amount' => 'required|string',
            'withdrawal_date' => 'nullable|string',
            'is_instant' => 'nullable|boolean',
            'notify_url' => 'nullable|string',
            'max_retry_count' => 'nullable|numeric|min:1|max:16',
        ];
    }
}
