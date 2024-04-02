<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PanelMeetingPostRequest extends FormRequest
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
            'bil' => 'required',
            'purpose' => 'required',
            'date' => 'required',
            'time' => 'required',
            'place' => 'required',
            'district' => 'required',
            'panel' => 'required',
            'chairman' => 'required'
		];
    }

    public function messages() {
		return [
			'bil.required' => 'Sila masukkan bil mesyuarat',
            'purpose.required' => 'Sila masukkan tujuan mesyuarat',
			'date.required' => 'Sila pilih tarikh',
            'time.required' => 'Sila pilih masa',
            'place.required' => 'Sila masukkan tempat',
            'district.required' => 'Sila pilih daerah',
            'panel.required' => 'Sila pilih panel',
            'chairman.required' => 'Sila masukkan pengerusi',
		];
	}
}
