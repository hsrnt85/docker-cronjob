<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserPolicyPostRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255']
		];
    }

    public function messages() {
		return [
            'name.required' => 'Sila Masukkan Nama Peranan'
		];
	}
}
