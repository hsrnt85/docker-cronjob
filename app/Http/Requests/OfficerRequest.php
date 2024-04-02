<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OfficerRequest extends FormRequest
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
        $rules = [];
        if(isset($this->officer)){
            $rules['officer'] = ['required',
                    Rule::unique('officer','users_id')->where(function ($query){
                        $query->where(['users_id'=> $this->officer,
                                        'district_id'=> districtId(),
                                        'officer_group_id'=>$this->officer_group,
                                        'data_status'=> 1
                        ]);
                        // if($this->request->get("officer_group") == 2){
                        //     $officer_category_id = $this->officer_category != null ? implode(',', $this->officer_category) : "";
                        //     $query->whereRaw('FIND_IN_SET(?, officer_category_id)',[$officer_category_id]);
                        // }
                        if(isset($this->id)){
                            $query->where('id', '<>', $this->id);
                        }

                        return $query;
                    })
                ];
        }
        $rules['officer_group'] = 'required';

        if($this->request->get("officer_group") == 2)
        {
            $rules['officer_category'] = 'required';
        }

        return $rules;

    }

    public function messages()
    {
	
        if(isset($this->officer)){
            $messages = [
                'officer.required' => setMessage('officer.required')
            ];
        }
          
        $messages = [
            'officer.unique' =>  setMessage('officer.unique'),
			'officer_category.required' => setMessage('officer_category.required'),
        ];

        return $messages;
	}
}
