<?php

namespace App\Http\Requests\StudentAffairs;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AchievementTypeRequest extends FormRequest
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
            'code' =>  [
                'required',
                $this->routeName == 'student-affairs.achievement-type.store' ? 'unique:achievement_types,code' : Rule::unique('achievement_types', 'code')->ignoreModel($this->achievementType),
            ],
            'name' => 'required'
        ];
    }
}
