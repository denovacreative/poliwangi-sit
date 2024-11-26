<?php

namespace App\Http\Requests\Lecture\CollegeClass;

use Illuminate\Foundation\Http\FormRequest;

class WeeklyScheduleRequest extends FormRequest
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
            'day' => 'required',
            'meeting_type' => 'required',
            'time_start' => 'required',
            'time_end' => 'required',
            'learning_method' => 'required'
        ];
    }
}
