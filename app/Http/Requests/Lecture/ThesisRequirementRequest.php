<?php

namespace App\Http\Requests\Lecture;

use Illuminate\Foundation\Http\FormRequest;

class ThesisRequirementRequest extends FormRequest
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
            'name' => 'required',
            'thesis_type' => 'required',
            'thesis_stage' => 'required'
        ];
    }
}
