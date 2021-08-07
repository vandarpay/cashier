<?php

namespace Vandar\VandarCashier\RequestsValidation;

use Illuminate\Foundation\Http\FormRequest;


class IPGRequestValidation extends FormRequest
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
            'api_key' => 'required|string',
            'amount' => 'required|numeric|gte:10000',
            'callback_url' => 'required|string|max:191',
            'mobile_number' => 'nullable|string',
            'factor_number' => 'nullable|string',
            'description' => 'nullable|string|max:255',
            'valid_card_number' => 'nullable|string',
        ];
    }

}
