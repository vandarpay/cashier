<?php

namespace Vandar\VandarCashier\RequestsValidation;

use Illuminate\Foundation\Http\FormRequest;


class ListRequestValidation extends FormRequest
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
            'business' => 'nullable|string',
            'page' => 'nullable|numeric|gte:1',
            'per_page' => 'nullable|numeric|gte:1'
        ];
    }
}
