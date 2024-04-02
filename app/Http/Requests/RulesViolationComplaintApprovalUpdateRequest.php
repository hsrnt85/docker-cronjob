<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RulesViolationComplaintApprovalUpdateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'remarks' => 'required',
		];
    }

    public function messages() {

		return [
			'remarks.required' => setMessage('remarks.required')
		];
	}
}
