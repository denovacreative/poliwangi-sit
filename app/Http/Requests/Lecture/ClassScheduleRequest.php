<?php

namespace App\Http\Requests\Lecture;

use App\Models\ClassSchedule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
            'employee' => 'required|uuid',
            'meeting_type' => 'required',
            'college_class' => 'required|uuid',
            'meeting_number' => 'required',
            'time_start' => 'required',
            'time_end' => 'required',
            'learning_method' => 'required',
            'credit' => 'required|numeric',
            'link_meeting' => 'nullable|string',
            'location' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:txt,pdf,doc,docx,csv,ppt,pptx,xls,xlsx,zip,jpeg,jpg,png,gif|max:2048',
            'presence_document' => 'nullable|file|mimes:txt,pdf,doc,docx,csv,ppt,pptx,xls,xlsx,zip|max:2048',
            'journal_document' => 'nullable|file|mimes:txt,pdf,doc,docx,csv,ppt,pptx,xls,xlsx,zip|max:2048',
            'status' => 'required',
        ];
    }
}
