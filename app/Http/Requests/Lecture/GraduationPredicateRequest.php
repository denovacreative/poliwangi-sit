<?php

namespace App\Http\Requests\Lecture;

use Illuminate\Foundation\Http\FormRequest;

class GraduationPredicateRequest extends FormRequest
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
            'academic_year' => 'required',
            'name' => 'required',
            'name_en' => 'required',
            'min_score' => 'required|numeric',
            'max_score' => 'required|numeric',
            'grade' => 'required|max:3',
        ];
    }
}
