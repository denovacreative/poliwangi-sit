<?php

namespace App\Http\Controllers\Api\Lecture\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lecture\Administration\HerRegistrationRequest;
use App\Models\Heregistration;
use App\Models\StudentCollegeActivity;
use Illuminate\Http\Request;
use DataTables;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Vinkla\Hashids\Facades\Hashids;

class HerRegistrationController extends Controller
{
    public function index(Request $request)
    {

        $query = Heregistration::whereHas('academicPeriod', function ($q) {
            $q->where('is_use', true);
        })->with(['academicPeriod', 'student']);

        if ($request->has('study_program_id') && $request->study_program_id != '' && $request->study_program_id != 'all') {
            $query->whereHas('student.studyProgram', function ($q) use ($request) {
                $q->where('id', $request->study_program_id);
            });
        }

        if ($request->has('academic_period_id') && $request->academic_period_id != '' && $request->academic_period_id != 'all') {
            $query->where('academic_period_id', Hashids::decode($request->academic_period_id)[0]);
        }

        if ($request->has('is_schoolarship') && $request->is_schoolarship != '' && $request->is_schoolarship != 'all') {
            $query->where('is_scholarship', $request->is_schoolarship);
        }

        if ($request->has('is_acc') && $request->is_acc != '' && $request->is_acc != 'all') {
            $query->where('is_acc', $request->is_acc == 'true');
        }
        if (mappingAccess() != null) {
            $query->whereHas('student', function ($q) {
                $q->whereIn('study_program_id', mappingAccess());
            });
        }

        return DataTables::of($query)->editColumn('payment_date', function ($data) {
            return date('d-m-Y', strtotime($data->payment_date));
        })->make();
    }

    public function validateData(Heregistration $heregistration, HerRegistrationRequest $request)
    {
        try {

            DB::beginTransaction();

            // $scores = $heregistration->student->score()->with('collegeClass.academicPeriod')->get()->mapToGroups(function($item, $key) {
            //     return [$item->collegeClass->academic_period_id => $item];
            // })->all();

            // $scoresInCurrentSemester = $heregistration->student->score()->whereHas('collegeClass.academicPeriod', function($q) {
            //     $q->where('is_use', true);
            // })->get();

            // $studentCollegeActivity = StudentCollegeActivity::where('academic_period_id', $heregistration->academic_period_id)->where('student_id', $heregistration->student_id)->first();

            // if ($studentCollegeActivity) {
            //     $studentCollegeActivity->update([
            //         'student_status_id' => 'A',
            //         'tuition_fee' => $heregistration->tuition_fee,
            //         'is_valid' => true,
            //     ]);
            // } else {
            //     StudentCollegeActivity::create([
            //         'id' => Uuid::uuid4(),
            //         'academic_period_id' => $heregistration->academic_period_id,
            //         'student_id' => $heregistration->student_id,
            //         'student_status_id' => 'A',
            //         'grade_semester' => 0,
            //         'grade' => 0,
            //         'credit_semester' => 0,
            //         'credit_total' => 0,
            //         'tuition_fee' => $heregistration->tuition_fee,
            //         'is_valid' => true,
            //     ]);
            // }

            $heregistration->is_acc = $request->is_acc;
            $heregistration->validator_id = Auth::user()->id;
            $heregistration->save();

            // if ($request->is_acc) {
            //     $heregistration->student->student_status_id = 'A';
            //     $heregistration->student->save();
            // }

            DB::commit();

            return $this->successResponse('Berhasil melakukan validasi data');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->exceptionResponse($e);
        }
    }

    // private function countIps($scoresInSemester)
    // {

    //     $scoresInSemester = collect($scoresInSemester);

    //     $scoresList = collect([]);
    //     $creditsList = collect([]);

    //     foreach ($scoresInSemester as $score) {
    //         $scoresList->add(((double) $score->index_score) * $score->collegeClass->credit_total);
    //         $creditsList->add($score->collegeClass->credit_total);
    //     }

    //     $sumScores = $scoresList->sum();
    //     $sumCredits = $creditsList->sum();

    //     return [
    //         'score' => $sumCredits > 0 ? bcdiv($sumScores, $sumCredits, 2) : 0,
    //         'sum_credit' => $sumCredits,
    //         'sum_score' => $sumScores,
    //     ];
    // }

    // private function countIpk($scoresInAllSemesters)
    // {

    //     $scoresList = collect([]);
    //     $creditsList = collect([]);

    //     foreach ($scoresInAllSemesters as $scoreInOneSemester) {
    //         $semesterData = $this->countIps($scoreInOneSemester);
    //         $scoresList->add($semesterData['sum_score']);
    //         $creditsList->add($semesterData['sum_credit']);
    //     }

    //     $sumScores = $scoresList->sum();
    //     $sumCredits = $creditsList->sum();

    //     return [
    //         'score' => $sumCredits > 0 ? bcdiv($sumScores, $sumCredits, 2) : 0,
    //         'sum_credit' => $sumCredits,
    //         'sum_score' => $sumScores,
    //     ];
    // }
}
