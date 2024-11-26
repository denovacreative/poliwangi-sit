<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomRequest extends FormRequest
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
            'unit_type' => [Rule::requiredIf(!empty($request->get('unit')))],
            'unit' => [Rule::requiredIf(!empty($request->get('unit_type')))],
            'code' => 'required|max:10',
            'name' => 'required',
            'location' => 'required',
            'capacity' => 'required|numeric',
            'type' => 'required',
            'is_active' => 'required'
        ];
    }
}
