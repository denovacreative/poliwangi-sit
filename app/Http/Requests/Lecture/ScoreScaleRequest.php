<?php

namespace App\Http\Requests\Lecture;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class ScoreScaleRequest extends FormRequest
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
    public function rules(Request $request)
    {
        return [
            'type_study_program' => ($this->routeName === 'lecture.score-scales.store') ? 'required' : 'nullable',
            'study_program' => ($this->routeName === 'lecture.score-scales.update') ? 'required' : [Rule::requiredIf(!empty($request->get('type_study_program') and $request->get('type_study_program') == 'select'))],
            'grade' => 'required|max:3|string',
            'index_score' => 'required|numeric',
            'min_score' => 'required|numeric',
            'max_score' => 'required|numeric',
            'year_start' => 'required',
            'year_end' => 'required',
            'is_score_def' => 'required'
        ];
    }
}
