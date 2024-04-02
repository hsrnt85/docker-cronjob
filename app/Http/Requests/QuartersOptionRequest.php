<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class QuartersOptionRequest extends FormRequest
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
            'option_no' => ['required',
                Rule::unique('quarters_option_no')->where(function ($query){
                    $query->where('execution_date', convertDateDb($this->execution_date))->where('data_status', 1);
                    if(isset($this->id)){
                        $query->where('id', '<>', $this->id);
                    }
                    return $query;
                })
            ],
            'execution_date' => 'required',
        ];
    }

    public function messages()
     {
		return [
            'option_no.required' => setMessage('option_no.required'),
            'option_no.unique' =>  setMessage('option_no.unique'),
            'execution_date.required' => setMessage('option_no.required'),
		];
	}
}
