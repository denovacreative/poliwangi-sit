<?php

namespace App\Http\Controllers\Api\Student\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\Academic\HerRegistrationRequest;
use App\Models\AcademicPeriod;
use App\Models\Heregistration;
use App\Models\Score;
use App\Models\StudentCollegeActivity;
use App\Models\StudentScholarship;
use App\Models\StudyProgramSetting;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StudentHerRegistrationController extends Controller
{
    public function index()
    {
        return DataTables::of(Heregistration::where('student_id', Auth::user()->userable->id)->with(['academicPeriod']))
            ->editColumn('payment_date', function($data) {
                return date('d-m-Y', strtotime($data->payment_date));
            })
            ->make();
    }

    public function getHerRegistrationData()
    {
        $student = Auth::user()->userable;
        $academicPeriod = AcademicPeriod::whereDate('heregistration_start_date', '<=', Carbon::now())->whereDate('heregistration_end_date', '>=', Carbon::now())->first();
        $studentStatusId = Auth::user()->userable->student_status_id;
        $studyProgramSettings = StudyProgramSetting::whereHas('academicPeriod', function($sp) { $sp->where('is_use', true); })->where('study_program_id', Auth::user()->userable->study_program_id)->first();
        $isHerRegistrationBypassedByStudyProgram = $studyProgramSettings && $studyProgramSettings->is_open_heregistration;
        $isRegisteredOnAkm = StudentCollegeActivity::where('student_id', Auth::user()->userable->id)->whereHas('academicPeriod', function($ap) { $ap->where('is_use', true); })->first();

        // Get score
        $studentScores = Score::where('student_id', Auth::user()->userable->id)->whereHas('collegeClass.academicPeriod', function($ap) {
            $ap->where('is_use', true);
        })->get('final_grade')->filter(function($ss) {
            return in_array(strtoupper($ss->final_grade), ['D', 'E']);
        });

        if ($academicPeriod) {

            $existingHerRegistration = $student->heregistration()->where('academic_period_id', $academicPeriod->id)->first();

            if ($existingHerRegistration) {
                return response()->json([
                    'already_registered' => true,
                    'heregistration' => $existingHerRegistration,
                    'contact_admin_alert' => $studentStatusId != 'N',
                    'is_heregistration_opened' => !!$academicPeriod && $academicPeriod->is_use,
                    'is_grade_under_minimum' => $studentScores->count() > 0,
                    'bypass_minimum_grade' => $isHerRegistrationBypassedByStudyProgram,
                    'is_registered_on_akm' => !!$isRegisteredOnAkm,
                ]);
            }
        } else {
            return response()->json([
                'already_registered' => true,
                'contact_admin_alert' => $studentStatusId != 'N',
                'is_heregistration_opened' => !!$academicPeriod && $academicPeriod->is_use,
                'is_grade_under_minimum' => $studentScores->count() > 0,
                'bypass_minimum_grade' => $isHerRegistrationBypassedByStudyProgram,
                'is_registered_on_akm' => !!$isRegisteredOnAkm,
            ]);
        }

        // Get student scholarship
        // $scholarships = StudentScholarship::where('academic_period_id', $academicPeriod->id)->where('student_id', $student->id)->where('is_active', true)->with('scholarship')->get();

        return response()->json([
            'already_registered' => false,
            'tuition_fee' => $student->tuition_fee,
            // 'is_scholarship' => $student->scholarship->count() > 0,
            'contact_admin_alert' => $studentStatusId != 'N',
            'is_heregistration_opened' => !!$academicPeriod && $academicPeriod->is_use,
            'herregistration_academic_period' => $academicPeriod,
            'is_grade_under_minimum' => $studentScores->count() > 0,
            'bypass_minimum_grade' => $isHerRegistrationBypassedByStudyProgram,
            'is_registered_on_akm' => !!$isRegisteredOnAkm,
            // 'scholarships' => $scholarships,
        ]);
    }

    public function store(HerRegistrationRequest $request)
    {

        $academicPeriod = AcademicPeriod::whereDate('heregistration_start_date', '<=', Carbon::now())->whereDate('heregistration_end_date', '>=', Carbon::now())->where('is_use', true)->first();

        if (!$academicPeriod) {
            return $this->errorResponse(400, 'Waktu daftar ulang sudah ditutup / belum dibuka!');
        }

        // Move attachment
        if ($request->has('attachment_file') && $request->attachment_file != '') {
            $name = Str::random() . '.' . $request->file('attachment_file')->extension();
            $request->file('attachment_file')->move(public_path('storage/images/lecture/administration/her-registrations'), $name);
            $request->merge(['attachment' => $name]);
        }

        $student = Auth::user()->userable;

        Heregistration::create([
            'id' => Uuid::uuid4(),
            'academic_period_id' => $academicPeriod->id,
            'student_id' => $student->id,
            'attachment' => $request->attachment,
            'payment_date' => Carbon::now(),
            'tuition_fee' => $student->tuition_fee,
            'is_scholarship' => null,
            'scholarship_amount' => null,
            'subtotal' => $student->tuition_fee,
            'is_acc' => false,
        ]);

        return $this->successResponse('Berhasil melakukan daftar ulang!');
    }
}
