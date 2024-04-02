<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlacklistPenaltyPostRequest extends FormRequest
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
        $rules = [];

        $rules = [
            'tid' => 'required',
            'penalty_date' => 'required',
            'reason' => 'required',
            'meeting_remarks' => 'required',
        ];

        // Edit page
        if (null !== $this->request->get("id")) {
            $rules = [
                // 'penalty_date' => 'required',
                // 'rate' => 'required',
                'reason' => 'required'
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'tid.required' => setMessage('tid.required'),
            'penalty_date.required' => setMessage('penalty_date.required'),
            // 'description.required' => setMessage('description.required'),
            'reason.required' => setMessage('reason.required'),
            'rate.required' => setMessage('rate.required'),
            'meeting_remarks.required' => setMessage('meeting_remarks.required'),
        ];
    }
}
