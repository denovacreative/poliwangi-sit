<?php

namespace App\Http\Requests\Lecture;

use Illuminate\Foundation\Http\FormRequest;

class ThesesRequest extends FormRequest
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
            'academic_period' => 'required',
            'student' => 'required',
            'filing_date' => 'required|date',
            'start_date' => 'required|date',
            'finish_date' => 'required|date',
            'topic' => 'required',
            'title' => 'required',
            'abstract' => 'required',
            'decree_number' => 'required',
            'decree_date' => 'required|date',
            'thesis_type' => 'required',
            'employee_1' => 'required',
            'employee_2' => 'required'
        ];
    }
}
