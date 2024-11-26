<?php

namespace App\Http\Controllers\Api\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\SemesterRequest;
use App\Models\AcademicPeriod;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SemesterController extends Controller
{
    public function index()
    {
        return DataTables::of(AcademicPeriod::with(['academicYear']))->addColumn('college_date', function ($data) {
            return Carbon::parse($data->college_start_date)->isoFormat('D MMM Y') . ' s.d ' . Carbon::parse($data->college_end_date)->isoFormat('D MMM Y');
        })->addColumn('mid_exam_date', function ($data) {
            return Carbon::parse($data->mid_exam_start_date)->isoFormat('D MMM Y') . ' s.d ' . Carbon::parse($data->mid_exam_end_date)->isoFormat('D MMM Y');
        })->addColumn('final_exam_date', function ($data) {
            return Carbon::parse($data->final_exam_start_date)->isoFormat('D MMM Y') . ' s.d ' . Carbon::parse($data->final_exam_end_date)->isoFormat('D MMM Y');
        })->addColumn('heregistration_date', function ($data) {
            if ($data->heregistration_start_date == null || $data->heregistration_end_date == null) {
                return '-';
            } else {
                return Carbon::parse($data->heregistration_start_date)->isoFormat('D MMM Y') . ' s.d ' . Carbon::parse($data->heregistration_end_date)->isoFormat('D MMM Y');
            }
        })->make();
    }

    public function store(SemesterRequest $request)
    {
        try {

            $validate = $this->validateSemester($request, ['college', 'mExam', 'fExam', 'heregistration', 'existing_her']);
            if ($validate['status'] == true) {
                return $this->errorResponse(500, $validate['message']);
            }
            AcademicPeriod::create([
                'id' => $request->code,
                'academic_year_id' => $request->academic_year,
                'semester' => $request->semester,
                'name' => $request->name,
                'college_start_date' => $request->college_start_date,
                'college_end_date' => $request->college_end_date,
                'mid_exam_start_date' => $request->mid_exam_start_date,
                'mid_exam_end_date' => $request->mid_exam_end_date,
                'final_exam_start_date' => $request->final_exam_start_date,
                'final_exam_end_date' => $request->final_exam_end_date,
                'heregistration_start_date' => $request->heregistration_start_date,
                'heregistration_end_date' => $request->heregistration_end_date,
                'number_of_meeting' => $request->number_of_meeting,
                'is_active' => isset($request->status) && $request->status == 'on' ? true : false
            ]);
            return $this->successResponse('Berhasil membuat data semester baru');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show(AcademicPeriod $academicPeriod)
    {
        return $this->successResponse(null, compact('academicPeriod'));
    }

    public function destroy(AcademicPeriod $academicPeriod)
    {
        try {
            $academicPeriod->delete();
            return $this->successResponse('Berhasil menghapus data semester');
        } catch (QueryException $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function updateStatus(AcademicPeriod $academicPeriod)
    {
        try {
            $academicPeriod->update(['is_active' => !$academicPeriod->is_active]);
            return $this->successResponse('Berhasil mengubah status semester');
        } catch (QueryException $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function setActive(AcademicPeriod $academicPeriod)
    {
        try {
            AcademicPeriod::where('id', '!=', $academicPeriod->id)->update(['is_use' => false]);
            $academicPeriod->update(['is_use' => !$academicPeriod->is_use]);
            $academicPeriodCheck = AcademicPeriod::where('is_use', true)->first();
            if (!$academicPeriodCheck) {
                $academicPeriod->update(['is_use' => true]);
                return $this->errorResponse(500, 'Opps! Minimal harus ada satu semester yang aktif');
            }
            return $this->successResponse('Berhasil set semester aktif');
        } catch (QueryException $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(AcademicPeriod $academicPeriod, SemesterRequest $request)
    {
        try {
            // return dd($request->college_start_date != $academicPeriod->college_start_date);
            if ($request->college_start_date != $academicPeriod->college_start_date) {
                $validate = $this->validateSemester($request, ['college']);
                if ($validate['status'] == true) {
                    return $this->errorResponse(500, $validate['message']);
                }
            }
            if ($request->college_end_date != $academicPeriod->college_end_date) {
                $validate = $this->validateSemester($request, ['college']);
                if ($validate['status'] == true) {
                    return $this->errorResponse(500, $validate['message']);
                }
            }
            if ($request->mid_exam_start_date != $academicPeriod->mid_exam_start_date) {
                $validate = $this->validateSemester($request, ['mExam']);
                if ($validate['status'] == true) {
                    return $this->errorResponse(500, $validate['message']);
                }
            }
            if ($request->mid_exam_end_date != $academicPeriod->mid_exam_end_date) {
                $validate = $this->validateSemester($request, ['mExam']);
                if ($validate['status'] == true) {
                    return $this->errorResponse(500, $validate['message']);
                }
            }
            if ($request->final_exam_start_date != $academicPeriod->final_exam_start_date) {
                $validate = $this->validateSemester($request, ['fExam']);
                if ($validate['status'] == true) {
                    return $this->errorResponse(500, $validate['message']);
                }
            }
            if ($request->final_exam_end_date != $academicPeriod->final_exam_end_date) {
                $validate = $this->validateSemester($request, ['fExam']);
                if ($validate['status'] == true) {
                    return $this->errorResponse(500, $validate['message']);
                }
            }
            if ($request->heregistration_start_date != $academicPeriod->heregistration_start_date) {
                $validate = $this->validateSemester($request, ['heregistration', 'existing_her']);
                if ($validate['status'] == true) {
                    return $this->errorResponse(500, $validate['message']);
                }
            }
            if ($request->heregistration_end_date != $academicPeriod->heregistration_end_date) {
                $validate = $this->validateSemester($request, ['heregistration', 'existing_her']);
                if ($validate['status'] == true) {
                    return $this->errorResponse(500, $validate['message']);
                }
            }


            $academicPeriod->update([
                'id' => $request->code,
                'academic_year_id' => $request->academic_year,
                'semester' => $request->semester,
                'name' => $request->name,
                'college_start_date' => $request->college_start_date,
                'college_end_date' => $request->college_end_date,
                'mid_exam_start_date' => $request->mid_exam_start_date,
                'mid_exam_end_date' => $request->mid_exam_end_date,
                'final_exam_start_date' => $request->final_exam_start_date,
                'final_exam_end_date' => $request->final_exam_end_date,
                'heregistration_start_date' => $request->heregistration_start_date,
                'heregistration_end_date' => $request->heregistration_end_date,
                'number_of_meeting' => $request->number_of_meeting,
                'is_active' => isset($request->status) && $request->status == 'on' ? true : false
            ]);
            return $this->successResponse('Berhasil mengupdate data semester');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    private function validateSemester($request, $validate = [])
    {
        $herStartDate = Carbon::parse($request->heregistration_start_date)->format('Y-m-d');
        $herEndDate = Carbon::parse($request->heregistration_end_date)->format('Y-m-d');
        $collegeStartDate = Carbon::parse($request->college_start_date)->format('Y-m-d');
        $collegeEndDate = Carbon::parse($request->college_end_date)->format('Y-m-d');
        $mExamStartDate = Carbon::parse($request->mid_exam_start_date)->format('Y-m-d');
        $mExamEndDate = Carbon::parse($request->mid_exam_end_date)->format('Y-m-d');
        $fExamStartDate = Carbon::parse($request->final_exam_start_date)->format('Y-m-d');
        $fExamEndDate = Carbon::parse($request->final_exam_end_date)->format('Y-m-d');

        $checkIfExistHerDate = AcademicPeriod::whereBetween('heregistration_start_date', [$herStartDate, $herEndDate])
            ->orWhereBetween('heregistration_end_date', [$herStartDate, $herEndDate])->get();

        if (in_array('college', $validate)) {
            if ($collegeEndDate <= $collegeStartDate) {
                return [
                    'status' => true,
                    'message' => 'Tanggal perkuliahan tidak valid'
                ];
            }
        }
        if (in_array('mExam', $validate)) {
            if ($mExamEndDate <= $mExamStartDate) {
                return [
                    'status' => true,
                    'message' => 'Tanggal UTS tidak valid'
                ];
            }
        }
        if (in_array('fExam', $validate)) {
            if ($fExamEndDate <= $fExamStartDate) {
                return [
                    'status' => true,
                    'message' => 'Tanggal UAS tidak valid'
                ];
            }
        }
        if (in_array('heregistration', $validate)) {
            if ($herEndDate <= $herStartDate) {
                return [
                    'status' => true,
                    'message' => 'Tanggal heregistrasi tidak valid'
                ];
            }
        }
        if (in_array('existing_her', $validate)) {
            if (count($checkIfExistHerDate) > 0) {
                return [
                    'status' => true,
                    'message' => 'Tanggal heregistrasi sudah terdaftar'
                ];
            }
        }
        return [
            'status' => false
        ];
    }
}
