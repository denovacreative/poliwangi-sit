<?php

namespace App\Http\Requests\Lecture\Graduation;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGraduationRequest extends FormRequest
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
            'graduation_date' => 'required|date',
            'student_status_id' => 'required',
            'academic_period_id' => 'required',
            'judiciary_date' => 'required|date',
            'judiciary_number' => 'required',
            'certificate_number' => 'required',
        ];
    }
}
