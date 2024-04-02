<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuarterApplicationPostRequest extends FormRequest
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
        $rules = [
            'quarters_category' => 'required',          
		];

        return $rules;
    }

    public function messages() {
		$messages =  [
			'quarters_category.required' => '1. Sila pilih kategori kuarters',
		];

        return $messages;
	}
}
