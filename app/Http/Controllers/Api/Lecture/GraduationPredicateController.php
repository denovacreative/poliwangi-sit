<?php

namespace App\Http\Controllers\Api\Lecture;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lecture\GraduationPredicateRequest;
use App\Models\GraduationPredicate;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GraduationPredicateController extends Controller
{
    public function index(Request $request)
    {
        $query = GraduationPredicate::query();

        if (!empty($request->academic_year) and $request->academic_year != '' and $request->academic_year != 'all') {
            $query->where('academic_year_id', $request->academic_year);
        }

        return DataTables::of($query->with(['academicYear']))->make();
    }

    public function store(GraduationPredicateRequest $request)
    {
        try {
            GraduationPredicate::create([
                'academic_year_id' => $request->academic_year,
                'name' => $request->name,
                'name_en' => $request->name_en,
                'min_score' => $request->min_score,
                'max_score' => $request->max_score,
                'grade' => $request->grade
            ]);
            return $this->successResponse('Berhasil membuat data predikat kelulusan');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show(GraduationPredicate $graduationPredicate)
    {
        return $this->successResponse(null, compact('graduationPredicate'));
    }

    public function update(GraduationPredicate $graduationPredicate, GraduationPredicateRequest $request)
    {
        try {
            $graduationPredicate->update([
                'academic_year_id' => $request->academic_year,
                'name' => $request->name,
                'name_en' => $request->name_en,
                'min_score' => $request->min_score,
                'max_score' => $request->max_score,
                'grade' => $request->grade
            ]);
            return $this->successResponse('Berhasil mengupdate data predikat kelulusan');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(GraduationPredicate $graduationPredicate)
    {
        try {
            $graduationPredicate->delete();
            return $this->successResponse('Berhasil menghapus data predikat kelulusan');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
