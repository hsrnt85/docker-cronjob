<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'new_ic' => ['required', 'digits:12','min:12','max:12'],
			'email' => ['required', 'string', 'email'],
		];
	}

	/**
	 * Get the error messages for the defined validation rules.
	 *
	 * @return array
	 */
	public function messages() {
		return [
			'new_ic.required' => 'Sila masukkan No. Kad Pengenalan (Baru)',
			'new_ic.digits'  => 'Terdiri daripada 12 nombor tanpa (-)',
			'new_ic.min'  => 'Terdiri daripada 12 nombor tanpa (-)',
			'new_ic.max'  => 'Terdiri daripada 12 nombor tanpa (-)',
			'email.required' => 'Sila Masukkan Emel',
            'email.email' => 'Sila masukkan format yang betul. (contoh: pengguna@yahoo.com)',
		];
	}
}