<?php

namespace Vandar\VandarCashier\RequestsValidation;

use Illuminate\Foundation\Http\FormRequest;


class MandateRequestValidation extends FormRequest
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
            'bank_code' => 'required|string',
            'mobile_number' => 'nullable|string|starts_with:09|size:11',
            'callback_url' => 'required|string',
            'count' => 'required|int',
            'limit' => 'required|int',
            'name' => 'nullable|string',
            'email' => 'nullable|string|email',
            'expiration_date' => 'required|date_format:Y-m-d',
            'wage_type' => 'nullable|',
        ];
    }
}
