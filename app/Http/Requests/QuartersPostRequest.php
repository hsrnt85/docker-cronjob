<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuartersPostRequest extends FormRequest
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
            'quarters_category' => 'required',
            'address_1' => 'required',
            'total' => 'required' ,
            //'unit_no' => 'required' , //for edit quarters info
		];

        if(isset($this->request->all()['inventory']))
        {
            foreach($this->request->all()['inventory'] as $key => $val) {
                $rules['quantity.' . $key] = 'required';
                //$rules['responsibility.' . $key] = 'required';
            }
        }

        return $rules;
    }

    public function messages() {
		$messages =  [
			'quarters_category.required' => 'Sila pilih Kategori Kuarters (Lokasi)',
			'address_1.required' => 'Sila masukkan alamat 1',
			'total.required' => 'Sila masukkan jumlah',
            //'unit_no.required' => 'Sila masukkan no unit', //for edit quarters info
		];

        foreach($this->request->all()['quantity'] as $key => $val) {
            $messages['quantity.' . $key . '.required'] = 'Sila masukkan kuantiti';
            //$messages['responsibility.' . $key . '.required'] = 'Sila pilih tanggungjawab';
        }

        return $messages;
	}
}
