<?php

namespace App\Http\Requests\Portal;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnnouncementRequest extends FormRequest
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
    public function rules(Request $request)
    {
        return [
            'type_intended_for' => [Rule::requiredIf(!empty($request->get('intended_for')))],
            'intended_for' => [Rule::requiredIf(!empty($request->get('type_intended_for')))],
            'title' => 'required',
            'message' => 'required',
            'is_priority' => 'required',
            'is_active' => 'required',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'document' => 'nullable|file|mimes:txt,pdf,doc,docx,csv,ppt,pptx,xls,xlsx,zip|max:2048',
        ];
    }
}
