<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SelectionCriteriaPostRequest extends FormRequest
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
            'category' => 'required',
            'criteria' => 'required',
            'statement' => 'required',
            'marks' => 'required'
		];
    }

    public function messages() {
		return [
			'category.required' => 'Sila pilih kategori',
            'criteria.required' => 'Sila pilih kriteria',
			'statement.required' => 'Sila masukkan kenyataan pemarkahan',
            'marks.required' => 'Sila masukkan markah'
		];
	}
}
