<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class MeetingTypeRequest extends FormRequest
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
            'code' => 'required|string|max:5',
            'name' => 'required',
            'alias' => 'required',
            'type' => 'in:college,mid_exam,final_exam,none',
            'is_presence' => 'nullable',
            'is_exam' => 'nullable|boolean',
        ];
    }
}
