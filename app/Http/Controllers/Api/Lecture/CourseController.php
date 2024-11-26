<?php

namespace App\Http\Controllers\Api\Lecture;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lecture\CourseRequest;
use App\Models\Course;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use DataTables;
use Vinkla\Hashids\Facades\Hashids;
use Exception;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with(['courseType', 'studyProgram.educationLevel']);
        if (!empty($request->study_program_id) and $request->study_program_id != '' and $request->study_program_id != 'all') {
            $query->where('study_program_id', $request->study_program_id);
        }
        if (!empty($request->course_type_id) and $request->course_type_id != '' and $request->course_type_id != 'all') {
            $query->where('course_type_id', $request->course_type_id);
        }
        if (!empty($request->course_group_id) and $request->course_group_id != '' and $request->course_group_id != 'all') {
            $query->where('course_group_id', $request->course_group_id);
        }
        if (mappingAccess() != null) {
            $query->whereIn('study_program_id', mappingAccess());
        }
        return DataTables::of($query)->make();
    }

    public function store(CourseRequest $request)
    {
        try {

            $hashedFields = ['scientific_field_id'];
            $boolsValue = ['is_mku', 'is_sap', 'is_silabus', 'is_bahan_ajar', 'is_sap', 'is_diktat'];

            foreach ($hashedFields as $hf) $request->merge([$hf => $request->has($hf) ? Hashids::decode($request[$hf])[0] : null]);
            foreach ($boolsValue as $bf) $request->merge([$bf => $request->has($bf) ? true : false]);

            $request->merge([
                'id' => Uuid::uuid4(),
                'credit_total' => ($request->credit_meeting ?? 0) + ($request->credit_practicum ?? 0) + ($request->credit_practice ?? 0) + ($request->credit_simulation ?? 0)
            ]);

            Course::create($request->only([
                'code', 'name', 'name_en', 'alias', 'credit_total', 'credit_meeting', 'credit_practicum', 'credit_practice', 'credit_simulation', 'is_mku', 'is_sap', 'is_silabus', 'is_bahan_ajar', 'is_diktat', 'study_program_id', 'course_type_id', 'course_group_id', 'scientific_field_id', 'rps_employee_id', 'id'
            ]));

            return $this->successResponse('Berhasil membuat data mata kuliah');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(Course $course, CourseRequest $request)
    {
        try {

            $hashedFields = ['scientific_field_id'];
            $boolsValue = ['is_mku', 'is_sap', 'is_silabus', 'is_bahan_ajar', 'is_sap', 'is_diktat'];

            foreach ($hashedFields as $hf) $request->merge([$hf => $request->has($hf) ? Hashids::decode($request[$hf])[0] : null]);
            foreach ($boolsValue as $bf) $request->merge([$bf => $request->has($bf) ? true : false]);

            $request->merge([
                'credit_total' => ($request->credit_meeting ?? 0) + ($request->credit_practicum ?? 0) + ($request->credit_practice ?? 0) + ($request->credit_simulation ?? 0)
            ]);

            $course->update($request->only([
                'code', 'name', 'name_en', 'alias', 'credit_total', 'credit_meeting', 'credit_practicum', 'credit_practice', 'credit_simulation', 'is_mku', 'is_sap', 'is_silabus', 'is_bahan_ajar', 'is_diktat', 'study_program_id', 'course_type_id', 'course_group_id', 'scientific_field_id', 'rps_employee_id'
            ]));

            return $this->successResponse('Berhasil mengupdate data mata kuliah');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show($course)
    {

        $course = Course::with(['courseType', 'courseGroup', 'scientificField', 'studyProgram.educationLevel'])->where('id', $course)->first();
        $course->scientific_field_hashid = Hashids::encode($course->scientific_field_id);

        return $this->successResponse(null, compact('course'));
    }

    public function destroy(Course $course)
    {
        try {
            $course->delete();
            return $this->successResponse('Berhasil menghapus data mata kuliah');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
