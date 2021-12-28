<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendance extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required',
            'date' => 'required',
            'check_in_time' => 'required',
            'check_out_time' => 'required',
        ];

    }
    public function messages(){
        return [
            'user_id.required' => 'The employee field is required'
        ];
    }
}
