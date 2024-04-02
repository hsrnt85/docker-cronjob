<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PaymentDetailRequest extends FormRequest
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
            'payment_notice_no' => 'required',
            'payment_type_id' => 'required',
            'receipt_no' => 'required',
            'online_payment_refno' => 'required',
            'payment_date' => 'required',
            'total_payment' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'payment_notice_no.required' => 'No notis bayaran di perlukan',
            'payment_type_id.required' => 'Jenis bayaran di perlukan',
            'receipt_no.required' => 'No resit di perlukan',
            'online_payment_refno.required' => 'No transaksi perbankan di perlukan',
            'payment_date.required' => 'Tarikh bayaran di perlukan',
            'total_payment.required' => 'Jumlah bayaran di perlukan',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'status' => true
        ], 422));
    }
}
