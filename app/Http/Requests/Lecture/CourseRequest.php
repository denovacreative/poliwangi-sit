<?php

namespace App\Http\Requests\Lecture;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
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
            'code' => ['required', $this->routeName === 'lecture.courses.store' ? 'unique:courses,code' : Rule::unique('courses', 'code')->ignore($this->course)],
            'name' => 'required',
            'name_en' => 'required',
            'alias' => 'required',
            'credit_meeting' => 'numeric|required',
            'credit_practicum' => 'numeric|required',
            'credit_practice' => 'numeric|required',
            'credit_simulation' => 'numeric|required',
            'course_type_id' => 'required',
            'course_group_id' => 'required',
            'scientific_field_id' => 'required',
        ];
    }
}
