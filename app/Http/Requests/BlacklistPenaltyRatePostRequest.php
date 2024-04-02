<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class BlacklistPenaltyRatePostRequest extends FormRequest
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
            'eff_date' => 'required',
            'description' => 'required',
            'range_from.*' => 'required|integer',
            'operator_id.*' => 'required',
            'range_to.*' => 'required_unless:operator_id.*,1',
            'rate.*' => 'required',
        ];
    }

    public function withValidator(Validator $validator)
    {
        // Custom continuous months validation
        $validator->after(function ($validator) {
            $rangeFrom = $this->input('range_from');
            $rangeTo = $this->input('range_to');
            
            $previousRangeTo = 0;

            for ($i = 0; $i < count($rangeFrom); $i++) {
                $currentRangeFrom = intval($rangeFrom[$i]);

                if ($currentRangeFrom - 1 !== $previousRangeTo) {
                    // Add the error message 
                    $validator->errors()->add('range_from.' . $i, setMessage('range-error-msg'));
                    break;
                }
                
                if (isset($rangeTo[$i])) {
                    $previousRangeTo = intval($rangeTo[$i]);
                } 
            }
        });

        $validator->after(function ($validator) {
            $rangeFrom = $this->input('range_from');
            $rangeTo = $this->input('range_to');
            $operatorId = $this->input('operator_id');
            
            for ($i = 0; $i < count($rangeFrom); $i++) {
                // Check if an element exists at the current index in the rangeTo and operatorId arrays
                if (isset($rangeTo[$i]) && isset($operatorId[$i])) {
                    // If operator_id is not equal to 1 and range_to is empty, add an error message
                    if ($operatorId[$i] != 1 && empty($rangeTo[$i])) {
                        $validator->errors()->add('range_to.' . $i, setMessage('month.required'));
                    }

                    if ($operatorId[$i] != 1 && (!empty($rangeTo[$i]) && $rangeTo[$i] < $rangeFrom[$i] )) {
                        $validator->errors()->add('range_to.' . $i, setMessage('month.lower'));
                    }
                }
            }
        });
    }

    public function messages() {
        return [
            'eff_date.required' => setMessage('date_search.required'),
            'description.required' => setMessage('description.required'),
            'range_from.*.required' => setMessage('month.required'),
            'range_to.*.required_unless' => setMessage('month.required'),
            'operator_id.*.required' => setMessage('operator.required'),
            'range_from.*.integer' => setMessage('month.integer'),
            'range_to.*.integer' => setMessage('month.integer'),
            'range_to.*.gt' => 'The :attribute must be greater than the corresponding Range From value.',
            'rate.*.required' => setMessage('rate.required'),
		];
	}
}
