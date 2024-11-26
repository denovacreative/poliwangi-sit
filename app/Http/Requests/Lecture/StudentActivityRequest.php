<?php

namespace App\Http\Requests\Lecture;

use Illuminate\Foundation\Http\FormRequest;

class StudentActivityRequest extends FormRequest
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
            'study_program' => 'required',
            'semester' => 'required',
            'activity_category' => 'required',
            'title' => 'required',
            'type' => 'in:0,1',
            'start_date' => 'date',
            'end_date' => 'date',
            'decree_date' => 'date',
            'mbkm' => 'required'
        ];
    }
}
