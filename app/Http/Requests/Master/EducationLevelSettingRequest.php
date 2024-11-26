<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EducationLevelSettingRequest extends FormRequest
{
    private $routeName;

    public function __construct()
    {
        $this->routeName = request()->route()->getName();
    }

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
            'education_level_id' => [
                'required',
                $this->routeName == 'master.education-level-settings.store' ? 'unique:education_level_settings,education_level_id' : Rule::unique('education_level_settings', 'education_level_id')->ignore($this->educationLevelSetting)
            ],
            'study' => 'required',
            'max_leave' => 'required',
            'max_study' => 'required'
        ];
    }
}
