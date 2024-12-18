<?php

namespace App\Http\Requests\Lecture;

use Illuminate\Foundation\Http\FormRequest;

class ImportStudentActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
            'file' => 'required|mimes:csv,xlsx,xls|max:5000'
        ];
    }
}
