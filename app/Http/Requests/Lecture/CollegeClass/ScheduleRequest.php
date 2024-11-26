<?php

namespace App\Http\Requests\Lecture\CollegeClass;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ScheduleRequest extends FormRequest
{
    public function __construct()
    {
        $this->routeName = request()->route()->getName();
    }

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
            'academic_period_id' => 'required',
            'study_program_id' => 'required',
            'course_id' => 'required',
            'lecture_system_id' => 'required',
            'name' => [
                'max:5',
                $this->routeName === 'lecture.college-class.schedule.store' ? 'nullable' : 'required',
            ],
            'capacity' => 'required|integer',
            'date_start' => 'required',
            'date_end' => 'required',
            'number_of_meeting' => 'required|integer',
        ];
    }
}
