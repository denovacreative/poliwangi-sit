<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SemesterRequest extends FormRequest
{
    private $routeName;

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
            'code' => [
                'required',
                'digits:5',
                'numeric',
                $this->routeName === 'setting.academic-period.update' ? Rule::unique('academic_periods', 'id')->ignore($this->academicPeriod) : 'unique:academic_periods,id'
            ],
            'academic_year' => 'required', 
            'semester' => [
                'required',
                Rule::in(['1','2','3'])
            ], 
            'name' => 'required', 
            'college_start_date' => 'required|date', 
            'college_end_date' => 'required|date', 
            'mid_exam_start_date' => 'required|date', 
            'mid_exam_end_date' => 'required|date', 
            'final_exam_start_date' => 'required|date', 
            'final_exam_end_date' => 'required|date', 
            'heregistration_start_date' => 'required|date', 
            'heregistration_end_date' => 'required|date', 
            'number_of_meeting' => 'required|numeric', 
        ];
    }
}
