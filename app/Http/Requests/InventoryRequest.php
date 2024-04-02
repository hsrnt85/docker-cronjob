<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class InventoryRequest extends FormRequest
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
            'name' => ['required',
                Rule::unique('inventory')->where(function ($query){
                    $query->where('data_status', 1) 
                          ->where('quarters_category_id', ($this->quarters_cat_id));
                    if(isset($this->id)){
                        $query->where('id', '<>', $this->id);
                    }
                    return $query;
                })
            ],
            'price' => ['required'],
        ];
    }

    public function messages() {
		return [
            'name.required' => setMessage('name.required'),
            'name.unique' =>  setMessage('name.unique'),
            'price.required' => setMessage('price.required'),
		];
	}
}
