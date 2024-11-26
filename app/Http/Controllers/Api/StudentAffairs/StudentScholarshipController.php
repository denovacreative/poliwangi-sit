<?php

namespace App\Http\Controllers\Api\StudentAffairs;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentAffairs\StudentScholarshipRequest;
use App\Models\Scholarship;
use App\Models\StudentScholarship;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;

class StudentScholarshipController extends Controller
{
    public function index(Scholarship $scholarship, Request $request)
    {
        $query = StudentScholarship::with(['student.studyProgram.educationLevel', 'academicPeriod', 'scholarship'])->where(['scholarship_id' => $scholarship->id]);
        if ($request->has('academic_period_id') && $request->academic_period_id != '' && $request->academic_period_id != 'all') {
            $query->where('academic_period_id', $request->academic_period_id);
        }
        return DataTables::of($query)->make();
    }

    public function show(Scholarship $scholarship, StudentScholarship $studentScholarship)
    {
        return $this->successResponse(null, compact('studentScholarship'));
    }

    public function store(Scholarship $scholarship, StudentScholarshipRequest $request)
    {
        try {

            $findStudent = StudentScholarship::where(['student_id' => $request->student, 'scholarship_id' => $scholarship->id])->first();
            if ($findStudent) {
                return $this->errorResponse(500, 'Mahasiswa tersebut sudah terdaftar sebagai penerima beasiswa');
            }

            StudentScholarship::create([
                'id' => Uuid::uuid4(),
                'student_id' => $request->student,
                'amount' => $request->amount,
                'description' => $request->description,
                'is_active' => true,
                'scholarship_id' => $scholarship->id,
                'academic_period_id' => $request->academic_period,
            ]);
            return $this->successResponse('Berhasil membuat penerima beasiswa');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(Scholarship $scholarship, StudentScholarship $studentScholarship, StudentScholarshipRequest $request)
    {
        try {
            if ($request->student != $studentScholarship->student_id) {
                $findStudent = StudentScholarship::where(['student_id' => $request->student, 'scholarship_id' => $scholarship->id])->first();
                if ($findStudent) {
                    return $this->errorResponse(500, 'Mahasiswa tersebut sudah terdaftar sebagai penerima beasiswa');
                }
            }
            $studentScholarship->update([
                'student_id' => $request->student,
                'amount' => $request->amount,
                'description' => $request->description,
                'scholarship_id' => $scholarship->id,
                'academic_period_id' => $request->academic_period,
            ]);
            return $this->successResponse('Berhasil mengupdate penerima beasiswa');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(Scholarship $scholarship, StudentScholarship $studentScholarship)
    {
        try {
            $studentScholarship->delete();
            return $this->successResponse('Berhasil menghapus penerima beasiswa');
        } catch (QueryException $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function updateStatus(Scholarship $scholarship, StudentScholarship $studentScholarship)
    {
        try {
            $studentScholarship->update(['is_active' => !$studentScholarship->is_active]);
            return $this->successResponse('Berhasil mengubah status penerima beasiswa');
        } catch (QueryException $e) {
            return $this->exceptionResponse($e);
        }
    }
}
