<?php

namespace App\Http\Controllers\Api\General;

use App\Http\Controllers\Controller;
use App\Models\AcademicActivity;
use App\Models\AcademicCalendar;
use App\Models\AcademicPeriod;
use App\Models\AcademicYear;
use App\Models\AchievementField;
use App\Models\AchievementGroup;
use App\Models\AchievementLevel;
use App\Models\AchievementType;
use App\Models\Agency;
use App\Models\ClassSchedule;
use App\Models\CollegeClass;
use App\Models\Curriculum;
use App\Models\Country;
use App\Models\Course;
use App\Models\CourseCurriculum;
use App\Models\CourseGroup;
use App\Models\CourseType;
use App\Models\Day;
use App\Models\EducationLevel;
use App\Models\Employee;
use App\Models\EmployeeActiveStatus;
use App\Models\EmployeeStatus;
use App\Models\ClassGroup;
use App\Models\Disability;
use App\Models\EmployeeType;
use App\Models\Ethnic;
use App\Models\EvaluationType;
use App\Models\Income;
use App\Models\JudicialPeriod;
use App\Models\LectureSystem;
use App\Models\Major;
use App\Models\MeetingType;
use App\Models\Profession;
use App\Models\Region;
use App\Models\RegistrationType;
use App\Models\RegistrationPath;
use App\Models\Religion;
use App\Models\Role;
use App\Models\Room;
use App\Models\StudentStatus;
use App\Models\ScientificField;
use App\Models\ScholarshipType;
use App\Models\ScoreScale;
use App\Models\Student;
use App\Models\StudentActivityCategory;
use App\Models\StudyProgram;
use App\Models\TeachingLecturer;
use App\Models\ThesisStage;
use App\Models\TimeSlot;
use App\Models\University;
use App\Models\User;
use App\Models\WeeklySchedule;
use Carbon\Carbon;
use Exception;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GeneralController extends Controller
{
    public function academicYears(Request $request, $query = [], $is_array = false)
    {
        if ($is_array) {
            $academicYears = AcademicYear::orderBy('id', 'desc')->get();
            return $academicYears;
        } else {
            $academicYears = AcademicYear::orderBy('id', 'desc')->get();
            return $this->successResponse(null, compact('academicYears'));
        }
    }
    public function getEducationLevels(Request $request)
    {
        try {
            $allowFields = ['is_college'];
            $educationLevels = filterQuery(EducationLevel::query(), $request, $allowFields);
            $educationLevels = $educationLevels->get();
            return $this->successResponse(null, compact('educationLevels'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function academicPeriods(Request $request, $query = [], $is_array = false)
    {
        try {
            $allowFields = ['is_active', 'academic_year_id', 'is_use'];
            if ($is_array) {
                if (isset($query['academic_year_id']) && $query['academic_year_id'] != '') {
                    $query['academic_year_id'] = Hashids::decode($query['academic_year_id'])[0];
                }
                $academicPeriods = filterQuery(AcademicPeriod::query(), $query, $allowFields, true);
                $academicPeriods = $academicPeriods->orderBy('id', 'desc')->get();
                return $academicPeriods;
            } else {
                $academicPeriods = filterQuery(AcademicPeriod::query(), $request, $allowFields);
                $academicPeriods = $academicPeriods->orderBy('id', 'desc')->get();
                return $this->successResponse(null, compact('academicPeriods'));
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function getAgencies(Request $request)
    {
        $agencies = Agency::all();
        return $this->successResponse(null, compact('agencies'));
    }
    public function getReligions(Request $request)
    {
        $religions = Religion::all();
        return $this->successResponse(null, compact('religions'));
    }

    public function religions(Request $request)
    {
        try {
            $allowFields = [''];
            $religions = filterQuery(Religion::query(), $request, $allowFields);
            $religions = $religions->get();
            return $this->successResponse(null, compact('religions'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function studyPrograms(Request $request, $query = [], $is_array = false)
    {
        try {
            $allowFields = ['education_level_id', 'major_id'];
            if ($is_array) {
                $studyPrograms = filterQuery(StudyProgram::with(['educationLevel']), $query, $allowFields, true);
                $studyPrograms = $studyPrograms->with('educationLevel');
                if (mappingAccess() != null) {
                    $studyPrograms = $studyPrograms->whereIn('id', mappingAccess());
                }
                $studyPrograms = $studyPrograms->get();
                return $studyPrograms;
            } else {
                $studyPrograms = filterQuery(StudyProgram::with(['educationLevel']), $request, $allowFields);
                if (mappingAccess() != null) {
                    $studyPrograms = $studyPrograms->whereIn('id', mappingAccess());
                }
                $studyPrograms = $studyPrograms->get();
                return $this->successResponse(null, compact('studyPrograms'));
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function getMajors(Request $request)
    {
        try {
            $allowFields = ['is_active'];
            $majors = filterQuery(Major::query(), $request, $allowFields);
            if (mappingAccess() != null) {
                $majors = $majors->whereHas('studyProgram', function ($q) {
                    $q->whereIn('id', mappingAccess());
                });
            }
            $majors = $majors->get();
            return $this->successResponse(null, compact('majors'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function getStudentActivityCategories(Request $request)
    {
        try {
            $allowFields = ['is_default', 'is_mbkm'];
            $studentActivityCategories = filterQuery(StudentActivityCategory::query(), $request, $allowFields);
            $studentActivityCategories = $studentActivityCategories->get();
            return $this->successResponse(null, compact('studentActivityCategories'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getRegions(Request $request)
    {
        try {
            $allowFields = ['level', 'parent'];
            if ($request->has('parent') && $request->parent != '') {
                $request->merge(['parent' => Hashids::decode($request->parent)[0]]);
            }
            $regions = filterQuery(Region::query(), $request, $allowFields);
            $regions = $regions->get();
            return $this->successResponse(null, compact('regions'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function getAcademicActivities(Request $request)
    {
        try {
            $academicActivities = AcademicActivity::all();
            return $this->successResponse(null, compact('academicActivities'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function getUsers(Request $request)
    {
        try {
            $allowFields = ['unitable_type', 'unitable_id', 'userable_type', 'userable_id'];
            $users = filterQuery(User::whereIsActive(true)->whereHas('roles', function ($q) {
                $q->whereNotIn('name', ['Developer', 'Default']);
            }), $request, $allowFields);
            $users = $users->get();
            return $this->successResponse(null, compact('users'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function getRoles(Request $request)
    {
        try {
            $allowFields = ['is_default'];
            $roles = filterQuery(Role::whereNotIn('name', ['Developer', 'Default']), $request, $allowFields);
            $roles = $roles->get();
            return $this->successResponse(null, compact('roles'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function getEmployees(Request $request, $query = [], $is_array = false)
    {
        try {
            $allowFields = [];
            if($is_array) {
                $employees = filterQuery(Employee::query(), $query, $allowFields, true);
                $employees = $employees->get();
                return $employees;
            } else {
                $employees = filterQuery(Employee::query(), $request, $allowFields);
                $employees = $employees->get();
                return $this->successResponse(null, compact('employees'));
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getRPSEmployees(Request $request)
    {
        try {
            $allowFields = [];
            $employees = filterQuery(Employee::where('is_rps', true), $request, $allowFields);
            $employees = $employees->get();
            return $this->successResponse(null, compact('employees'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getEmployeeTypes(Request $request)
    {
        try {
            $allowFields = [];
            $employeeTypes = filterQuery(EmployeeType::query(), $request, $allowFields);
            $employeeTypes = $employeeTypes->get();
            return $this->successResponse(null, compact('employeeTypes'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getEmployeeStatuses(Request $request)
    {
        try {
            $allowFields = [];
            $employeeStatuses = filterQuery(EmployeeStatus::query(), $request, $allowFields);
            $employeeStatuses = $employeeStatuses->get();
            return $this->successResponse(null, compact('employeeStatuses'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }


    public function studentStatuses(Request $request, $query = [], $is_array = false)
    {
        try {
            $allowFields = ['is_submited', 'is_active', 'is_college'];
            if ($is_array) {
                // return response()->json([
                //     's' => $query
                // ]);
                $studentStatuses = filterQuery(StudentStatus::query(), $query, $allowFields, true);
                $studentStatuses = $studentStatuses->get();
                return $studentStatuses;
            } else {
                $studentStatuses = filterQuery(StudentStatus::query(), $request, $allowFields);
                $studentStatuses = $studentStatuses->get();
                return $this->successResponse(null, compact('studentStatuses'));
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getStudents(Request $request)
    {
        try {
            $request->merge(['class_group_id' => Hashids::decode($request->class_group_id)]);
            $allowFields = ['name', 'student_status_id', 'nim', 'study_program_id', 'class_group_id'];
            $students = filterQuery(Student::query(), $request, $allowFields);
            if (mappingAccess() != null) {
                $students = $students->whereIn('study_program_id', mappingAccess());
            }
            $students = $students->get();
            return $this->successResponse(null, compact('students'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getScientificFields(Request $request)
    {
        try {
            $allowFields = [];
            $scientificFields = filterQuery(ScientificField::query(), $request, $allowFields);
            $scientificFields = $scientificFields->get();
            return $this->successResponse(null, compact('scientificFields'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function lectureSystem(Request $request, $query = [], $is_array = false)
    {
        try {
            $allowFields = [];
            if ($is_array) {
                $lectureSystem = filterQuery(LectureSystem::query(), $query, $allowFields, true);
                $lectureSystem = $lectureSystem->get();
                return $lectureSystem;
            } else {
                $lectureSystem = filterQuery(LectureSystem::query(), $request, $allowFields);
                $lectureSystem = $lectureSystem->get();
                return $this->successResponse(null, compact('lectureSystem'));
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getAchievementFields(Request $request)
    {
        try {
            $allowFields = [];
            $achievementFields = filterQuery(AchievementField::query(), $request, $allowFields);
            $achievementFields = $achievementFields->get();
            return $this->successResponse(null, compact('achievementFields'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function registrationType(Request $request, $query = [], $is_array = false)
    {
        try {
            $allowFields = ['is_school_register'];
            if ($is_array) {
                $registrationType = filterQuery(RegistrationType::query(), $query, $allowFields, true);
                $registrationType = $registrationType->get();
                return $registrationType;
            } else {
                $registrationType = filterQuery(RegistrationType::query(), $request, $allowFields);
                $registrationType = $registrationType->get();
                return $this->successResponse(null, compact('registrationType'));
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function registrationPath(Request $request, $query = [], $is_array = false)
    {
        try{
            $allowFields = [];
            if ($is_array) {
                $registrationPath = filterQuery(RegistrationPath::query(), $query, $allowFields, true);
                $registrationPath = $registrationPath->get();
                return $registrationPath;
            } else {
                $registrationPath = filterQuery(RegistrationPath::query(), $request, $allowFields);
                $registrationPath = $registrationPath->get();
                return $this->successResponse(null, compact('registrationPath'));
            }

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    public function getUniversities(Request $request)
    {
        try {
            $allowFields = [];
            $universities = filterQuery(University::query(), $request, $allowFields);
            $universities = $universities->get();
            return $this->successResponse(null, compact('universities'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function curriculums(Request $request, $query = [], $is_array = false)
    {
        try {
            $allowFields = ['study_program_id'];
            if ($is_array) {
                $curriculums = filterQuery(Curriculum::query(), $query, $allowFields, true);
                if (mappingAccess() != null) {
                    $curriculums = $curriculums->whereIn('study_program_id', mappingAccess());
                }
                $curriculums = $curriculums->get();
                return $curriculums;
            } else {
                $curriculums = filterQuery(Curriculum::query(), $request, $allowFields);
                if (mappingAccess() != null) {
                    $curriculums = $curriculums->whereIn('study_program_id', mappingAccess());
                }
                $curriculums = $curriculums->get();
                return $this->successResponse(null, compact('curriculums'));
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getAchievementTypes(Request $request)
    {
        try {
            $allowFields = [];
            $achievementTypes = filterQuery(AchievementType::query(), $request, $allowFields);
            $achievementTypes = $achievementTypes->get();
            return $this->successResponse(null, compact('achievementTypes'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getEmployeeActiveStatuses(Request $request)
    {
        try {
            $allowFields = [];
            $employeeActiveStatuses = filterQuery(EmployeeActiveStatus::query(), $request, $allowFields);
            $employeeActiveStatuses = $employeeActiveStatuses->get();
            return $this->successResponse(null, compact('employeeActiveStatuses'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getCountries(Request $request)
    {
        try {
            $allowFields = [];
            $countries = filterQuery(Country::query(), $request, $allowFields);
            $countries = $countries->get();
            return $this->successResponse(null, compact('countries'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getProvinces(Request $request)
    {
        try {
            $allowFields = [];
            $provinces = filterQuery(Region::where('level', 1), $request, $allowFields);
            $provinces = $provinces->get();
            return $this->successResponse(null, compact('provinces'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getProfessions(Request $request)
    {
        try {
            $allowFields = [];
            $professions = filterQuery(Profession::query(), $request, $allowFields);
            $professions = $professions->get();
            return $this->successResponse(null, compact('professions'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getScholarshipTypes(Request $request)
    {
        try {
            $allowFields = [];
            $scholarshipTypes = filterQuery(ScholarshipType::query(), $request, $allowFields);
            $scholarshipTypes = $scholarshipTypes->get();
            return $this->successResponse(null, compact('scholarshipTypes'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function getJudicialPeriods(Request $request)
    {
        try {
            $allowFields = ['academic_period_id'];
            $judicialPeriods = filterQuery(JudicialPeriod::query(), $request, $allowFields);
            $judicialPeriods = $judicialPeriods->get();
            return $this->successResponse(null, compact('judicialPeriods'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function getActiveScoreScales(Request $request)
    {
        try {
            $allowFields = ['study_program_id', 'is_score_def'];
            $scoreScales = filterQuery(ScoreScale::query(), $request, $allowFields);
            $today = Carbon::now();
            if (mappingAccess() != null) {
                $scoreScales = $scoreScales->whereIn('study_program_id', mappingAccess());
            }
            $scoreScales = $scoreScales->where('date_start', '<=', $today)->where('date_end', '>=', $today)->get();
            return $this->successResponse(null, compact('scoreScales'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function courses(Request $request, $query = [], $is_array = false)
    {
        try {
            $allowFields = ['is_mku', 'is_sap', 'is_silabus', 'is_bahan_ajar', 'is_diktat', 'study_program_id'];
            if ($is_array) {
                $courses = filterQuery(Course::query(), $query, $allowFields, true);
                if (mappingAccess() != null) {
                    $courses = $courses->whereIn('study_program_id', mappingAccess());
                }
                $courses = $courses->get();
                return $courses;
            } else {
                $courses = filterQuery(Course::query(), $request, $allowFields);
                if (mappingAccess() != null) {
                    $courses = $courses->whereIn('study_program_id', mappingAccess());
                }
                $courses = $courses->get();
                return $this->successResponse(null, compact('courses'));
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function courseCurriculums(Request $request, $query = [], $is_array = false)
    {
        try {
            $allowFields = [];
            if ($is_array) {
                if (isset($query['study_program_id'])) {
                    $courseCurriculums = CourseCurriculum::whereHas('curriculum', function ($q) use ($query) {
                        $q->where('study_program_id', $query['study_program_id']);
                    });

                    unset($query['study_program_id']);
                }
                $courseCurriculums = filterQuery($courseCurriculums->with(['course', 'curriculum']), $query, $allowFields, true);
                $courseCurriculums = $courseCurriculums->get();
                return $courseCurriculums;
            } else {
                $courseCurriculums = filterQuery(CourseCurriculum::query(), $request, $allowFields);
                $courseCurriculums = $courseCurriculums->get();
                return $courseCurriculums;
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function days(Request $request, $query = [], $is_array = false) {
        try {
            $allowFields = [];
            if($is_array) {
                $days = filterQuery(Day::query(), $query, $allowFields, true);
                $days = $days->get();
                return $days;
            }
        } catch(Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function courseTypes(Request $request, $query = [], $is_array = false)
    {
        try {
            $allowFields = [];
            if ($is_array) {
                $courseTypes = filterQuery(CourseType::query(), $query, $allowFields, true);
                $courseTypes = $courseTypes->get();
                return $courseTypes;
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function courseGroups(Request $request, $query = [], $is_array = false)
    {
        try {
            $allowFields = [];
            if ($is_array) {
                $courseGroups = filterQuery(CourseGroup::query(), $query, $allowFields, true);
                $courseGroups = $courseGroups->get();
                return $courseGroups;
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function scientificFields(Request $request, $query = [], $is_array = false)
    {
        try {
            $allowFields = [];
            if ($is_array) {
                $scientificFields = filterQuery(ScientificField::query(), $query, $allowFields, true);
                $scientificFields = $scientificFields->get();
                return $scientificFields;
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function teachingLecturers(Request $request, $query, $is_array = false)
    {
        try {
            $allowFields = ['college_class_id'];
            if ($is_array) {
                $teachingLecturers = filterQuery(TeachingLecturer::with('employee'), $query, $allowFields, true);
                $teachingLecturers = $teachingLecturers->get();
                return $teachingLecturers;
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getData(Request $request)
    {
        $result = [];

        foreach ($request->model as $key => $value) {
            $query = [];
            if ($request->has('where') && count($request->where) - 1 >= $key) {
                $query = $request->where[$key];
            }
            $result[$value] = $this->{$value}($request, $query, true);
        }
        return response()->json($result);
    }

    public function getRooms(Request $request, $query = [], $is_array = false)
    {
        try {
            $allowFields = ['unitable_type', 'unitable_id', 'type', 'is_active'];
            if($is_array) {
                $rooms = filterQuery(Room::query(), $query, $allowFields, true);
                $rooms = $rooms->get();
                return $rooms;
            } else {
                $rooms = filterQuery(Room::query(), $request, $allowFields);
                $rooms = $rooms->get();
                return $this->successResponse(null, compact('rooms'));
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getMeetingTypes(Request $request, $query = [], $is_array = false)
    {
        try {
            $allowFields = ['type', 'is_presence', 'is_exam'];
            if($is_array) {
                $meetingTypes = filterQuery(MeetingType::query(), $query, $allowFields, true);
                $meetingTypes = $meetingTypes->get();
                return $meetingTypes;
            } else {
                $meetingTypes = filterQuery(MeetingType::query(), $request, $allowFields);
                $meetingTypes = $meetingTypes->get();
                return $this->successResponse(null, compact('meetingTypes'));
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getTimeSlots(Request $request, $query = [], $is_array = false)
    {
        try {
            $allowFields = ['type'];
            if($is_array) {
                $timeSlots = filterQuery(TimeSlot::query(), $query, $allowFields, true);
                $timeSlots = $timeSlots->get();
                return $timeSlots;
            } else {
                $timeSlots = filterQuery(TimeSlot::query(), $request, $allowFields);
                $timeSlots = $timeSlots->get();
                return $this->successResponse(null, compact('timeSlots'));
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getAcademicYears(Request $request)
    {
        try {
            $allowFields = [];
            $academicYears = filterQuery(AcademicYear::query(), $request, $allowFields);
            $academicYears = $academicYears->orderBy('id', 'desc')->get();
            return $this->successResponse(null, compact('academicYears'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getCollegeClasses(Request $request)
    {
        try {
            $allowFields = ['academic_period_id', 'study_program_id', 'course_id', 'lecture_system_id', 'is_lock_score'];
            $collegeClasses = filterQuery(CollegeClass::query()->with('course'), $request, $allowFields);
            $collegeClasses = $collegeClasses->get();
            return $this->successResponse(null, compact('collegeClasses'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getCourses(Request $request, $query = [], $is_array = false)
    {
        try {
            $allowFields = ['study_program_id', 'course_type_id', 'course_group_id', 'scientific_field_id', 'rps_employee_id', 'employee_id'];
            if($is_array) {
                $courses = filterQuery(Course::query(), $query, $allowFields, true);
                $courses = $courses->get();
                return $courses;
            } else {
                $courses = filterQuery(Course::query(), $request, $allowFields);
                $courses = $courses->get();
                return $this->successResponse(null, compact('courses'));
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getClassSchedules(Request $request)
    {
        try {
            $allowFields = ['employee_id', 'room_id', 'meeting_type_id', 'college_class_id'];
            $classSchedules = filterQuery(ClassSchedule::query(), $request, $allowFields);
            $classSchedules = $classSchedules->orderBy('meeting_number', 'asc')->get();
            return $this->successResponse(null, compact('classSchedules'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getClassGroups(Request $request, $query, $is_array = false)
    {
        try {
            $allowFields = ['study_program_id', 'academic_year_id'];
            if($is_array) {
                $classGroups = filterQuery(ClassGroup::query(), $query, $allowFields, true);
                if (mappingAccess() != null) {
                    $classGroups = $classGroups->whereIn('study_program_id', mappingAccess());
                }
                $classGroups = $classGroups->get();
                return $classGroups;
            } else {
                $classGroups = filterQuery(ClassGroup::query(), $request, $allowFields);
                if (mappingAccess() != null) {
                    $classGroups = $classGroups->whereIn('study_program_id', mappingAccess());
                }
                $classGroups = $classGroups->get();
                return $this->successResponse(null, compact('classGroups'));
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }


    public function getEvaluationTypes(Request $request)
    {
        try {
            $allowFields = [];
            $evaluationTypes = filterQuery(EvaluationType::query(), $request, $allowFields);
            $evaluationTypes = $evaluationTypes->get();
            return $this->successResponse(null, compact('evaluationTypes'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function getThesisStages(Request $request)
    {
        try {
            $allowFields = ['is_active', 'is_upload', 'thesis_type', 'thesis_stage_id'];
            $thesisStages = filterQuery(ThesisStage::query(), $request, $allowFields);
            $thesisStages = $thesisStages->get();
            return $this->successResponse(null, compact('thesisStages'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function getWeeklySchedules(Request $request)
    {
        try {
            $allowFields = ['college_class_id', 'day_id', 'room_id', 'meeting_type_id', 'learning_method'];
            $weeklySchedules = filterQuery(WeeklySchedule::query(), $request, $allowFields);
            $weeklySchedules = $weeklySchedules->with(['day', 'room', 'meetingType'])->get();
            return $this->successResponse(null, compact('weeklySchedules'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }



    public function getAcademicCalendars(Request $request)
    {
        try {
            $allowFields = ['academic_period_id', 'academic_activity_id'];
            $academicCalendars = filterQuery(AcademicCalendar::query(), $request, $allowFields);
            $academicCalendars = $academicCalendars->with(['academicPeriod', 'academicActivity'])->get();
            return $this->successResponse(null, compact('academicCalendars'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getDays(Request $request)
    {
        try {
            $allowFields = [];
            $days = filterQuery(Day::query(), $request, $allowFields);
            $days = $days->get();
            return $this->successResponse(null, compact('days'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getAchievementGroups(Request $request)
    {
        try{
            $allowFields = ['id', 'name'];
            $achievementGroups = filterQuery(AchievementGroup::query(), $request, $allowFields);
            $achievementGroups = $achievementGroups->where('is_active', 1)->get();
            return $this->successResponse(null, compact('achievementGroups'));
        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    public function getAchievementLevels(Request $request)
    {
        try{
            $allowFields = [];
            $achievementLevels = filterQuery(AchievementLevel::query(), $request, $allowFields);
            $achievementLevels = $achievementLevels->get();
            return $this->successResponse(null, compact('achievementLevels'));
        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    public function getEthnics(Request $request)
    {
        try{
            $allowFields = [];
            $ethnic = filterQuery(Ethnic::query(), $request, $allowFields);
            $ethnic = $ethnic->get();
            return $this->successResponse(null, compact('ethnic'));
        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }
    public function getDisability(Request $request)
    {
        try{
            $allowFields = ['name',];
            $disability = filterQuery(Disability::query(), $request, $allowFields);
            $disability = $disability->get();
            return $this->successResponse(null, compact('disability'));
        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    public function getIncomes(Request $request){
        try{
            $allowFields = [];
            $income = filterQuery(Income::query(), $request, $allowFields);
            $income = $income->get();
            return $this->successResponse(null, compact('income'));
        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }
}
