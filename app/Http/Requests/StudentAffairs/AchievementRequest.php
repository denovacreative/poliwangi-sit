<?php

namespace App\Http\Requests\StudentAffairs;

use Illuminate\Foundation\Http\FormRequest;

class AchievementRequest extends FormRequest
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
            //
            'name' => 'required',
            'name_en' => 'required',
            'student' => 'required',
            'achievement_group' => 'required',
            'academic_period' => 'required',
            'achievement_level' => 'required',
            'rate' => 'required',
            'event_type' => 'required',
            'position' => 'required',
            'location' => 'required',
            'organizer' => 'required',
            'date_start' => 'date|required',
            'date_end' => 'date|required',
            'decree_number' => 'required',
            'decree_date' => 'date|required',
            'is_valid' => 'required',
            'is_show_skpi' => 'required',
            'file' => 'mimes:jpeg,png,jpg,pdf',
        ];
    }
}
