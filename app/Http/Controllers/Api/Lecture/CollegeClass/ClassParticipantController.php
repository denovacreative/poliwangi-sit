<?php

namespace App\Http\Controllers\Api\Lecture\CollegeClass;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lecture\CollegeClass\ClassParticipantRequest;
use App\Http\Requests\Lecture\CollegeClass\SetCoordinatorRequest;
use Illuminate\Http\Request;
use App\Models\Presence;
use App\Models\ClassParticipant;
use App\Models\CollegeClass;
use App\Models\Student;
use App\Models\Score;
use App\Models\StudentCollegeActivity;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use DataTables;
use Exception;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;

class ClassParticipantController extends Controller
{
    public function index(CollegeClass $collegeClass, Request $request)
    {
        $query = ClassParticipant::with(['student', 'collegeClass'])->where('college_class_id', $collegeClass->id);
        return DataTables::of($query)
            ->addColumn('student_name', function ($data) {
                return $data->student->name;
            })
            ->addColumn('student_nim', function ($data) {
                return $data->student->nim;
            })
            ->addColumn('student_gender', function ($data) {
                return $data->student->gender;
            })
            ->make();
    }

    public function getSingleStudentSearchResult(CollegeClass $collegeClass, Request $request)
    {
        $students = Student::where('student_status_id', 'A')->where(function ($q) use ($request) {
            $q->where('name', 'iLike', '%' . $request->name . '%')->orWhere('nim', 'iLike', '%' . $request->name . '%');
        })->where('curriculum_id', '=', $collegeClass->curriculum_id)->whereDoesntHave('judicialParticipant');
        if (mappingAccess() != null) {
            $students = $students->whereIn('study_program_id', mappingAccess());
        }
        $students = $students->get(['nim', 'id', 'name']);

        return $this->successResponse('Berhasil mendapatkan list data mahasiswa', compact('students'));
    }

    public function store(CollegeClass $collegeClass, ClassParticipantRequest $request)
    {
        try {
            if (!is_array($request->students)) {

                $classParticipant = ClassParticipant::where([
                    'college_class_id' => $request->college_class_id,
                    'student_id' => $request->students,
                ])->first();

                if (!$classParticipant) {

                    DB::beginTransaction();

                    $student = Student::find($request->students);

                    if ($student->curriculum_id != null && $student->curriculum_id != $collegeClass->curriculum_id) {
                        throw new Exception('Kurikulum mahasiswa ini tidak sama dengan kurikulum kelas !');
                    }

                    ClassParticipant::create([
                        'id' => Uuid::uuid4(),
                        'student_id' => $request->students,
                        'college_class_id' => $request->college_class_id,
                    ]);

                    DB::commit();

                    return response()->json([
                        'message' => 'Berhasil membuat data peserta kelas'
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Peserta kelas ini sudah ada!'
                    ], 400);
                }
            } else {

                $classParticipantsToInsert = [];
                // $scoreToInserts = [];
                // $presencesToInsert = [];

                DB::beginTransaction();

                $classSchedules = $collegeClass->classSchedule;

                $students = Student::whereIn('id', $request->students)->get();
                $studentsMapped = $students->mapWithKeys(function ($item, $key) {
                    return [$item->id => $item->curriculum_id];
                });

                foreach ($request->students as $studentId) {

                    $isClassParticipantExist = ClassParticipant::where('student_id', $studentId)->where('college_class_id', $request->college_class_id)->first();

                    if (!$isClassParticipantExist && ($studentsMapped[$studentId] == null || $collegeClass->curriculum_id == $studentsMapped[$studentId])) {

                        $classParticipantsToInsert[] = [
                            'id' => Uuid::uuid4(),
                            'student_id' => $studentId,
                            'college_class_id' => $request->college_class_id,
                            'created_at' => Carbon::now()
                        ];
                    }
                }

                ClassParticipant::insert($classParticipantsToInsert);

                DB::commit();

                return $this->successResponse('Berhasil memasukkan data mahasiswa!');
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $this->exceptionResponse($e);
        }
    }


    public function setCoordinator(CollegeClass $collegeClass, ClassParticipant $classParticipant)
    {
        ClassParticipant::where('college_class_id', $collegeClass->id)->update([
            'is_class_coordinator' => false
        ]);

        $classParticipant->is_class_coordinator = true;
        $classParticipant->save();

        return response()->json([
            'message' => 'Berhasil mengubah koordinator kelas'
        ]);
    }

    public function destroy(CollegeClass $collegeClass, ClassParticipant $classParticipant)
    {
        try {
            DB::beginTransaction();
            $classParticipant->delete();
            DB::commit();
            return response()->json([
                'message' => 'Berhasil menghapus data peserta kelas'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->exceptionResponse($e);
        }
    }

    public function getClassParticipantStudentList(CollegeClass $collegeClass, Request $request)
    {
        $query = Student::with(['studyProgram.educationLevel', 'classGroup', 'studentStatus', 'academicPeriod.academicYear'])->where('student_status_id', 'A')->whereDoesntHave('judicialParticipant');

        if ($request->has('study_program_id') && $request->study_program_id != null && $request->study_program_id != 'all') {
            $query->where('study_program_id', $request->study_program_id);
        }

        if ($request->has('class_group_id') && $request->class_group_id != null && $request->class_group_id != 'all') {
            $query->where('class_group_id', Hashids::decode($request->class_group_id));
        }

        if ($request->has('academic_year_id') && $request->academic_year_id != null && $request->academic_year_id != 'all') {
            $query->whereHas('academicPeriod', function ($q) use ($request) {
                $q->where('academic_year_id', Hashids::decode($request->academic_year_id));
            });
        }
        if (mappingAccess() != null) {
            $query->whereIn('study_program_id', mappingAccess());
        }

        return DataTables::of($query)
            ->addColumn('akm_semester', function ($data) {
                return StudentCollegeActivity::where(['student_id' => $data->id, 'student_status_id' => 'A'])->count();
            })
            ->make();
    }

    public function checkLecturerEligibilityToCreate(CollegeClass $collegeClass)
    {

        $studyProgramSetting = $collegeClass->studyProgram->studyProgramSetting()->where('academic_period_id', getActiveAcademicPeriod()->id)->get();
        $isLecturer = getInfoLogin()->hasRole('Dosen');

        return response()->json([
            'isLecturer' => $isLecturer,
            'isAllowed' => $isLecturer && $studyProgramSetting ? $studyProgramSetting[0]->is_lecture_generate : false,
        ]);
    }
}
