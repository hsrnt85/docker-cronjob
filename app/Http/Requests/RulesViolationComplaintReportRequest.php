<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RulesViolationComplaintReportRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'carian_tarikh_aduan_dari' => ['required'],
            'carian_tarikh_aduan_hingga' => ['required'],
            'carian_id_kategori' => ['required'],
		];
    }

    public function messages() {
		return [
            'carian_tarikh_aduan_dari.required' => 'Sila Masukkan Tarikh Dari',
            'carian_tarikh_aduan_hingga.required' => 'Sila Masukkan Tarikh Hingga',
            'carian_id_kategori.required' => 'Sila Pilih Kategori Kuarters',
		];
	}
}
