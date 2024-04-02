<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PegawaiPostRequest extends FormRequest
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
            'pegawai' => 'required',
			'district' => 'required',
            'officer_category' => 'required',
            'officer_category.*' => 'numeric',
		];
    }

    public function messages() {
		return [
			'pegawai.required' => 'Sila pilih pegawai',
			'district.required' => 'Sila pilih daerah',
			'officer_category.required' => 'Sila pilih kategori pegawai'
		];
	}
}
