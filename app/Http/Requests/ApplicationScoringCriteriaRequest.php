<?php

namespace App\Http\Requests;


use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ApplicationScoringCriteriaRequest extends FormRequest
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
            'description' => 'required',
            'execution_date' => ['required',
                Rule::unique('scoring_scheme')->where(function ($query){
                    $query->where('data_status', 1);
                    if(isset($this->scoring_scheme_id)){
                        $query->where('id', '<>', $this->scoring_scheme_id);
                    }
                    return $query;
                })
            ],
            'criteria_name.*' => 'required',
            'range_from.*.*' => 'required',
            'mark.*.*' => 'required',
		];
    }

    public function messages() {
		return [
			'description.required' => setMessage('description.required'),
            'execution_date.required' => setMessage('execution_date.required'),
            'execution_date.unique' =>  setMessage('execution_date.unique'),
            'criteria_name.*.required' => ' ',
            'range_from.*.*.required' => ' ',
            'mark.*.*.required' => ' ',
		];
	}
}
