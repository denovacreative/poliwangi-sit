<?php

namespace App\Http\Controllers\Api\Lecture\Graduation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lecture\Graduation\CreateGraduationRequest;
use App\Http\Requests\Lecture\Graduation\UpdateGraduationRequest;
use App\Models\Graduation;
use App\Models\GraduationPredicate;
use App\Models\JudicialParticipant;
use App\Models\Student;
use App\Models\StudentCollegeActivity;
use App\Models\StudentStatus;
use Illuminate\Http\Request;
use DataTables;
use Exception;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class GraduationController extends Controller
{

    private $activeAcademicPeriodId = null;

    public function __construct()
    {
        $this->activeAcademicPeriodId = getActiveAcademicPeriod(true)->id;
    }

    public function index(Request $request)
    {
        try {
            $query = Graduation::query();

            if ($request->has('study_program_id') && $request->study_program_id != '') {
                $query->where('study_program_id', $request->study_program_id);
            }

            if ($request->has('student_status_id') && $request->student_status_id != '') {
                $query->where('student_status_id', $request->student_status_id);
            }

            if ($request->has('entry_year') && $request->entry_year != '') {
                $query->whereHas('student.academicPeriod', function($q) use ($request) {
                    $q->where('academic_year_id', $request->entry_year);
                });
            }

            if ($request->has('academic_period_id') && $request->academic_period_id != '') {
                $query->where('academic_period_id', $request->academic_period_id);
            }

            return DataTables::of($query->with(['student', 'studentStatus', 'studyProgram.educationLevel', 'academicPeriod'])->where('academic_period_id', $request->has('academic_period_id') && $request->academic_period_id != '' ? $request->academic_period_id : $this->activeAcademicPeriodId))->make();
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show(Graduation $graduation)
    {
        $graduation = Graduation::where('id', $graduation->id)->with(['academicPeriod'])->first();
        return $this->successResponse('', compact('graduation'));
    }

    public function getAllowedStudentStatuses(Request $request)
    {
        return response()->json([
            'statuses' => StudentStatus::where('is_college', false)->whereNotIn('id', ['L', '1'])->get(),
        ]);
    }

    public function store(CreateGraduationRequest $request)
    {
        try {
            DB::beginTransaction();
            if ($request->form_type == 'graduation') {

                if (!$request->students) {
                    return $this->errorResponse(400, 'Tidak ada siswa terpilih!');
                }

                $failedInsert = [];
                // Check if student already registered on judicium
                foreach ($request->students as $idx => $student) {
                    $student = Student::find($student);
                    $judicium = JudicialParticipant::where('student_id', $student->id)->first();
                    $lastAkm = StudentCollegeActivity::where('academic_period_id', $this->activeAcademicPeriodId)->where('student_id', $student->id)->first();
                    if ($lastAkm) {
                        if ($judicium) {
                            Graduation::create([
                                'id' => Uuid::uuid4(),
                                'student_id' => $student->id,
                                'student_status_id' => '1',
                                'graduation_date' => $request->graduation_date[$idx],
                                'year' => date('Y', strtotime($request->graduation_date[$idx])),
                                'description' => $request->description[$idx],
                                'certificate_number' => $request->certificate_number[$idx],
                                'academic_period_id' => $this->activeAcademicPeriodId,
                                'study_program_id' => $student->study_program_id,
                                'name' => $student->name,
                                'graduation_predicate_id' => GraduationPredicate::where('min_score', '<', $lastAkm->grade)->where('max_score', '>', $lastAkm->grade)->where('academic_year_id', getActiveAcademicPeriod(true)->academicYear->id)->first()->id,
                                'judiciary_number' => $judicium->decree_number,
                                'grade' => $lastAkm->grade,
                            ]);
                            $student->student_status_id = $request->student_status_id;
                            $student->save();
                        } else {
                            $failedInsert[] = [
                                'student' => $student,
                                'message' => 'Tidak terdaftar yudisium!'
                            ];
                        }
                    } else {
                        $failedInsert[] = [
                            'student' => $student,
                            'message' => 'Tidak terdaftar di AKM',
                        ];
                    }
                }
                DB::commit();
                if (count($failedInsert) == 0) {
                    return $this->successResponse('Berhasil memasukkan data kelulusan');
                } else {
                    return $this->successResponse('Beberapa data tidak berhasil dimasukkan', [
                        'status' => false,
                        'data' => $failedInsert,
                    ]);
                }
            } else {
                $studentStatus = StudentStatus::find($request->student_status_id);

                if ($studentStatus->is_college || $studentStatus->id == 'L' || $studentStatus->id == '1') {
                    return $this->errorResponse(400, 'Status mahasiswa salah!');
                }

                // If request other than graduation
                $activeAcademicPeriod = getActiveAcademicPeriod(true);
                $student = Student::find($request->student_id);
                $lastAkm = StudentCollegeActivity::where('academic_period_id', $this->activeAcademicPeriodId)->where('student_id', $student->id)->first();

                if (!$lastAkm) {
                    return $this->errorResponse(400, 'Siswa belum terdaftar di AKM!');
                }

                $graduationPredicate = GraduationPredicate::where('min_score', '<=', $lastAkm->grade)->where('max_score', '>=', $lastAkm->grade)->where('academic_year_id', $request->academic_period_id)->first();

                if (!$graduationPredicate) throw new Exception('Data predikat kelulusan tidak ditemukan untuk nilai di range ' . $lastAkm->grade . ' untuk tahun akademik ini');

                Graduation::create([
                    'id' => Uuid::uuid4(),
                    'academic_period_id' => $this->activeAcademicPeriodId,
                    'student_id' => $student->id,
                    'student_status_id' => $request->student_status_id,
                    'study_program_id' => $student->study_program_id,
                    'graduation_predicate_id' => $graduationPredicate->id,
                    'name' => $student->name,
                    'graduation_date' => $request->graduation_date,
                    'judiciary_number' => $request->judiciary_number,
                    'judiciary_date' => $request->judiciary_date,
                    'grade' => $lastAkm->grade,
                    'certificate_number' => $request->certificate_number,
                    'year' => date('Y', strtotime($request->graduation_date)),
                    'description' => $request->description,
                ]);

                $student->student_status_id = $request->student_status_id;
                $student->save();

                DB::commit();

                return $this->successResponse('Berhasil memasukkan data kelulusan!');
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $this->exceptionResponse($e);
        }
    }

    public function singleSearch(Request $request)
    {
        return response()->json([
            'students' => Student::where('name', 'iLike', '%' . $request->name . '%')->where('student_status_id', 'A')->get(),
        ]);
    }

    public function destroy(Graduation $graduation)
    {
        try {
            DB::beginTransaction();
            $latestAkm = StudentCollegeActivity::where('academic_period_id', $this->activeAcademicPeriodId)->where('student_id', $graduation->student->id)->first();

            if (!$latestAkm) {
                return $this->errorResponse(400, 'Tidak dapat menghapus, siswa ini belum mempunyai AKM di tahun ajaran saat ini!');
            }

            $graduation->student()->update([
                'student_status_id' => $latestAkm->student_status_id,
            ]);
            $graduation->delete();
            DB::commit();
            return $this->successResponse('Berhasil menghapus data graduation');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->exceptionResponse($e);
        }
    }

    public function update(Graduation $graduation, UpdateGraduationRequest $request)
    {
        try {
            $graduation->update($request->only(['student_status_id', 'academic_period_id', 'judiciary_date', 'judiciary_number', 'certificate_number', 'graduation_date', 'year', 'description']));
            return $this->successResponse('Berhasil mengupdate data kelulusan');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getStudents(Request $request)
    {

        $query = Student::where('student_status_id', 'A')->whereHas('judicialParticipant')->with(['studyProgram.educationLevel']);

        if ($request->has('study_program_id') && $request->study_program_id != '') {
            $query->where('study_program_id', $request->study_program_id);
        }

        if ($request->has('academic_year_id') && $request->academic_year_id != '') {
            $query->whereHas('academicPeriod', function($q) use ($request) {
                $q->where('academic_year_id', $request->academic_year_id);
            });
        }

        return DataTables::of($query)->make();
    }
}
