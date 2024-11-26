<?php

namespace App\Http\Requests\Lecture\Administration;

use Illuminate\Foundation\Http\FormRequest;

class GenerateStatusSemesterRequest extends FormRequest
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
            'student' => 'required',
            'semester' => 'required',
            'student_status' => 'required',
            'finances_id' => 'required',
        ];
    }
}
