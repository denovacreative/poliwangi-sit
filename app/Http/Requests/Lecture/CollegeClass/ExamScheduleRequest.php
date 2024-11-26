<?php

namespace App\Http\Requests\Lecture\CollegeClass;

use Illuminate\Foundation\Http\FormRequest;

class ExamScheduleRequest extends FormRequest
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
            'meeting_type' => 'required',
            'room' => 'required_if:type,offline',
            'type' => 'required|in:online,offline',
            'date' => 'required|date',
            'time_start' => 'required',
            'time_end' => 'required'
        ];
    }
}
