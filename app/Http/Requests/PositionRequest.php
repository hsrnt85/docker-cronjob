<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Config\CookiesFunction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PositionRequest extends FormRequest
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
            'position_name' => ['required',
                Rule::unique('position')->where(function ($query){
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
            'position_name.required' =>setMessage('position_name.required'),
            'position_name.unique' =>  setMessage('position_name.unique')
		];
	}
}
