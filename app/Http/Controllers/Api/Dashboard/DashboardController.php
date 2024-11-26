<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\AcademicPeriod;
use App\Models\AcademicYear;
use App\Models\Announcement;
use App\Models\Graduation;
use App\Models\Guardianship;
use App\Models\JudicialParticipant;
use App\Models\Scholarship;
use App\Models\Score;
use App\Models\Student;
use App\Models\StudentCollegeActivity;
use App\Models\StudentScholarship;
use App\Models\StudyProgram;
use App\Models\Thesis;
use Exception;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\StudentT;

class DashboardController extends Controller
{
    //
    public function index(){
        set_time_limit(0);
        try{
            //get periode acamdemic active
            $academicPeriodActive = AcademicPeriod::with(['academicYear'])->where('is_use',true)->first();
            //get total new student
            $totalNewStudent = Student::whereHas('academicPeriod', function($q)use($academicPeriodActive){ 
                $q->where('academic_year_id', $academicPeriodActive->academic_year_id);
            });
            if (mappingAccess() != null) {
                $totalNewStudent = $totalNewStudent->whereIn('study_program_id', mappingAccess());
            }
            $totalNewStudent = $totalNewStudent->count();
            
            //student count
            $allStudent = Student::with(['studyProgram']);
            if (mappingAccess() != null) {
                $allStudent = $allStudent->whereIn('study_program_id', mappingAccess());
            }
            $allStudent = $allStudent->count();

            //student active count
            $activeStudent = Student::with(['studentStatus'])->whereHas('studentStatus', function($q){
                $q->where('is_active', true);
            });
            if (mappingAccess() != null) {
                $activeStudent = $activeStudent->whereIn('study_program_id', mappingAccess());
            }
            $activeStudent = $activeStudent->count();

            //total graduation
            $totalgraduation = Graduation::whereHas('academicPeriod', function($q)use($academicPeriodActive){ 
                $q->where('academic_year_id', $academicPeriodActive->academic_year_id);
            });
            if (mappingAccess() != null) {
                $totalgraduation = $totalgraduation->whereIn('study_program_id', mappingAccess());
            }
            $totalgraduation = $totalgraduation->count();


            //total IPK
            $totalipk = Graduation::whereHas('academicPeriod', function ($q) use ($academicPeriodActive) {
                $q->where('academic_year_id', $academicPeriodActive->academic_year_id);
            });
            if (mappingAccess() != null) {
                $totalipk = $totalipk->whereIn('study_program_id', mappingAccess());
            }
            $totalipk = $totalipk->selectRaw('SUM(grade) as ipk')->first();
            
            $total_ipk = $totalipk->ipk;
            $total_grad = $totalgraduation;

            if ($totalgraduation != 0) {
                $ipk = $total_ipk / $totalgraduation;
            } else {
                $ipk = 0; // Atau nilai lain yang sesuai jika $totalgraduation == 0
            }
            
            //announcement
            $announcement = Announcement::orderBy('created_at', 'desc')->limit(5)->get();
            $announcements = [];
            if(count($announcement) > 0){
                foreach ($announcement as $key) {
                    # code...
                    $announcements[] = [
                        'id' => $key->id,
                        'title' => $key->title,
                        'message' => $key->message,
                        'thumbnail' => $key->thumbnail,
                        'attachment' => $key->attachment,
                        'date' => date('d F Y',strtotime($key->created_at)),
                    ];
                }
            }

            //get student thesis
            $thesis = Thesis::with(['student'])->where('academic_period_id', $academicPeriodActive->id);
            if (mappingAccess() != null) {
                $thesis = $thesis->whereHas('student', function($q){
                    $q->whereIn('study_program_id', mappingAccess());
                });
            }
            $thesis = $thesis->count();

            //get student Yudisium
            $judicial = JudicialParticipant::with(['judicialPeriod', 'student'])->whereHas('judicialPeriod', function($q)use($academicPeriodActive){
                $q->where('academic_period_id', $academicPeriodActive->id);
            });
            if (mappingAccess() != null) {
                $judicial = $judicial->whereHas('student', function($q){
                    $q->whereIn('study_program_id', mappingAccess());
                });
            }
            $judicial = $judicial->count();

            //get student scholarship
            $scholarship = StudentScholarship::with(['scholarship', 'student'])->where('academic_period_id', $academicPeriodActive->id);
            if (mappingAccess() != null) {
                $scholarship = $scholarship->whereHas('student', function($q){
                    $q->whereIn('study_program_id', mappingAccess());
                });
            }
            $scholarship = $scholarship->count();
            
            
            $data = [
                'academicPeriodActive' => $academicPeriodActive,
                'totalNewStudent' => $totalNewStudent,
                'allStudent' => $allStudent,
                'activeStudent' => $activeStudent,
                'ipk' => round($ipk, 1),
                'announcements' => $announcements,
                'totalStudentThesis' => $thesis,
                'totalStudentJudicial' => $judicial,
                'totalStudentScholarship' => $scholarship,
                // 'unguardianship' => $this->countUnguardianship(),
                'akm' => $this->studentActivity(),
                'akmProdi' => $this->studentActivityStudyProgram(),
                // 'newStudentData' => $this->newStudentData(),
                'graduationData' => $this->graduation(),
                // 'score' => $this->score(),
            ];

            return $this->successResponse(null, $data);

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    private function countUnguardianship()
    {
        $query = Student::where(['student_status_id' => 'A']);
        // if (mappingAccess() != null) {
        //     $query = $query->whereIn('study_program_id', mappingAccess());
        // }
        $query = $query->get();
        $count = 0;
        foreach ($query as $value) {
            $getGuardianship = Guardianship::where(['student_id' => $value->id, 'academic_period_id' => getActiveAcademicPeriod(true)->id])->first();
            if (!$getGuardianship) {
                $count++;
            }
        }
        return $count;
    }

    private function studentActivity()
    {
        $year = AcademicPeriod::with(['academicYear'])->whereHas('academicYear', function($q){
            $q->where('is_active', true);
        })->groupBy('academic_year_id')->get('academic_year_id');
        $data = [];
        foreach ($year as $key) {

            $query = StudentCollegeActivity::with(['student', 'academicPeriod'])->whereHas('academicPeriod', function($q)use($key){
                $q->where('academic_year_id', $key->academicYear->id);
            });
            if (mappingAccess() != null) {
                $query = $query->whereHas('student', function($q){
                    $q->whereIn('study_program_id', mappingAccess());
                });
            }
            $query = $query->count();

            $data[] = [
                'name' => $key->academicYear->name,
                'total' => $query,
            ];
        }

        return $data;
    }

    private function studentActivityStudyProgram()
    {
        $prodi = StudyProgram::all();
        $data = [];
        foreach ($prodi as $key) {

            $query = StudentCollegeActivity::with(['student', 'academicPeriod'])->where('academic_period_id', getActiveAcademicPeriod(true)->id);
            if (mappingAccess() != null) {
                $query = $query->whereHas('student', function($q)use($key){
                    $q->whereIn('study_program_id', $key->id);
                });
            }
            $query = $query->count();

            $data[] = [
                'name' => $key->name,
                'total' => $query
            ];
        }

        return $data;
    }

    private function newStudentData()
    {
        //get periode acamdemic active
        $academicPeriodActive = AcademicPeriod::with(['academicYear'])->where('is_use',true)->first();
        $prodi = StudyProgram::with(['educationLevel']);
        // if (mappingAccess() != null) {
        //     $prodi = $prodi->whereIn('id', mappingAccess());
        // }
        $prodi = $prodi->get();
        $data = [];
        $total = 0;
        $totalpdb = 0;
        $totalnotpdb = 0;
        $totalmale = 0;
        $totalfemale = 0;
        foreach ($prodi as $key) {

            $totalNewStudent = Student::whereHas('academicPeriod', function($q)use($academicPeriodActive){ 
                $q->where('academic_year_id', $academicPeriodActive->academic_year_id);
            })->where('study_program_id', $key->id);
            $totalNewStudent = $totalNewStudent->count();
            //PDB
            $pdb = Student::whereHas('academicPeriod', function($q)use($academicPeriodActive){ 
                $q->where('academic_year_id', $academicPeriodActive->academic_year_id);
            })->where('registration_type_id', 1)->where('study_program_id', $key->id);
            $pdb = $pdb->count();
            //NOT PDB
            $notpdb = Student::whereHas('academicPeriod', function($q)use($academicPeriodActive){ 
                $q->where('academic_year_id', $academicPeriodActive->academic_year_id);
            })->where('registration_type_id', '!=', 1)->where('study_program_id', $key->id);
            $notpdb = $notpdb->count();
            //Male
            $male = Student::whereHas('academicPeriod', function($q)use($academicPeriodActive){ 
                $q->where('academic_year_id', $academicPeriodActive->academic_year_id);
            })->where('gender', 'L')->where('study_program_id', $key->id);
            $male = $male->count();
            //Female
            $female = Student::whereHas('academicPeriod', function($q)use($academicPeriodActive){ 
                $q->where('academic_year_id', $academicPeriodActive->academic_year_id);
            })->where('gender', 'P')->where('study_program_id', $key->id);
            $female = $female->count();

            $data[] = [
                'education_level_name' => $key->educationLevel->code,
                'name' => $key->name.' - '.$key->educationLevel->code,
                'total_new' => $totalNewStudent ?? 0,
                'pdb' => $pdb ?? 0,
                'notpdb' => $notpdb ?? 0,
                'male' => $male ?? 0,
                'female' => $female ?? 0,
            ];

            $total += $totalNewStudent ?? 0;
            $totalpdb += $pdb ?? 0;
            $totalnotpdb += $notpdb ?? 0;
            $totalmale += $male ?? 0;
            $totalfemale += $female ?? 0;
        }

        return [
            'data' => $data,
            'totalNewStudentData' => $total,
            'totalpdb' => $totalpdb,
            'totalnotpdb' => $totalnotpdb,
            'totalmale' => $totalmale,
            'totalfemale' => $totalfemale,
        ];
    }

    private function graduation()
    {
        //get periode acamdemic active
        $academicPeriodActive = AcademicPeriod::with(['academicYear'])->where('is_use',true)->first();
        $prodi = StudyProgram::with(['educationLevel']);
        if (mappingAccess() != null) {
            $prodi = $prodi->whereIn('id', mappingAccess());
        }
        $prodi = $prodi->get();

        $data = [];
        
        foreach ($prodi as $key) {
            $query = Graduation::with(['programStudy', 'academicPeriod'])->where('study_program_id', $key->id)->whereHas('academicPeriod', function($q)use($academicPeriodActive){
                $q->where('academic_year_id', $academicPeriodActive->academic_year_id);
            })->count();

            $data[] = [
                'name' => $key->name,
                'total' => $query,
            ];
        }

        return $data;
    }

    private function score()
    {
        // Mendapatkan periode akademik aktif
        $academicPeriodActive = AcademicPeriod::with(['academicYear'])->where('is_use', true)->first();

        // Mengambil daftar program studi yang sesuai dengan hak akses
        $prodiQuery = StudyProgram::with(['educationLevel']);

        if (mappingAccess() != null) {
            $prodiQuery->whereIn('id', mappingAccess());
        }

        $prodiList = $prodiQuery->get();
        $data = [];

        foreach ($prodiList as $prodi) {
            $scoreQuery = Score::with(['collegeClass'])
                ->whereHas('collegeClass', function ($q) use ($prodi) {
                    $q->where('study_program_id', $prodi->id);
                })
                ->whereHas('collegeClass', function ($q) use ($academicPeriodActive) {
                    $q->where('academic_period_id', $academicPeriodActive->id);
                });

            $studentCount = $scoreQuery->groupBy('student_id')->count();
            $totalScore = $scoreQuery->sum('final_score');

            $data[] = [
                'name' => $prodi->name,
                'total' => ($studentCount > 0) ? ($totalScore / $studentCount) : 0,
            ];
        }

        return $data;
    }

}
