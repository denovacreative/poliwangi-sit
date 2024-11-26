<?php

namespace App\Http\Controllers\Api\Student\Profile;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\Student;
use App\Models\StudentActivityMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentCollegeActivity;

class StudentProfileController extends Controller
{
    public function index()
    {
        $semester = StudentCollegeActivity::whereStudentId(getInfoLogin()->userable_id);
        $students = Auth::user()->userable()->with(['studyProgram.educationLevel', 'academicPeriod'])->first();
        $studentActivityMembers = StudentActivityMember::with(['studentActivity', 'studentActivity.academicPeriod', 'studentActivity.studyProgram','studentActivity.studyProgram.educationLevel'])->where('student_id' , getInfoLogin()->userable_id)->get();
        $achievements = Achievement::where('student_id', getInfoLogin()->userable_id)->get();
        $students->angkatan_formatted = $students->academic_period_id ? $students->academicPeriod->academic_year_id : '-';
        $countAll = 0;
        $countInvalid = 0;
        foreach ($students->toArray() as $key => $item) {
            if(!in_array($key, ['created_at', 'updated_at', 'is_valid', 'entry_date'])) {
                if (!is_null($item)) {
                    $countInvalid += 1;
                }

                $countAll += 1;
            }
        }
        $userData = Auth::user();

        return $this->successResponse('Berhasil mendapatkan profil mahasiswa', [
            'profile' => $students,
            'user' => $userData,
            'achievement' => $achievements,
            'studentActivityMembers' => $studentActivityMembers,
            'semester' => $semester->count(),
            'progress' => ceil(($countInvalid / $countAll) * 100)
        ]);
    }

    public function update(Request $request){
        Student::where('id' , getInfoLogin()->userable_id)->update(['phone_number' => $request->no_tlp, 'email' => $request->email]);
        return $this->successResponse('Berhasil memperbarui profile');
    }

}
