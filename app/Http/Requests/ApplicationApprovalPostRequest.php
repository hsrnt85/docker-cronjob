<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationApprovalPostRequest extends FormRequest
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
        $rules = [
          
        ];
        // $rules = [
        //     'application_status' => 'required'
        // ];
        // if(isset($this->request->all()['application_status'])){

        //     if($this->request->all()['application_status']==6)//tidak lulus
        //     {
        //         $rules = [
        //             'remarks' => 'required'
        //         ];
        //     }
        // }

        return $rules;
    }

    public function messages() {
        $messages = [
           
        ];

		// $messages = [
        //     'application_status.required' => setMessage('application_status.required'),
        // ];

        // if(isset($this->request->all()['application_status'])){
        //     if($this->request->all()['application_status']==6)//tidak lulus
        //     {
        //         $messages = [
        //             'remarks.required' => setMessage('remarks.required'),
        //         ];
        //     }
        // }

        return $messages;
	}
}
