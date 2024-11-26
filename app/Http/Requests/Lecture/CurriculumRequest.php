<?php

namespace App\Http\Requests\Lecture;

use Illuminate\Foundation\Http\FormRequest;

class CurriculumRequest extends FormRequest
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
            'study_program_id' => 'required',
            'academic_period_id' => 'required',
            'name' => 'required',
            'mandatory_credit' => 'required|numeric',
            'choice_credit' => 'numeric',
        ];
    }
}
