<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class QuartersClassRequest extends FormRequest
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

    public function rules(Request $request)
    {
        $this->class_name;
        //RULES
        return [
            'class_name' => ['required',
                Rule::unique('quarters_class')->where(function ($query){
                    $query->where('class_name', $this->class_name)->where('data_status', 1);
                    if(isset($this->id)){
                        $query->where('id', '<>', $this->id);
                    }
                    return $query;
                })
            ],
            'p_grade_id.*' => 'required',
            'services_type_id.*' => 'required',
            'market_rental_amount.*' => 'required|string|max:7',
            'rental_fee.*' => 'required|string|max:7',
        ];

    }

    public function messages()
    {

		return [
            'class_name.required' => setMessage('class_name.required'),
            'class_name.unique' => setMessage('class_name.unique'),
            'p_grade_id.*.required' => ' ',
            'services_type_id.*.required' => ' ',
            'market_rental_amount.*.required' => ' ',
            'rental_fee.*.required' => ' ',
		];

	}

}
