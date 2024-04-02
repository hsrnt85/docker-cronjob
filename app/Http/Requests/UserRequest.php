<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            // 'email' => ['required', 'string', 'email'],
            'new_ic' => ['required', 'digits:12',
                Rule::unique('users')->where(function ($query){
                    $query->where('data_status', 1);
                    if(isset($this->id)){
                        $query->where('id', '<>', $this->id);
                    }
                    return $query;
                })
            ],
            // 'position' => ['required'],
            // 'position_type' => ['required'],
            // 'position_grade' => ['required'],
            // 'organization' => ['required'],
            // 'services_type' => ['required'],
            // 'district' => ['required'],
            'roles' => ['required'],
            'system_platform' => ['required'],
		];
    }

    public function messages() {
		return [
            // 'name.required' => setMessage('name.required'),
            // 'email.required' => setMessage('email.required'),
            // 'email.email' => setMessage('email.email'),
            'new_ic.required' => setMessage('new_ic.required'),
            'new_ic.digits'  => setMessage('new_ic.digits'),
            'new_ic.unique' =>  setMessage('new_ic.unique'),
            // 'position.required' => setMessage('position.required'),
            // 'position_type.required' => setMessage('position_type.required'),
            // 'position_grade.required' => setMessage('position_grade.required'),
            // 'organization.required' => setMessage('organization.required'),
            // 'services_type.required' => setMessage('services_type.required'),
            // 'district.required' => setMessage('district.required'),
            'roles.required' => setMessage('roles.required'),
            'system_platform.required' => setMessage('system_platform.required'),
		];
	}
}
