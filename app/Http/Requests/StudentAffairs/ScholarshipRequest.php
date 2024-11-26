<?php

namespace App\Http\Requests\StudentAffairs;

use Illuminate\Foundation\Http\FormRequest;

class ScholarshipRequest extends FormRequest
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
            'period_start' => 'required',
            'period_end' => 'required',
            'date_start' => 'date|required',
            'date_end' => 'date|required',
            'scholarship_type' => 'required',
            'amount' => 'required|numeric'
        ];
    }
}
