<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterPostRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email'],
            'new_ic' => ['required', 'digits:12'],
            'position' => ['required'],
            'position_type' => ['required'],
            'position_grade' => ['required'],
            'organization' => ['required'],
            'services_type' => ['required'],
            'district' => ['required'],
		];
    }

    public function messages() {
		return [
            'name.required' => 'Sila Masukkan Nama',
            'email.required' => 'Sila Masukkan Emel',
            'email.email' => 'Sila masukkan format yang betul. (contoh: pengguna@yahoo.com)',
            'new_ic.required' => 'Sila masukkan No. Kad Pengenalan (Baru)',
            'new_ic.digits'  => 'Terdiri daripada 12 nombor tanpa (-)',
            'position.required' => 'Sila Pilih Jawatan',
            'position_type.required' => 'Sila Pilih Kod Jawatan',
            'position_grade.required' => 'Sila Pilih Gred Jawatan',
            'organization.required' => 'Sila masukkan Nama Organisasi',
            'services_type.required' => 'Sila masukkan Jenis Perkhidmatan',
            'district.required' => 'Sila Pilih Daerah',
		];
	}
}
