<?php

namespace App\Http\Controllers\Api\Feeder\Truncate;

use App\Http\Controllers\Controller;
use App\Models\AcademicPeriod;
use App\Models\AcademicYear;
use App\Models\AchievementLevel;
use App\Models\AchievementType;
use App\Models\ActivityCategory;
use App\Models\ClassParticipant;
use App\Models\CollegeClass;
use App\Models\Course;
use App\Models\CourseCurriculum;
use App\Models\Curriculum;
use App\Models\Disability;
use App\Models\EducationLevel;
use App\Models\Employee;
use App\Models\EmployeeStatus;
use App\Models\EvaluationType;
use App\Models\Finance;
use App\Models\Graduation;
use App\Models\Income;
use App\Models\Profession;
use App\Models\RegistrationPath;
use App\Models\RegistrationType;
use App\Models\Religion;
use App\Models\Score;
use App\Models\ScoreScale;
use App\Models\Student;
use App\Models\StudentActivity;
use App\Models\StudentActivityMember;
use App\Models\StudentActivitySupervisor;
use App\Models\StudentCollegeActivity;
use App\Models\StudentStatus;
use App\Models\StudyProgram;
use App\Models\SubstanceType;
use App\Models\TeachingLecturer;
use App\Models\Transcript;
use App\Models\TypeOfStay;
use App\Models\UniversityProfile;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TruncateController extends Controller
{
    public function truncate()
    {
        try {
            Curriculum::query()->truncate();
            Course::query()->truncate();
            CourseCurriculum::query()->truncate();
            Student::query()->truncate();
            StudentActivity::query()->truncate();
            StudentActivityMember::query()->truncate();
            StudentActivitySupervisor::query()->truncate();
            Employee::query()->truncate();
            Graduation::query()->truncate();
            CollegeClass::query()->truncate();
            TeachingLecturer::query()->truncate();
            StudentCollegeActivity::query()->truncate();
            ClassParticipant::query()->truncate();
            Score::query()->truncate();
            AcademicPeriod::query()->truncate();
            Transcript::query()->truncate();
            Income::query()->truncate();
            Profession::query()->truncate();
            StudyProgram::query()->truncate();
            UniversityProfile::query()->truncate();
            ActivityCategory::query()->truncate();
            SubstanceType::query()->truncate();
            ScoreScale::query()->truncate();
            Religion::query()->truncate();
            RegistrationPath::query()->truncate();
            EvaluationType::query()->truncate();
            RegistrationType::query()->truncate();
            TypeOfStay::query()->truncate();
            EducationLevel::query()->truncate();
            Disability::query()->truncate();
            AcademicYear::query()->truncate();
            StudentStatus::query()->truncate();
            Finance::query()->truncate();
            AchievementLevel::query()->truncate();
            AchievementType::query()->truncate();
            EmployeeStatus::query()->truncate();
            EmployeeActiveStatus::query()->truncate();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil truncate data'
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
