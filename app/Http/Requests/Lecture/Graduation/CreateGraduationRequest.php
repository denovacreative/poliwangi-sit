<?php

namespace App\Http\Requests\Lecture\Graduation;

use Illuminate\Foundation\Http\FormRequest;

class CreateGraduationRequest extends FormRequest
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

        $rules = [];

        if ($this->form_type != 'graduation') {
            $rules['student_id'] = 'required';
        }

        return $rules;
    }
}
