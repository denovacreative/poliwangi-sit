<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudyProgramRequest extends FormRequest
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
            'code' => [
                'required',
                $this->routeName == 'master.study-programs.store' ? 'unique:study_programs,code' : Rule::unique('study_programs', 'code')->ignoreModel($this->studyProgram),
                'max:10'
            ],
            'name' => 'required',
            'phone_number' => 'required|numeric',
            'acreditation' => [
                Rule::in(['A', 'B', 'C', 'none'])
            ],
            'education_level' => 'required'
        ];
    }
}
