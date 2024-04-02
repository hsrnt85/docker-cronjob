<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EvaluationMeetingApplicationRequest extends FormRequest
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
            //'status' => 'required'
        ];

        return $rules;
    }

    public function messages() {
		$messages = [
            //'status.required' => "Sila pilih status semakan"
        ];

        return $messages;
	}
}
