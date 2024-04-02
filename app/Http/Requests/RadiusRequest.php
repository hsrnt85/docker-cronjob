<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RadiusRequest extends FormRequest
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
            'radius' => 'required',
            'date' => ['required',
                Rule::unique('radius','date_start')->where(function ($query){
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
			'radius.required' =>  setMessage('radius.required'),
            'date.required' => setMessage('date.required'),
            'date.unique' =>  setMessage('date.unique'),
		];
	}
}
