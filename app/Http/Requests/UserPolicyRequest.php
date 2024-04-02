<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserPolicyRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [

            'name' => ['required', 'max:255',
                Rule::unique('roles')->where(function ($query){
                    $query->where('data_status', 1);
                    if(isset($this->id)){
                        $query->where('id', '<>', $this->id);
                    }
                    return $query;
                })
            ],
		];
    }

    public function messages() {
		return [
            'name.required' => setMessage('name.required'),
            'name.unique' =>  setMessage('name.unique'),
		];
	}
}
