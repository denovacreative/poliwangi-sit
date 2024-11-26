<?php

namespace App\Http\Requests\Lecture;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JudicialPeriodRequest extends FormRequest
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
            'academic_period' => 'required',
            'periode' =>  [
                'required',
                'max:5',
                $this->routeName == 'lecture.judicial-period.store' ? 'unique:judicial_periods,periode' : Rule::unique('judicial_periods', 'periode')->ignoreModel($this->judicialPeriod),
            ],
            'name' => 'required',
            'date' => 'required|date',
            'date_start' => 'required|date',
            'date_end' => 'required|date'
        ];
    }
}
