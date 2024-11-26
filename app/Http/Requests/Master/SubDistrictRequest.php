<?php

namespace App\Http\Requests\Master;

use App\Rules\DistrictParent;
use Illuminate\Foundation\Http\FormRequest;

class SubDistrictRequest extends FormRequest
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
            'name' => 'required',
            'parent' => [new DistrictParent],
        ];
    }
}
