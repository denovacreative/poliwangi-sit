<?php

namespace App\Http\Requests\StudentAffairs;

use Illuminate\Foundation\Http\FormRequest;

class AchievementGroupRequest extends FormRequest
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
            'point' => 'required|numeric',
            'achievement_field' => 'required',
            'achievement_type' => 'required'
        ];
    }
}
