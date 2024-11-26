<?php

namespace App\Http\Requests\StudentAffairs;

use Illuminate\Foundation\Http\FormRequest;

class DiplomaCompanionRequest extends FormRequest
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
            'education_level' => 'required',
            'terms_acceptance' => 'required',
            'terms_acceptance_en' => 'required',
            'study' => 'required',
            'type_education' => 'required',
            'type_education_en' => 'required',
            'next_type_education' => 'required',
            'next_type_education_en' => 'required',
            'instruction_language' => 'required',
            'instruction_language_en' => 'required',
            'introduction' => 'required',
            'introduction_en' => 'required',
            'kkni_info' => 'required',
            'kkni_info_en' => 'required',
            'work_ability' => 'required',
            'mastery_of_knowledge' => 'required',
            'special_attitude' => 'required',
        ];
    }
}
