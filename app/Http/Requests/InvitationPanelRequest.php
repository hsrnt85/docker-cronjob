<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class InvitationPanelRequest extends FormRequest
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
            // 'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email'],
            'position' => ['required',
                Rule::unique('invitation_panel')->where(function ($query){
                    $query->where('department', $this->department)->where('district_id', districtId())->where('data_status', 1);
                    if(isset($this->id)){
                        $query->where('id', '<>', $this->id);
                    }
                    return $query;
                })
            ],
            'department' => ['required']
		];
    }

    public function messages() {
		return [
            // 'name.required' => setMessage('name.required'),
            'email.required' => setMessage('email.required'),
            'email.email' => setMessage('email.email'),
            'position.required' => setMessage('position.required'),
            'position.unique' =>  setMessage('position.unique'),
            'department.required' => setMessage('department.required'),
		];
	}
}
