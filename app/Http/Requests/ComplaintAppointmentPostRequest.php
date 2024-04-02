<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComplaintAppointmentPostRequest extends FormRequest
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
            'appointment_date' => 'required',
            'appointment_time' => 'required'
		];
    }

    public function messages() {
		return [
			'appointment_date.required' => 'Sila masukkan tarikh temujanji',
            'appointment_time.required' => 'Sila masukkan masa temujanji',
		];
	}
}