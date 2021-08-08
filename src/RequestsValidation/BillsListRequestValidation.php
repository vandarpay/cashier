<?php

namespace Vandar\VandarCashier\RequestsValidation;

use Illuminate\Foundation\Http\FormRequest;


class BillsListRequestValidation extends FormRequest
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
            'form_date' => 'nullable|string',
            'to_date' => 'nullable|string',
            'status_kind' => 'nullable|string',
            'status' => 'nullable|string',
            'channel' => 'nullable|string',
            'form_id' => 'nullable|string',
            'ref_id' => 'nullable|string',
            'tracking_code' => 'nullable|string',
            'id' => 'nullable|string',
            'track_id' => 'nullable|string',
            'q' => 'nullable|string',
            'page' => 'nullable|numeric|gte:1',
            'per_page' => 'nullable|numeric|gte:1'
        ];
    }
}
