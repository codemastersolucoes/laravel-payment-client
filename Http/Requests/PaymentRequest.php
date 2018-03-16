<?php

namespace Payments\Client\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            'payments' => 'bail|required|array',
            'payments.*.id' => 'bail|required|string',
            'payments.*.status' => 'bail|required|string'
        ];
    }
}