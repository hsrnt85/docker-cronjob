<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest {
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
			'password' => 'required|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!@#$%^&*_-]).*$/',
			'confirm_password' => 'required|same:password|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!@#$%^&*_-]).*$/'
		];
	}

	/**
	 * Get the error messages for the defined validation rules.
	 *
	 * @return array
	 */
	public function messages() {
		return [
			'password.required' => 'Sila Masukkan Kata laluan',
            'password.min' => 'Kata laluan hendaklah sekurang-kurangnya mempunyai minimum 6 aksara',
            'password.regex' => 'Kata laluan hendaklah terdiri daripada gabungan huruf (kecil dan besar), nombor dan simbol [a-z,A-Z,0-9,! @ # $ % ^ & * _ -]',
			'confirm_password.required' => 'Sila Masukkan Pengesahan Kata laluan',
            'confirm_password.same'     => 'Pengesahan Kata laluan hendaklah sama dengan Kata laluan',
            'confirm_password.min' => 'Kata laluan hendaklah sekurang-kurangnya mempunyai minimum 8 aksara',
            'confirm_password.regex' => 'Kata laluan hendaklah terdiri daripada gabungan huruf (kecil dan besar), nombor dan simbol [a-z,A-Z,0-9,! @ # $ % ^ & * _ -]',
		];
	}
}
