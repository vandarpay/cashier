<?php

namespace Vandar\Cashier\RequestsValidation;

use Illuminate\Foundation\Http\FormRequest;


class MorphsRequestValidation extends FormRequest
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
            'payable_type' => 'nullable|string',
            'payable_id' => 'nullable|numeric',
            'paymentable_type' => 'nullable|string',
            'paymentable_id' => 'nullable|numeric',
        ];
    }
}
