<?php

namespace App\Http\Requests\Lecture;

use Illuminate\Foundation\Http\FormRequest;

class StudentActivitySupervisorRequest extends FormRequest
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
            'employee_id' => 'required',
            'number' => 'required',
            'activity_category_id' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'employee_id' => 'lecturer',
            'activity_category_id' => 'activity category'
        ];
    }
}
