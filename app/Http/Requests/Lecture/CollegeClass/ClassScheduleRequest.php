<?php

namespace App\Http\Requests\Lecture\CollegeClass;

use Illuminate\Foundation\Http\FormRequest;

class ClassScheduleRequest extends FormRequest
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
            'meeting_number' => 'required|numeric',
            'date' => 'required|date',
            'time_start' => 'required',
            'time_end' => 'required',
            'meeting_type_id' => 'required',
            'credit' => 'required|numeric',
            'employee_id' => 'required',
            'learning_method' => 'required|in:online,offline,hybrid',
            'status' => 'required|in:schedule,start,done,reschedule',
            'attachment' => 'nullable|file|mimes:txt,pdf,doc,docx,csv,ppt,pptx,xls,xlsx,zip,jpeg,jpg,png,gif|max:2048',
            'presence_document' => 'nullable|file|mimes:txt,pdf,doc,docx,csv,ppt,pptx,xls,xlsx,zip|max:2048',
            'journal_document' => 'nullable|file|mimes:txt,pdf,doc,docx,csv,ppt,pptx,xls,xlsx,zip|max:2048',
            'material_realization' => 'required_if:status,done'
        ];
    }
}
