<?php

namespace App\Http\Controllers\Api\Portal;

use App\Exports\StudentExcelExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\CollegeStudentRequest;
use App\Imports\StudentImport;
use App\Models\AcademicPeriod;
use App\Models\AcademicYear;
use App\Models\Achievement;
use App\Models\ActivityScoreConversion;
use App\Models\ClassGroup;
use App\Models\ClassParticipant;
use App\Models\CollegeClass;
use App\Models\Course;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\CourseCurriculum;
use App\Models\Major;
use App\Models\RegistrationPath;
use App\Models\Score;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentCollegeActivity;
use App\Models\StudentStatus;
use App\Models\StudyProgram;
use App\Models\Thesis;
use App\Models\Transcript;
use App\Models\UniversityProfile;
use Vinkla\Hashids\Facades\Hashids;
use DataTables;
use Ramsey\Uuid\Uuid;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Nette\Utils\Floats;

class CollegeStudentController extends Controller
{
    const STUDENT_PICTURE_PATH = 'storage/images/students/';
    public function index(Request $request)
    {
        $query = Student::with(['studyProgram.educationLevel', 'classGroup', 'studentStatus', 'academicPeriod.academicYear', 'religion', 'studentCollegeActivity'])
            ->withCount(['studentCollegeActivity' => function ($q) {
                $q->where('student_status_id', 'A');
            }]);
        if ($request->has('study_program_id') && $request->study_program_id != null && $request->study_program_id != 'all') {
            $query->where('study_program_id', $request->study_program_id);
        }
        if ($request->has('class_group_id') && $request->class_group_id != null && $request->class_group_id != 'all') {
            $query->where('class_group_id', $request->class_group_id);
        }
        if ($request->has('student_status_id') && $request->student_status_id != null && $request->student_status_id != 'all') {
            $query->where('student_status_id', $request->student_status_id);
        }
        if ($request->has('lecture_system_id') && $request->lecture_system_id != null && $request->lecture_system_id != 'all') {
            $query->where('lecture_system_id', Hashids::decode($request->lecture_system_id)[0]);
        }
        if ($request->has('registration_type_id') && $request->registration_type_id != null && $request->registration_type_id != 'all') {
            $query->where('registration_type_id', $request->registration_type_id);
        }
        if ($request->has('curriculum_id') && $request->curriculum_id != null && $request->curriculum_id != 'all') {
            $query->where('curriculum_id', $request->curriculum_id);
        }
        if ($request->has('gender') && $request->gender != null && $request->gender != 'all') {
            $query->where('gender', $request->gender);
        }

        if ($request->has('academic_year_id') && $request->academic_year_id != null && $request->academic_year_id != 'all') {
            $query->whereHas('academicPeriod', function ($q) use ($request) {
                $q->where('academic_year_id', $request->academic_year_id);
            });
        }
        if (mappingAccess() != null) {
            $query->whereIn('study_program_id', mappingAccess());
        }
        return DataTables::of($query)->addColumn('grade', function ($data) {
            $grade = 0;
            $length = count($data->studentCollegeActivity);
            if ($length > 0) {
                $grade = $data->studentCollegeActivity[$length - 1]->grade;
            }
            return (float) $grade;
        })->make();
    }

    public function show(Student $student)
    {
        $student = $student->whereId($student->id)->with(['studyProgram.educationLevel', 'classGroup', 'studentStatus', 'academicPeriod.academicYear', 'consentration', 'curriculum', 'registrationType', 'registrationPath', 'ethnic', 'region.parent.parent', 'country', 'fatherEducation', 'fatherProfession', 'fatherIncome', 'motherEducation', 'motherProfession', 'motherIncome', 'guardianEducation', 'guardianProfession', 'guardianIncome', 'schoolRegion', 'studentDisability.disability', 'lectureSystem', 'religion'])->first();
        $student['picture'] = asset('storage/images/students/' . ($student->picture ?? 'default.png'));
        $student['diploma_file'] = (!is_null($student->diploma_file) and $student->diploma_file != '') ? asset('storage/documents/documents/students/diploma/' . $student->diploma_file) : null;
        return $this->successResponse(null, compact('student'));
    }

    public function semesterStatus(Student $student)
    {
        $query = StudentCollegeActivity::where(['student_id' => $student->id])->with(['academicPeriod', 'studentStatus']);
        return DataTables::of($query->get())->make();
    }
    public function studentScore(Student $student, Request $request)
    {
        $query = Score::where(['student_id' => $student->id, 'is_publish' => true])->with('collegeClass.course');
        if ($request->has('academic_period_id') && $request->academic_period_id != null && $request->academic_period_id != 'all') {
            $query->whereHas('collegeClass', function ($q) use ($request) {
                $q->where('academic_period_id', $request->academic_period_id);
            });
        }
        return DataTables::of($query)->make();
    }
    public function studentAchievement(Student $student)
    {
        $query = Achievement::with(['achievementGroup', 'student'])->where(['student_id' => $student->id])->get();
        return DataTables::of($query)->make();
    }
    public function studentKrs(Request $request, Student $student)
    {

        $query = CollegeClass::whereHas('classParticipant', function ($q) use ($student) {
            $q->where('student_id', $student->id);
        })->with(['course', 'academicPeriod', 'teachingLecturer.employee']);
        if (!empty($request->academic_period_id) and $request->academic_period_id != '' and $request->academic_period_id != 'all') {
            $query->where('academic_period_id', $request->academic_period_id);
        }
        $results = $query->get();
        return DataTables::of($results)->make();
    }

    public function studentKhs(Request $request, Student $student)
    {
        $total_k = 0;
        $total_m = 0;
        $total_k_ = 0;
        $total_m_ = 0;

        try {
            $query  = DB::table('scores')
                ->join('college_classes', 'college_classes.id', '=', 'scores.college_class_id')
                ->join('courses', 'courses.id', '=', 'college_classes.course_id')->where('scores.student_id', '=', $student->id)
                ->select(['courses.name', 'courses.code', 'scores.index_score', 'scores.final_grade', 'college_classes.credit_total', 'college_classes.academic_period_id'])
                ->get();

            $queryConvertion = DB::table('activity_score_conversions')
            ->join('student_activities', 'student_activities.id', '=', 'activity_score_conversions.student_activity_id')
            ->join('courses', 'courses.id', '=', 'activity_score_conversions.course_id')
            ->join('student_activity_members', 'student_activity_members.id', '=', 'activity_score_conversions.student_activity_member_id')
            ->where('activity_score_conversions.is_transcript', true)
            ->where('student_activity_members.student_id', $student->id)
            ->select(['courses.name', 'courses.code', 'activity_score_conversions.index_score', 'activity_score_conversions.grade as final_grade', 'activity_score_conversions.credit as credit_total',  'student_activities.academic_period_id'])
            ->get();

            if (!empty($request->academic_period_id) and $request->academic_period_id != '' and  $request->academic_period_id != 'all') {
                $query  = DB::table('scores')
                    ->join('college_classes', 'college_classes.id', '=', 'scores.college_class_id')
                    ->join('courses', 'courses.id', '=', 'college_classes.course_id')->where('scores.student_id', '=', $student->id)->where('college_classes.academic_period_id', $request->academic_period_id)
                    ->select(['courses.name', 'courses.code', 'scores.index_score', 'scores.final_grade', 'college_classes.credit_total', 'college_classes.academic_period_id'])
                    ->get();

                $queryConvertion = DB::table('activity_score_conversions')
                    ->join('student_activities', 'student_activities.id', '=', 'activity_score_conversions.student_activity_id')
                    ->join('courses', 'courses.id', '=', 'activity_score_conversions.course_id')
                    ->join('student_activity_members', 'student_activity_members.id', '=', 'activity_score_conversions.student_activity_member_id')
                    ->where('student_activities.academic_period_id', $request->academic_period_id)
                    ->where('student_activity_members.student_id', $student->id)
                    ->where('activity_score_conversions.is_transcript', true)
                    ->select(['courses.name', 'courses.code', 'activity_score_conversions.index_score', 'activity_score_conversions.grade as final_grade', 'activity_score_conversions.credit as credit_total',  'student_activities.academic_period_id'])
                    ->get();
            }
            $dataQuery = [];
            foreach ($query as $key => $value) {
                $dataQuery[] = [
                    'name' => $value->name,
                    'code' => $value->code,
                    'index_score' => $value->index_score,
                    'final_grade' => $value->final_grade,
                    'credit_total' => $value->credit_total,
                    'academic_period_id' => $value->academic_period_id,
                ];
                $total_k += $value->credit_total;
                $total_m += $value->index_score * $value->credit_total;
            }
            $dataQuery_ = [];
            foreach ($queryConvertion as $key => $value) {
                $dataQuery_[] = [
                    'name' => $value->name,
                    'code' => $value->code,
                    'index_score' => $value->index_score,
                    'final_grade' => $value->final_grade,
                    'credit_total' => $value->credit_total,
                    'academic_period_id' => $value->academic_period_id,
                ];
                $total_k_ += $value->credit_total;
                $total_m_ += $value->index_score * $value->credit_total;
            }
            $dataValue = array_merge($dataQuery, $dataQuery_);
            $data = [
                'khs' => $dataValue,
                'total_credit' => ($total_k + $total_k_),
                'total_indexscore_credit' => floor($total_m + $total_m_),
            ];
            return $this->successResponse(null, compact('data'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function studentTranscript(Student $student)
    {
        $courseTranscripts = [];
        $total_credit = 0;
        $total_sks_index = 0;
        $courseConvert = [];
        $total_credit_ = 0;
        $total_sks_index_ = 0;
        try {
            $query = Transcript::with(['course'])
                ->where('student_id', $student->id)->get();
            $queryConvert = ActivityScoreConversion::with(['studentActivityMember', 'studentActivity', 'course'])->whereHas('studentActivityMember', function($q)use($student){
                $q->where('student_id', $student->id);
            })->get();
            foreach ($query as $key => $value) {

                $total_credit += $value->credit;
                $total_sks_index += round($value->credit *  $value->index_score);

                $courseTranscripts[] = [
                    'course_code' => $value->course->code,
                    'credit' => $value->credit,
                    'course_name' => $value->course->name,
                    'grade' => $value->grade,
                    'index' => $value->index_score,
                ];
            }
            foreach ($queryConvert as $key => $value) {

                $total_credit_ += $value->credit;
                $total_sks_index_ += round($value->credit *  $value->index_score);

                $courseConvert[] = [
                    'course_code' => $value->course->code,
                    'credit' => $value->credit,
                    'course_name' => $value->course->name,
                    'grade' => $value->grade,
                    'index' => $value->index_score,
                ];
            }
            $merge = array_merge($courseTranscripts, $courseConvert);
            $data = [
                'courseTranscripts' => $merge,
                'total_credit' => ($total_credit + $total_credit_),
                'total_sks_index' => ($total_sks_index + $total_sks_index_),
            ];
            return $this->successResponse(null, compact('data'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function studentScoreConversion(Student $student, Request $request)
    {

        $query = ActivityScoreConversion::whereHas('studentActivityMember', function ($q) use ($student) {
            $q->where('student_id', $student->id);
        })->with('course', 'studentActivityMember', 'studentActivity.studentActivityCategory', 'studentActivity.academicPeriod');
        if ($request->has('academic_period_id') && $request->academic_period_id != null && $request->academic_period_id != 'all') {
            $query->whereHas('studentActivity', function ($q) use ($request) {
                $q->where('academic_period_id', $request->academic_period_id);
            });
        }
        return DataTables::of($query)->make();
    }

    public function studentCurriculum(Student $student)
    {
        try {
            $courseCurriculums = [];
            $semesters = [];
            $query = CourseCurriculum::where('curriculum_id', $student->curriculum_id)->with(['course'])->orderBy('semester', 'asc')->get();
            foreach ($query as $key => $value) {
                $courseCurriculums[] = [
                    'course' => $value->course->code . ' - ' . $value->course->name,
                    'credit' => $value->credit_total,
                    'is_mandatory' => $value->is_mandatory,
                    'semester' => $value->semester
                ];
                if (!in_array($value->semester, $semesters)) {
                    $semesters[] = $value->semester;
                }
            }
            $data = [
                'courses' => $courseCurriculums,
                'semester' => $semesters
            ];
            return $this->successResponse(null, compact('data'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function setRegistrationPath(Request $request)
    {
        try {
            $data = $request->all();
            if (count($data['student']) > 0) {
                foreach ($data['student'] as $item => $v) {
                    Student::where('id', $data['student'][$item])->update([
                        'registration_path_id' => $request->registration_path,
                    ]);
                }
            }
            return $this->successResponse('Data berhasil di set');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function setRegistrationType(Request $request)
    {
        try {
            $data = $request->all();
            if (count($data['student']) > 0) {
                foreach ($data['student'] as $item => $v) {
                    Student::where('id', $data['student'][$item])->update([
                        'registration_type_id' => $request->registration_type,
                    ]);
                }
            }
            return $this->successResponse('Data berhasil di set');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function setStudentEntryDate(Request $request)
    {
        try {
            $data = $request->all();
            if (count($data['student']) > 0) {
                foreach ($data['student'] as $item => $v) {
                    Student::where('id', $data['student'][$item])->update([
                        'entry_date' => $request->date
                    ]);
                }
            }
            return $this->successResponse('Data berhasil di set');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function setLectureSystem(Request $request)
    {
        try {
            $data = $request->all();
            if (count($data['student']) > 0) {
                foreach ($data['student'] as $item => $v) {
                    Student::where('id', $data['student'][$item])->update([
                        'lecture_system_id' => (Hashids::decode($request->lecture_system)[0])
                    ]);
                }
            }
            return $this->successResponse('Data berhasil di set');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function setClassGroup(Request $request)
    {
        try {
            $data = $request->all();
            if (count($data['student']) > 0) {
                foreach ($data['student'] as $item => $v) {
                    Student::where('id', $data['student'][$item])->update([
                        'class_group_id' => (Hashids::decode($request->class_group)[0]),
                    ]);
                }
            }
            return $this->successResponse('Data berhasil di set');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function setCurriculum(Request $request)
    {
        try {
            $data = $request->all();
            if (count($data['student']) > 0) {
                foreach ($data['student'] as $item => $v) {
                    Student::where('id', $data['student'][$item])->update([
                        'curriculum_id' => ($request->curriculum),
                    ]);
                }
            }
            return $this->successResponse('Data berhasil di set');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function store(CollegeStudentRequest $request)
    {
        try {

            $pictureName = 'student_default_pic.jpg';

            if ($request->hasFile('photo_student')) {
                $pictureName = uniqid('mahasiswa-') . '.' . $request->file('photo_student')->getClientOriginalExtension();
                $request->file('photo_student')->move(public_path(self::STUDENT_PICTURE_PATH), $pictureName);
            }
            $diploma_file = null;

            if ($request->hasFile('diploma_file')) {
                $diploma_file = uniqid('diploma-') . '.' . $request->file('diploma_file')->getClientOriginalExtension();
                $request->file('diploma_file')->move(public_path(self::STUDENT_PICTURE_PATH), $diploma_file);
            }

            $request->merge([
                'picture' => $pictureName,
                'street' => $request->jalan,
                'diploma' => $diploma_file
            ]);

            // return response()->json(['message' => $request->school_region], 500);

            $data_create = [
                'id' => Uuid::uuid4(),
                'picture' => $request->picture,
                'nim' => $request->nim,
                'name' => $request->name_student,
                'study_program_id' => $request->study_program,
                // 'academic_year_id' => $request->academic_year,
                'academic_period_id' => $request->academic_year,
                'lecture_system_id' => isset($request->lecture_system) ? Hashids::decode($request->lecture_system)[0] : null,
                'student_status_id' => $request->student_status,
                'class_group_id' => $request->class_group,
                'registration_type_id' => $request->registration_type,
                'registration_path_id' => $request->registration_path,
                'entry_date' => $request->date,
                'is_valid' => ($request->is_valid ?? 0),
                'gender' => $request->gender,
                'weight_body' => $request->weight,
                'height_body' => $request->height,
                'blood' => $request->blood,
                'birthplace' => $request->birth_place,
                'birthdate' => $request->birth_date,
                'religion_id' => isset($request->religion) ? Hashids::decode($request->religion)[0] : null,
                'ethnic_id' => isset($request->ethnics) ? Hashids::decode($request->ethnics)[0] : null,
                'passport' => $request->passpor,
                'kk' => $request->no_kk,
                'kps_number' => $request->kps_number,
                'nik' => $request->number_id,
                'marital_status' => $request->marital,
                'jacket_size' => $request->jacket_size,
                'phone_number' => $request->phone_number,
                'house_phone_number' => $request->house_phone_number,
                'email' => $request->personal_email,
                'campus_email' => $request->campus_email,
                'profession_id' => isset($request->student_profession) ? Hashids::decode($request->student_profession)[0] : null,
                'income_id' => isset($request->student_income) ? Hashids::decode($request->student_income)[0] : null,
                'street' => $request->street,
                'village_lev_2' => $request->village_lev_2,
                'neighbourhood' => $request->neighborhood,
                'hamlet' => $request->hamlet,
                'village_lev_1' => $request->village,
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'region_id' => isset($request->subdistricts) ? Hashids::decode($request->subdistricts)[0] : null,
                // 'district_id' => null,
                // 'provinces_id'  => null,
                'country_id' => $request->countries,
                'father_nik' => $request->nik_father,
                'father_name' => $request->father_name,
                'father_birthplace' => $request->father_birth_place,
                'father_birthdate' => $request->father_birth_date,
                'father_education_id' => $request->father_education_id,
                'father_address' => $request->father_address,
                'father_life_status' => $request->father_life_status,
                'father_relationship_status' => $request->father_relationship_status,
                'father_phone_number' => $request->father_phone_number,
                'father_email' => $request->father_email,
                'father_profession_id' => isset($request->father_profession) ? Hashids::decode($request->father_profession)[0] : null,
                'father_income_id' => isset($request->father_income) ? Hashids::decode($request->father_income)[0] : null,
                'mother_nik' => $request->mother_number_id_national,
                'mother_name' => $request->mother_name,
                'mother_birthplace' => $request->mother_birth_place,
                'mother_birthdate' => $request->mother_birth_date,
                'mother_education_id' => isset($request->mother_education) ? Hashids::decode($request->mother_education)[0] : null,
                'mother_address' => $request->mother_address,
                'mother_life_status' => $request->mother_life_status,
                'mother_relationship_status' => $request->mother_relationship_status,
                'mother_phone_number' => $request->mother_phone_number,
                'mother_email' => $request->mother_email,
                'mother_profession_id' => isset($request->mother_profession) ? Hashids::decode($request->mother_profession)[0] : null,
                'mother_income_id' => isset($request->mother_income) ? Hashids::decode($request->mother_income)[0] : null,
                'guardian_nik' => $request->nik_guardian,
                'guardian_name' => $request->guardian_name,
                'guardian_birthplace' => $request->guardian_birth_place,
                'guardian_birthdate' => $request->guardian_birth_date,
                'guardian_education_id' => isset($request->guardian_education) ? Hashids::decode($request->guardian_education)[0] : null,
                'guardian_address' => $request->guardian_address,
                'guardian_life_status' => $request->guardian_life_status,
                'guardian_relationship_status' => $request->guardian_relationship_status,
                'guardian_phone_number' => $request->guardian_phone_number,
                'guardian_email' => $request->guardian_email,
                'guardian_profession_id' => isset($request->guardian_profession) ? Hashids::decode($request->guardian_profession)[0] : null,
                'guardian_income_id' => isset($request->guardian_income) ? Hashids::decode($request->guardian_income)[0] : null,
                'school_name' => $request->school_name,
                'school_phone_number' => $request->school_phone_number,
                'school_address' => $request->school_address,
                'school_region_id' => null,
                'school_diploma_number' => $request->school_diploma_number,
                'diploma_file' => $request->diploma,
            ];


            Student::create($data_create);

            // return response()->json([
            //     'message' => 'berhasil',
            // ]);

            return $this->successResponse('Berhasil membuat data mahasiswa baru');
        } catch (Exception $e) {
            return response()->json([
                'trace' => $e->getTrace(),
                'message' => $e->getMessage(),
            ], 500);
            // return $this->exceptionResponse($e);
        }
    }
    public function destroy(Student $student)
    {
        try {

            $imageName = $student->picture;
            $diplomaFile = $student->diploma_file;

            $student->delete();

            if ($imageName != 'default_students_pic.jpg' && File::exists(public_path(self::STUDENT_PICTURE_PATH))) {
                File::delete(public_path(self::STUDENT_PICTURE_PATH) . $student->picture);
            }
            if ($diplomaFile != 'default_students_pic.jpg' && File::exists(public_path(self::STUDENT_PICTURE_PATH))) {
                File::delete(public_path(self::STUDENT_PICTURE_PATH) . $student->diploma_file);
            }

            return $this->successResponse('Berhasil menghapus data mahasiswa');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(Student $student, CollegeStudentRequest $request)
    {
        try {

            $imageName = $student->picture;
            $diplomaFile = $student->diploma_file;

            if ($imageName != 'default_students_pic.jpg' && File::exists(public_path(self::STUDENT_PICTURE_PATH))) {
                File::delete(public_path(self::STUDENT_PICTURE_PATH) . $student->picture);
            }
            if ($diplomaFile != 'default_students_pic.jpg' && File::exists(public_path(self::STUDENT_PICTURE_PATH))) {
                File::delete(public_path(self::STUDENT_PICTURE_PATH) . $student->diploma_file);
            }

            $pictureName = '';

            if ($request->hasFile('photo_student')) {
                $pictureName = uniqid('mahasiswa-') . '.' . $request->file('photo_student')->getClientOriginalExtension();
                $request->file('photo_student')->move(public_path(self::STUDENT_PICTURE_PATH), $pictureName);
            }
            $diploma_file = null;

            if ($request->hasFile('diploma_file')) {
                $diploma_file = uniqid('diploma-') . '.' . $request->file('diploma_file')->getClientOriginalExtension();
                $request->file('diploma_file')->move(public_path(self::STUDENT_PICTURE_PATH), $diploma_file);
            }

            $request->merge([
                'picture' => $imageName,
                'street' => $request->jalan,
                'diploma' => $diploma_file
            ]);

            $student->update(
                [
                    // 'id' => Uuid::uuid4(),
                    'picture' => $request->picture,
                    'nim' => $request->nim,
                    'name' => $request->name_student,
                    'study_program_id' => $request->study_program,
                    // 'academic_year_id' => $request->academic_year,
                    'academic_period_id' => $request->academic_year,
                    'lecture_system_id' => isset($request->lecture_system) ? Hashids::decode($request->lecture_system)[0] : null,
                    'student_status_id' => $request->student_status,
                    'class_group_id' => $request->class_group,
                    'registration_type_id' => $request->registration_type,
                    'registration_path_id' => $request->registration_path,
                    'entry_date' => $request->date,
                    'is_valid' => ($request->is_valid ?? 0),
                    'gender' => $request->gender,
                    'weight_body' => $request->weight,
                    'height_body' => $request->height,
                    'blood' => $request->blood,
                    'birthplace' => $request->birth_place,
                    'birthdate' => $request->birth_date,
                    'religion_id' => isset($request->religion) ? Hashids::decode($request->religion)[0] : null,
                    'ethnic_id' => isset($request->ethnics) ? Hashids::decode($request->ethnics)[0] : null,
                    'passport' => $request->passpor,
                    'kk' => $request->no_kk,
                    'kps_number' => $request->kps_number,
                    'nik' => $request->number_id,
                    'marital_status' => $request->marital,
                    'jacket_size' => $request->jacket_size,
                    'phone_number' => $request->phone_number,
                    'house_phone_number' => $request->house_phone_number,
                    'email' => $request->personal_email,
                    'campus_email' => $request->campus_email,
                    'profession_id' => isset($request->student_profession) ? Hashids::decode($request->student_profession)[0] : null,
                    'income_id' => isset($request->student_income) ? Hashids::decode($request->student_income)[0] : null,
                    'street' => $request->street,
                    'village_lev_2' => $request->village_lev_2,
                    'neighbourhood' => $request->neighborhood,
                    'hamlet' => $request->hamlet,
                    'village_lev_1' => $request->village,
                    'postal_code' => $request->postal_code,
                    'address' => $request->address,
                    'region_id' => null,
                    // 'district_id' => null,
                    // 'provinces_id'  => null,
                    'country_id' => $request->countries,
                    'father_nik' => $request->nik_father,
                    'father_name' => $request->father_name,
                    'father_birthplace' => $request->father_birth_place,
                    'father_birthdate' => $request->father_birth_date,
                    'father_education_id' => $request->father_education_id,
                    'father_address' => $request->father_address,
                    'father_life_status' => $request->father_life_status,
                    'father_relationship_status' => $request->father_relationship_status,
                    'father_phone_number' => $request->father_phone_number,
                    'father_email' => $request->father_email,
                    'father_profession_id' => isset($request->father_profession) ? Hashids::decode($request->father_profession)[0] : null,
                    'father_income_id' => isset($request->father_income) ? Hashids::decode($request->father_income)[0] : null,
                    'mother_nik' => $request->mother_number_id_national,
                    'mother_name' => $request->mother_name,
                    'mother_birthplace' => $request->mother_birth_place,
                    'mother_birthdate' => $request->mother_birth_date,
                    'mother_education_id' => isset($request->mother_education) ? Hashids::decode($request->mother_education)[0] : null,
                    'mother_address' => $request->mother_address,
                    'mother_life_status' => $request->mother_life_status,
                    'mother_relationship_status' => $request->mother_relationship_status,
                    'mother_phone_number' => $request->mother_phone_number,
                    'mother_email' => $request->mother_email,
                    'mother_profession_id' => isset($request->mother_profession) ? Hashids::decode($request->mother_profession)[0] : null,
                    'mother_income_id' => isset($request->mother_income) ? Hashids::decode($request->mother_income)[0] : null,
                    'guardian_nik' => $request->nik_guardian,
                    'guardian_name' => $request->guardian_name,
                    'guardian_birthplace' => $request->guardian_birth_place,
                    'guardian_birthdate' => $request->guardian_birth_date,
                    'guardian_education_id' => isset($request->guardian_education) ? Hashids::decode($request->guardian_education)[0] : null,
                    'guardian_address' => $request->guardian_address,
                    'guardian_life_status' => $request->guardian_life_status,
                    'guardian_relationship_status' => $request->guardian_relationship_status,
                    'guardian_phone_number' => $request->guardian_phone_number,
                    'guardian_email' => $request->guardian_email,
                    'guardian_profession_id' => isset($request->guardian_profession) ? Hashids::decode($request->guardian_profession)[0] : null,
                    'guardian_income_id' => isset($request->guardian_income) ? Hashids::decode($request->guardian_income)[0] : null,
                    'school_name' => $request->school_name,
                    'school_phone_number' => $request->school_phone_number,
                    'school_address' => $request->school_address,
                    'school_region_id' => null,
                    'school_diploma_number' => $request->school_diploma_number,
                    'diploma_file' => $request->diploma,
                ]
            );

            return $this->successResponse('Berhasil mengubah data mahasiswa');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }




    public function print(Request $request){
        $studyProgram = '';
        $classGroup = 'SEMUA GRUP KELAS';
        $academicPeriod = '';
        $registrationPath = 'SEMUA JALUR';
        $academicYear = '';
        $studentStatus = 'SEMUA STATUS';
        try{
            $query = Student::with(['studyProgram', 'academicPeriod', 'classGroup', 'registrationPath', 'studentStatus']);
            if ($request->has('study_program_id') && $request->study_program_id != '' && $request->study_program_id != 'all' ) {
                $query->where('study_program_id', $request->study_program_id);
                $studyProgram = StudyProgram::where('id', $request->study_program_id)->first()->educationLevel->code . '-' . StudyProgram::where('id', $request->study_program_id)->first()->name;
            }
            if ($request->has('academic_year_id') && $request->academic_year_id != '' && $request->academic_year_id != 'all' ) {
                $query->whereHas('academicPeriod', function ($q) use ($request) {
                    $q->where('academic_year_id', $request->academic_year_id);
                });
                $academicYear = AcademicYear::where('id', $request->academic_year_id)->first()->name;
            }
            if ($request->has('academic_period_id') && $request->academic_period_id != '' && $request->academic_period_id != 'all' ) {
                $query->where('academic_period_id', $request->academic_period_id);
                $academicPeriod = AcademicPeriod::where('id', $request->academic_period_id)->first()->name;
            }

            if ($request->has('class_group_id') && $request->class_group_id != '' && $request->class_group_id != 'all' ) {
                $class_group_id = Hashids::decode($request->class_group_id);
                $query->where('class_group_id', $class_group_id);
                $classGroup = ClassGroup::where('id', $class_group_id)->first()->name;
            }
            if ($request->has('registration_path_id') && $request->registration_path_id != '' && $request->registration_path_id != 'all' ) {
                $query->where('registration_path_id', $request->registration_path_id);
                $registrationPath = RegistrationPath::where('id', $request->registration_path_id)->first()->name;
            }
            if ($request->has('student_status_id') && $request->student_status_id != '' && $request->student_status_id != 'all' ) {
                $query->where('student_status_id', $request->student_status_id);
                $studentStatus = StudentStatus::where('id', $request->student_status_id)->first()->name;
            }
            $header = [
                'study_program' => $studyProgram,
                'academic_year' => $academicYear,
                'academic_period' => $academicPeriod,
                'class_group' => $classGroup,
                'registration_path' => $registrationPath,
                'student_status' => $studentStatus,
            ];
            return view('print.student-list', [
                'title' => 'Daftar Mahasiswa | Poliwangi',
                'header' => $header,
                'datas' => $query->get(),
                'universitasProfile' => UniversityProfile::first()
            ]);
        }catch(Exception $e){
            return abort(404);
        }

    }




    public function printTotalStudentStatus(Request $request)
    {
        try {
            $academicYear = 'Semua Angkatan';
            $studyProgram = 'Semua Program Studi';
            $studentStatus = 'Semua Status';


            $query = DB::table('students')
                ->join('study_programs', 'students.study_program_id', '=', 'study_programs.id')
                ->join('education_levels', 'study_programs.education_level_id', '=', 'education_levels.id')
                ->join('academic_periods', 'students.academic_period_id', '=', 'academic_periods.id')
                ->join('academic_years', 'academic_periods.academic_year_id', '=', 'academic_years.id')
                ->join('majors', 'study_programs.major_id', '=', 'majors.id')
                ->select(
                    DB::raw("CONCAT(education_levels.code, ' - ', study_programs.name) AS program_study"),
                    DB::raw("COUNT(students.id) AS Total"),
                    'study_programs.major_id',
                    // Tambahkan kolom major_id
                    'majors.name AS major_name',
                    'academic_periods.name AS academic_period',
                    'academic_years.name AS academic_year'
                )
                ->groupBy('study_programs.id', 'education_levels.code', 'study_programs.major_id', 'majors.id', 'academic_periods.id', 'academic_years.id')
                ->orderBy('academic_years.id', 'asc');



            if ($request->has('major_id') && $request->major_id != '' && $request->major_id != 'all') {
                $query->where('study_programs.major_id', $request->major_id);
                $studyProgram = Major::where('id', $request->major_id)->first()->name;
            }


            if ($request->has('student_status_id') && $request->student_status_id != null && $request->student_status_id != 'all') {
                $query->where('students.student_status_id', $request->student_status_id);
                $studentStatus = StudentStatus::where('id', $request->student_status_id)->first()->name;
            }

            if ($request->has('academic_year_id') && $request->academic_year_id != '' && $request->academic_year_id != 'all') {
                if ($request->has('academic_year_id2') && $request->academic_year_id2 != '' && $request->academic_year_id2 != 'all') {
                    $query->whereBetween('academic_periods.academic_year_id', [$request->academic_year_id, $request->academic_year_id2]);
                    $academicYear = AcademicYear::where('id', $request->academic_year_id)->first()->name . ' - ' . AcademicYear::where('id', $request->academic_year_id2)->first()->name;
                } else {
                    $query->where('academic_periods.academic_year_id', $request->academic_year_id);
                    $academicYear = AcademicYear::where('id', $request->academic_year_id)->first()->name;
                }
            }

            $header = [
                'academicYear' => $academicYear,
                'studyProgram' => $studyProgram,
                'studentStatus' => $studentStatus
            ];
            return view('print.total-students-status', [
                'title' => 'Laporan Mahasiswa Per Status',
                'data' => $query->get(),
                'header' => $header,
                'universitasProfile' => UniversityProfile::first()
            ]);
        } catch (Exception $e) {
            return abort(404);
        }
    }

    public function printStudentStatus(Request $request)
    {

        try {


            $studyProgram = 'SEMUA PROGRAM STUDI';
            $StudetStatus = 'SEMUA STATUS MAHASISWA';
            $academicYear = 'SEMUA ANGKATAN';
            $academicPeriod = null;

            $query = DB::table('student_college_activities')
                ->join('student_statuses', 'student_college_activities.student_status_id', '=', 'student_statuses.id')
                ->join('students', 'student_college_activities.student_id', '=', 'students.id')
                ->join('academic_periods as ac', 'student_college_activities.academic_period_id', '=', 'ac.id')
                ->join('academic_periods as at', 'students.academic_period_id', '=', 'at.id')
                ->join('study_programs as sp', 'students.study_program_id', '=', 'sp.id')
                ->join('education_levels as el', 'sp.education_level_id', '=', 'el.id')
                ->join('academic_years as ay', 'at.academic_year_id', '=', 'ay.id')
                ->groupBy('student_statuses.id', 'ay.id', 'sp.id', 'el.id', 'ac.id')
                ->select(DB::raw('COUNT(student_college_activities.id) as count'), 'student_statuses.name as status_name', 'ay.name as academic_years', DB::raw("CONCAT(el.code, ' - ', sp.name) as study_program"), 'ac.name as academic_period');

            if ($request->has('academic_period_id') && $request->academic_period_id != null) {
                $query->where('ac.id', $request->academic_period_id);
                $academicPeriod = AcademicPeriod::where('id', $request->academic_period_id)->first()->name;
            }

            if ($request->has('academic_year_id') && $request->academic_year_id != null) {
                $query->where('ay.id', $request->academic_year_id);
                $academicYear = AcademicYear::where('id', $request->academic_year_id)->first()->name;
            }

            if ($request->has('study_program_id') && $request->study_program_id != null) {
                $query->where('sp.id', $request->study_program_id);
                $studyProgram = StudyProgram::where('id', $request->study_program_id)->first()->name;
            }
            if ($request->has('student_status_id') && $request->student_status_id != null) {
                $query->where('student_statuses.id', $request->student_status_id);
                $StudetStatus = StudentStatus::where('id', $request->student_status_id)->first()->name;
            }

            return view('print.student-status', [
                'title' => 'Laporan Status Mahasiswa | Poliwangi',
                'datas' => $query->get(),
                'universitasProfile' => UniversityProfile::first(),
                'header' => [
                    'studyProgram' => $studyProgram,
                    'academicYear' => $academicYear,
                    'academicPeriod' => $academicPeriod,
                    'studentStatus' => $StudetStatus
                ]
            ]);
        } catch (Exception $e) {
            return abort(404);
        }
    }




    public function printStudentTranscript(Request $request)
    {
        try {

            $query = Transcript::with(['student', 'course', 'college_class']);

            if ($request->has('study_program_id')) {
                $query->whereHas('student', function ($q)  use ($request) {
                    $q->where('study_program_id', $request->study_program_id);
                });
            }

            if ($request->has('class_group_id')) {
                $query->whereHas('student', function ($q)  use ($request) {
                    $q->where('class_group_id', Hashids::decode($request->class_group_id));
                });
            }



            $data = [];
            $credit = 0;
            $mutu = 0;
            $nama_ta = '';
            $nama_ta_inggris = '';

            if ($request->has('student_id') && $request->student_id != null) {

                $mahasiswa = Student::where('id', $request->student_id)->first();
                $grades = $query->where('student_id', $request->student_id)->get();
                foreach ($grades as $nilai) {
                    $credit += $nilai->credit;
                    $mutu += round($nilai->credit * $nilai->index_score);
                }

                $thesis = Thesis::where('student_id', $request->student_id)->get();
                foreach ($thesis as $ta) {
                   $nama_ta = $ta->name;
                   $nama_ta_inggris = $ta->name_en;
                }
                $mahasiswaData = [
                    'biodata' => [
                        'nama' => $mahasiswa->name,
                        'nim' => $mahasiswa->nim,
                        'tempat_tanggal_lahir' => $mahasiswa->birthplace . ', ' . $mahasiswa->birthdate,
                        'studi_program' => $mahasiswa->studyProgram->name,
                        'program' => $mahasiswa->studyProgram->educationLevel->code,
                        'kredit' => $credit,
                        'ipk' => number_format($mutu / $credit, 2) ,
                        'ta' => $nama_ta,
                        'ta_en' => $nama_ta_inggris
                    ],
                    'nilai' => [],
                ];


                foreach ($grades as $nilai) {
                    $mahasiswaData['nilai'][] = [
                        'kode' => $nilai->course->code,
                        'sks' => $nilai->credit,
                        'matakuliah' => $nilai->course->name,
                        'matakuliah_inggris' => $nilai->course->name_en,
                        'nhu' => $nilai->grade,
                        'am' => $nilai->index_score,
                    ];
                }

                $data[] = $mahasiswaData;
            } else {
                $mahasiswa = Student::where('class_group_id', Hashids::decode($request->class_group_id))->get();
                foreach ($mahasiswa as $value) {


                    $grades = $query->where('student_id', $value->id)->get();
                    foreach ($grades as $nilai) {
                        $credit += $nilai->credit;
                        $mutu += round($nilai->credit * $nilai->index_score);
                    }

                    $thesis = Thesis::where('student_id', $value->id)->get();
                    foreach ($thesis as $ta) {
                       $nama_ta = $ta->name;
                       $nama_ta_inggris = $ta->name_en;
                    }

                    $mahasiswaData = [
                        'biodata' => [
                            'nama' => $value->name,
                            'nim' => $value->nim,
                            'tempat_tanggal_lahir' => $value->birthplace . ', ' . $value->birthdate,
                            'studi_program' => $value->studyProgram->name,
                            'program' => $value->studyProgram->educationLevel->code,
                            'kredit' => $credit,
                            'ipk' => number_format($mutu / $credit, 2) ,
                            'ta' => $nama_ta,
                            'ta_en' => $nama_ta_inggris
                        ],
                        'nilai' => [],
                    ];

                    foreach ($grades as $nilai) {
                        $mahasiswaData['nilai'][] = [
                            'kode' => $nilai->course->code,
                            'sks' => $nilai->credit,
                            'matakuliah' => $nilai->course->name,
                            'matakuliah_inggris' => $nilai->course->name_en,
                            'nhu' => $nilai->grade,
                            'am' => $nilai->index_score,
                        ];
                    }

                    $data[] = $mahasiswaData;
                    $query = $query->getModel()->newQuery();
                }
            }
            return view('print.student-transcript', [
                'title' => 'Transkip Mahasiswa | Poliwangi',
                'data' => $data,
                'profile_univ' => UniversityProfile::first(),
                'data_wakil' => UniversityProfile::with('viceChancellor')->get(),
            ]);

        } catch (Exception $e) {
            return  abort(404);
        }
    }



    public function printStudentSemester(Request $request)
    {

        $studyProgram = 'Semua Program Studi';
        $academicYear = 'Semua Angkatan';
        $academicPeriod = 'Semua Periode Akademik';
        try {
            $query = StudentCollegeActivity::with(['student', 'studentStatus', 'academicPeriod', 'student.studyProgram', 'student.academicPeriod.academicYear']);

            if ($request->has('academic_period_id') && $request->academic_period_id != null) {
                $query->where('academic_period_id', $request->academic_period_id);
                $academicPeriod = AcademicPeriod::find($request->academic_period_id)->name;
            }

            if ($request->has('study_program_id') && $request->study_program_id != null) {
                $query->whereHas('student', function ($q) use ($request) {
                    $q->where('study_program_id', $request->study_program_id);
                });
                $studyProgram = StudyProgram::find($request->study_program_id)->educationLevel->code . ' - ' . StudyProgram::find($request->study_program_id)->name;
            }

            if ($request->has('academic_year_id') && $request->academic_year_id != null) {
                $query->whereHas('student', function ($q) use ($request) {
                    $q->whereHas('academicPeriod', function ($q) use ($request) {
                        $q->where('academic_year_id', $request->academic_year_id);
                    });
                });
                $academicYear = AcademicYear::find($request->academic_year_id)->name;
            }
            if ($request->has('student_status_id') && $request->student_status_id != null) {
                if ($request->student_status_id == 'NA') {
                    $query->whereNotIn('academic_period_id', [$request->academic_period_id]);
                }
                $query->where('student_status_id', $request->student_status_id);
            }

            if ($request->has('student_total') && $request->student_total != null) {
                $query->limit($request->student_total);
            }

            return view('print.student-semester-status', [
                'title' => 'Laporan Status Semester Mahasiswa',
                'error' => false,
                'universitasProfile' => UniversityProfile::first(),
                'datas' => $query->get(),
                'header' => [
                    'academicYear' => $academicYear,
                    'studyProgram' => $studyProgram,
                    'academicPeriod' => $academicPeriod
                ]
            ]);
        } catch (Exception $e) {
            return view('print.student-semester-status', [
                'title' => 'Laporan Status Semester Mahasiswa',
                'error' => true,
                'universitasProfile' => UniversityProfile::first(),
            ]);
        }
    }


    public function downloadTemplateImport()
    {

        try {
            return Excel::download(new StudentExcelExport, 'TemplateImportStudent.xlsx');
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function importDataStudent(Request $request)
    {
        try{

            $file = $request->file('file_import');

            // membuat nama file unik
            $name_file = $file->hashName();

            //temporary file
            $path = $file->storeAs('public/excel/',$name_file);

            $res = Excel::import(new StudentImport, storage_path('app/public/excel/'.$name_file));

            //remove from server
            Storage::delete($path);


            return $this->successResponse('Berhasil Import Data');
        }catch(Exception $e){
            return response()->json([
                'message' => $e->getMessage(),
            ],500);
            // return $this->exceptionResponse([$e->getMessage()]);
        }
    }

}
