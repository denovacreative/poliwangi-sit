<?php

namespace App\Http\Requests\Portal;

use Illuminate\Foundation\Http\FormRequest;

class CollegeStudentRequest extends FormRequest
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
            'photo_student' => ($this->routeName === 'portal.college-students.create' ? 'required' : 'nullable') . '|mimes:png,jpg,jpeg,png',
            'nim' => 'numeric|nullable',
            'name_student' => 'required',
            'study_program' => 'required',
            'academic_year' => 'required',
            'lecture_system' => 'required',
            'student_status' => 'required',
            'class_group' => 'nullable',
            'registration_type' => 'required',
            'registration_path' => 'required',
            'date' => 'required',
            // 'is_valid' => 'required||nullable',
            'gender' => 'required',
            'weight' => 'nullable',
            'height' => 'nullable',
            'blood' => 'nullable',
            'birth_place' => 'required',
            'birth_date' => 'required',
            'religion' => 'required',
            'ethnics' => 'nullable',
            'passpor' => 'nullable',
            'no_kk' => 'nullable',
            'kps_number' => 'nullable',
            'number_id' => 'nullable',
            'marital' => 'required|in:L,M,D,J',
            'jacket_size' => 'required',
            'phone_number' => 'required',
            'house_phone_number' => 'nullable',
            'personal_email' => 'nullable',
            'campus_email' => 'nullable',
            'student_profession' => 'nullable',
            'student_income' => 'nullable',
            'jalan' => 'nullable',
            'village_lev_2' => 'nullable',
            'neighborhood' => 'nullable',
            'hamlet' => 'nullable',
            'village' => 'nullable',
            'postal_code' => 'numeric|nullable',
            'address' => 'nullable',
            'subdistrict' => 'nullable',
            'district' => 'nullable',
            'provinces' => 'nullable',
            'countries' => 'required',
            'nik_father' => 'nullable',
            'father_name' => 'nullable',
            'father_birth_place' => 'nullable',
            'father_birth_date' => 'nullable',
            'father_education' => 'nullable',
            'father_address' => 'nullable',
            'father_life_status' => 'nullable',
            'father_relationship_status' => 'nullable',
            'father_phone_number' => 'nullable',
            'father_email' => 'nullable',
            'father_profession' => 'nullable',
            'father_income' => 'nullable',
            'mother_number_id_national' => 'nullable',
            'mother_name' => 'nullable',
            'mother_birth_place' => 'nullable',
            'mother_birth_date' => 'nullable',
            'mother_education' => 'nullable',
            'mother_address' => 'nullable',
            'mother_life_status' => 'nullable',
            'mother_relationship_status' => 'nullable',
            'mother_phone_number' => 'nullable',
            'mother_email' => 'nullable',
            'mother_profession' => 'nullable',
            'mother_income' => 'nullable',
            'nik_guardian' => 'nullable',
            'guardian_name' => 'nullable',
            'guardian_birth_place' => 'nullable',
            'guardian_birth_date' => 'nullable',
            'guardian_education' => 'nullable',
            'guardian_address' => 'nullable',
            'guardian_life_status' => 'nullable',
            'guardian_relationship_status' => 'nullable',
            'guardian_phone_number' => 'nullable',
            'guardian_email' => 'nullable',
            'guardian_profession' => 'nullable',
            'guardian_income' => 'nullable',
            'school_name' => 'nullable',
            'school_phone_number' => 'nullable',
            'school_address' => 'nullable',
            'school_region_id' => 'nullable',
            'school_diploma_number' => 'nullable',
            'diploma_file' => 'nullable|mimes:png,jpg,jpeg,png,pdf,doc,svg,docx',
        ];
    }
}
