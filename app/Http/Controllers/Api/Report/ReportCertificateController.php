<?php

namespace App\Http\Controllers\Api\Report;

use App\Http\Controllers\Controller;
use App\Models\JudicialParticipant;
use App\Models\Student;
use App\Models\UniversityProfile;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class ReportCertificateController extends Controller
{
    public function print(Request $request)
    {
        $query = JudicialParticipant::with(['student', 'judicialPeriod', 'student.academicPeriod']);
        try {
            if ($request->has('study_program_id') && $request->study_program_id != null) {
                $query->whereHas('student', function ($q) use ($request) {
                    $q->where('study_program_id', $request->study_program_id);
                });
            }

            if ($request->has('academic_year_id') && $request->academic_year_id != null) {
                $query->whereHas('student', function ($q) use ($request) {
                    $q->whereHas('academicPeriod', function ($q) use ($request) {
                        $q->where('academic_year_id', $request->academic_year_id);
                    });
                });
            }

            if ($request->has('judicial_period_id') && $request->judicial_period_id != null) {
                $query->where('judicial_period_id', $request->judicial_period_id);
            }

            if ($request->has('student_id') && $request->student_id != null) {
                $query->where('student_id', $request->student_id);
            }
            $data = [];
            $univProfile = UniversityProfile::with('employee')->first();
            foreach ($query->get() as $key => $value) {
               $data[$value->student->name][] =[
                    'tanggal_lahir' => $value->student->birthdate,
                    'tempat_lahir' => $value->student->birthplace,
                    'nik' => $value->student->nik,
                    'nim' => $value->student->nim,
                    'program_studi' => $value->student->studyProgram->name,
                    'title' => $value->student->studyProgram->title,
                    'title_as' => $value->student->studyProgram->title_alias,
                    'program_code' => $value->student->studyProgram->educationLevel->code,
                    'jurusan' => $value->student->studyProgram->major->name,
                    'ketua_jurusan' => [
                               'nama' =>  $value->student->studyProgram->major->employee->name . ' '.str_replace(',', ', ', $value->student->studyProgram->major->employee->back_title),
                               'nip' => $value->student->studyProgram->major->employee->nip
                    ],
                    'tanggal_terbit' => date('d-m-Y'),
                    'direktur' => [
                        'nama' => $univProfile->employee->name . ' '.str_replace(',', ', ',  $univProfile->employee->back_title),
                        'nip' => $univProfile->employee->nip
                    ]
               ];
            }
            return view('print.student-certificate', [
                'title' => 'Ijazah Mahasiswa | Poliwangi',
                'datas' => $data
            ]);
            } catch (Exception $e) {
               return abort(404);
            }
    }

    public function singleSearch(Request $request)
    {
        return response()->json([
            'students' => Student::where('name', 'iLike', '%' . $request->name . '%')->whereHas('studentStatus', function ($e) {
                $e->where('is_college', true);
            })->get(),
        ]);
    }
}
