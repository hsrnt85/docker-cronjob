<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentMethodRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'payment_method' => 'required|string|max:255',
            'ispeks_payment_code_id' => 'required|exists:ispeks_payment_code,id',
        ];
    }

    public function messages()
    {
        return [
            'payment_method.required' => 'Kaedah bayaran di perlukan'
        ];
    }
}
