<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PositionGradeRequest extends FormRequest
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
            'grade_no' => ['required',
                Rule::unique('position_grade')->where(function ($query){
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
			'grade_no.required' =>setMessage('grade_no.required'),
            'grade_no.unique' =>  setMessage('grade_no.unique')
		];
	}
}

