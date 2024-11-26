<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            'name' => 'required',
            'username' => [
                'required',
                $this->routeName === 'users.store' ? 'unique:users,username' : Rule::unique('users', 'username')->ignore($this->user),
            ],
            'email' => [
                'required',
                'email',
                $this->routeName === 'users.store' ? 'unique:users,email' : Rule::unique('users', 'email')->ignore($this->user),
            ],
            'password' => $this->routeName === 'users.store' ? 'required' : 'nullable',
            'confirm_password' => $this->routeName === 'users.store' ? 'required|same:password' : 'nullable',
            'role' => 'required',
            'phone_number' => 'required',
            'is_active' => 'required',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
