<?php

namespace App\Http\Requests\Student\Academic;

use Illuminate\Foundation\Http\FormRequest;

class HerRegistrationRequest extends FormRequest
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
            'attachment_file' => 'required|mimes:png,jpg,jpeg,pdf|max:5000'
        ];
    }
}
