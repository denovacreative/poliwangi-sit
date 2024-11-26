<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClassGroupRequest extends FormRequest
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
                $this->routeName == 'master.class-groups.store' ? 'unique:class_groups,code' : Rule::unique('class_groups', 'code')->ignoreModel($this->classGroup),
            ],
            'name' => 'required',
            'academic_year' => 'required',
            'study_program' => 'required'
        ];
    }
}
