<?php

namespace App\Http\Controllers\Api\Student\FinalLevel;

use App\Http\Controllers\Controller;
use App\Models\AcademicPeriod;
use App\Models\AcademicYear;
use App\Models\StudyProgram;
use Illuminate\Http\Request;
use App\Models\Thesis;
use App\Models\University;
use App\Models\UniversityProfile;
use DataTables;
use Exception;

class ThesisController extends Controller
{

    public function index()
    {
        return DataTables::of(Thesis::query()->where('student_id', getInfoLogin()->userable_id))->make();
    }

    public function store()
    {
    }

    public function show(Thesis $these)
    {
        return $this->successResponse(null, compact('these'));
    }

    public function update()
    {
    }

    public function printTotalThesis(Request $request){
        $query = Thesis::with(['student','student.studyProgram','student.academicPeriod.academicYear', 'academicPeriod']);
        $academicPeriod = 'Semua Periode Akademik';

        if ($request->has('academic_period_id') && $request->academic_period_id != '' && $request->academic_period_id != 'all') {
            $query->whereHas('academicPeriod', function ($q) use ($request){
                $q->where('id' , $request->academic_period_id);
            });

            $academicPeriod = AcademicPeriod::where('id' , $request->academic_period_id)->count();

            if ($academicPeriod == 0) {
                return abort(404);
            }
            $academicPeriod = AcademicPeriod::where('id' , $request->academic_period_id)->first()->name;

        }
        return view('print.total-thesis-students', [
            'title' => 'Laporan Jumlah Mahasiswa Tugas Akhir | Poliwangi',
            'universitasProfile' => UniversityProfile::first(),
            'academicPeriod' => $academicPeriod,
            'data' => $query->get()
        ]);
    }


    public function print(Request $request){
        try {
            $academicYear = '';
            $studyProgram = '';
            $query = Thesis::with(['student', 'thesisGuidance.employee', 'student.studyProgram', 'academicPeriod.academicYear']);

            if ($request->study_program_id == null) {
                $studyProgram = 'Semua Program Studi';
            }
            if ($request->academic_year_id == null) {
                $academicYear = 'Semua Angakatan';
            }
            if ($request->has('study_program_id') && $request->study_program_id != '' && $request->study_program_id != 'all') {
                $query->whereHas('student', function ($q) use ($request) {
                    $q->whereHas('studyProgram', function ($q) use ($request) {
                        $q->where('id', $request->study_program_id);
                    });
                });
                $data = StudyProgram::with('educationLevel')->where('id', $request->study_program_id)->first();
                $studyProgram = $data->educationLevel->code . '-' . $data->name;
            }
            if ($request->has('academic_year_id') && $request->academic_year_id != '' && $request->academic_year_id != 'all') {

                    $query->whereHas('academicPeriod', function ($q) use ($request) {
                        $q->whereHas('academicYear', function ($q) use ($request) {
                            $q->where('id', $request->academic_year_id);
                        });
                });
                $data = AcademicYear::where('id', $request->academic_year_id)->first();
                $academicYear = $data->name;
            }
            return view('print.thesis', [
                'title' => 'Laporan Tugas Akhir',
                'data' => $query->get(),
                'universitasProfile' => UniversityProfile::first(),
                'header' => [
                    'academicYear' => $academicYear,
                    'studyProgram' => $studyProgram
                ]
            ]);
        } catch (Exception $e) {
            return abort(404);
        }
    }

    public function destroy(Thesis $these)
    {
        try {
            $these->delete();

            return $this->successResponse('Berhasil menghapus data');
        } catch (Exception $e) {
            return $this->exceptonResponse($e);
        }
    }
}
