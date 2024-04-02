<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MeetingRegistrationRequest extends FormRequest
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
            'bil_no' => 'required',
            'purpose' => 'required',
            'date' => 'required',
            'time' => 'required',
            'venue' => 'required',
            'meeting_chairmain_ids' => 'required|array|min:1|max:1',
            'meeting_internal_panel_ids' => 'required|array|min:1',
            'meeting_invitation_panel_ids' => 'required|array|min:1',
		];
    }

    public function messages() {
		return [
			'bil_no.required' => setMessage('bil_no.required'),
            'purpose.required' => setMessage('purpose.required'),
			'date.required' => setMessage('date.required'),
            'time.required' => setMessage('time.required'),
            'venue.required' => setMessage('venue.required'),
            'meeting_chairmain_ids.required' => setMessage('chairmain.required'),
            'meeting_chairmain_ids.array' => setMessage('chairmain.required'),
            'meeting_chairmain_ids.min' => setMessage('chairmain.required'),
            'meeting_chairmain_ids.max' => setMessage('chairmain.required'),
            'meeting_internal_panel_ids.required' => setMessage('internal_panel.required'),
            'meeting_internal_panel_ids.array' => setMessage('internal_panel.required'),
            'meeting_internal_panel_ids.min' => setMessage('internal_panel.required'),
            'meeting_invitation_panel_ids.required' => setMessage('invitation_panel.required'),
            'meeting_invitation_panel_ids.array' => setMessage('invitation_panel.required'),
            'meeting_invitation_panel_ids.min' => setMessage('invitation_panel.required'),
            'application_ids.required' => setMessage('application_ids.required'),
            'application_ids.array' => setMessage('application_ids.required'),
            'application_ids.min' => setMessage('application_ids.required'),
		];
	}
}
