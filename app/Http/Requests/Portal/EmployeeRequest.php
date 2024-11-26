<?php

namespace App\Http\Requests\Portal;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
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
            'nip' => 'numeric|nullable',
            'nidn' => 'numeric|nullable',
            'nidk' => 'numeric|nullable',
            'name' => 'required',
            'gender' => 'required|in:L,P',
            'phone_number' => 'required',
            'personal_email' => 'required',
            'campus_email' => 'required',
            'postal_code' => 'numeric|nullable',
            'marital_status' => 'required|in:L,M,D,J',
            'photo_picture' => ($this->routeName === 'portal.employee.create' ? 'required' : 'nullable') . '|mimes:png,jpg,jpeg,png',
            'employee_status_id' => 'required',
            'employee_type_id' => 'required',
            'scientific_field_id' => 'required',
            'religion_id' => 'required',
            'country_id' => 'required'
        ];
    }
}
