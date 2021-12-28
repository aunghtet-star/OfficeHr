<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployee extends FormRequest
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
        $id = $this->route('employee');
        return [
            'name' => 'required',
            'email' => 'required | email|unique:users,email,'.$id,
            'phone' => 'required | min:9 | max:11| unique:users,phone,'.$id,
            'employee_id' => 'required | unique:users,employee_id,'.$id,
            'department_id' => 'required',
            'gender' => 'required',
            'birthday' => 'required',
            'date_of_join' => 'required',
            'is_present' => 'required',
            'passcode' => 'required|min:6|max:6|unique:users,passcode,'.$id,
            'nrc_number' => 'required',

        ];
    }
}
