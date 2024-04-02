<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SpecialPermissionRequest extends FormRequest
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
            'new_ic' => ['required', 'digits:12']
            // 'name' => 'required',
            // 'email' => 'required',
            // 'position' => 'required',
            // 'position_type' => 'required',
            // 'position_grade' => 'required',
            // 'organization' => 'required',
            // 'services_type' => 'required',
            // 'district' => 'required'
		];
    }

    public function messages() {
		return [
			'new_ic.required' => setMessage('new_ic.required'),
            'new_ic.digits'  => setMessage('new_ic.digits'),
            'new_ic.unique' =>  setMessage('new_ic.unique'),
            // 'name.required' => 'Sila masukkan Nama Pengguna',
            // 'email.required' => 'Sila masukkan Email Pengguna',
            // 'position.required' => 'Sila masukkan Jawatan',
            // 'position_type.required' => 'Sila masukkan Kod Jawatan',
            // 'position_grade.required' => 'Sila masukkan Gred Jawatan',
            // 'organization.required' => 'Sila masukkan Jabatan/Agensi Bertugas',
            // 'services_type.required' => 'Sila masukkan Jenis Perkhidmatan',
            // 'district.required' => 'Sila masukkan Daerah'

		];
	}
}
