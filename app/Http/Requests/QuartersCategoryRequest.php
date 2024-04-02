<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class QuartersCategoryRequest extends FormRequest
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
            //'name' => ['required', 'string', 'max:255'],
            'name' => ['required',
                Rule::unique('quarters_category')->where(function ($query){
                    $query->where('name', $this->name)->where('district_id', districtId())->where('data_status', 1);
                    if(isset($this->id)){
                        $query->where('id', '<>', $this->id);
                    }
                    return $query;
                })
            ],
            //'district' => ['required'],
            'landedType' => ['required' ],
            'categoryClass' => 'required|array|min:1'
        ];
    }

    public function messages() {
		return [
            'name.required' =>  setMessage('name.required'),
            'name.unique' =>  setMessage('name.unique'),
            //'district.required' =>  setMessage('district.required'),
            'landedType.required' =>  setMessage('landedType.required'),
            'categoryClass.required' =>  setMessage('categoryClass.required'),
            'categoryClass.required' =>  setMessage('categoryClass.required'),
            'categoryClass.array' =>  setMessage('categoryClass.required'),
            'categoryClass.min' =>  setMessage('categoryClass.required'),
		];
	}
}
