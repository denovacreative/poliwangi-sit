<?php

namespace App\Http\Controllers\Api\Lecture;

use App\Http\Controllers\Controller;
use App\Models\JudicialParticipant;
use App\Models\JudicialParticipantRequirement;
use App\Models\JudicialPeriod;
use App\Models\JudicialRequirement;
use App\Models\StudentCollegeActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Exception;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;
use App\Models\Student;
use App\Models\StudyProgram;
use App\Models\Thesis;
use App\Models\UniversityProfile;
use PHPUnit\Framework\MockObject\Builder\Stub;

class JudicialParticipantController extends Controller
{

    private $activeAcademicPeriodId = null;

    public function index(Request $request)
    {
        $academicPeridoActive =  getActiveAcademicPeriod(true)->id;


        if ($request->has('judicial_period_id') && $request->judicial_period_id != null && $request->judicial_period_id != 'all') {
            $query = JudicialParticipant::with(['judicialPeriod', 'student', 'judicialPeriod.academicPeriod'])->where('judicial_period_id', $request->judicial_period_id);

        }else{
            $query = JudicialParticipant::with(['judicialPeriod', 'student', 'judicialPeriod.academicPeriod'])
            ->whereHas('judicialPeriod', function ($q) use ($academicPeridoActive) {
            $q->whereHas('academicPeriod', function ($q) use ($academicPeridoActive) {
                $q->where('id', $academicPeridoActive);
            });
        });
        }

        if ($request->has('study_program_id') && $request->study_program_id != null && $request->study_program_id != 'all') {
            $query->whereHas('student', function ($q) use ($request) {
                $q->whereHas('studyProgram', function ($q) use ($request) {
                    $q->where('id', $request->study_program_id);
                });
            });
        }
        if ($request->has('academic_year_id') && $request->academic_year_id != null && $request->academic_year_id != 'all') {
            $query->whereHas('judicialPeriod', function ($q) use ($request) {
                $q->whereHas('academicPeriod', function ($q) use ($request) {
                    $q->where('academic_year_id', $request->academic_year_id);
             });
           });
        }

         return DataTables::of($query)->make();
    }


    public function print(Request $request){


        try{
            $query = JudicialParticipant::with(['judicialPeriod', 'student', 'judicialPeriod.academicPeriod', 'student.thesis']);

            if ($request->has('judicial_period_id') && $request->judicial_period_id != null && $request->judicial_period_id != 'all') {
                $query->where('judicial_period_id', $request->judicial_period_id);
            }
            if ($request->has('study_program_id') && $request->study_program_id != null && $request->study_program_id != 'all') {
                $query->whereHas('student', function ($q) use ($request) {
                    $q->whereHas('studyProgram', function ($q) use ($request) {
                        $q->where('id', $request->study_program_id);
                    });
                });
            }

            if ($request->has('academic_year_id') && $request->academic_year_id != null && $request->academic_year_id != 'all') {
                $query->whereHas('judicialPeriod', function ($q) use ($request) {
                    $q->whereHas('academicPeriod', function ($q) use ($request) {
                        $q->where('academic_year_id', $request->academic_year_id);
                 });
             });
            }


            return view('print.graduation-list-judicial', [
                'title' => 'Poliwangi',
                'universitasProfile' => UniversityProfile::all()->first(),
                'error' => false,
                'StudyProgram' => StudyProgram::with('educationLevel')->where('id' , $request->study_program_id)->first(),
                'data' => $query->get(),
                'judicialPeriod' => JudicialPeriod::where('id' , $request->judicial_period_id)->first()
            ]);

        }catch(Exception $e){
            return view('print.graduation-list-judicial', [
                'title' => 'Poliwangi',
                'universitasProfile' => UniversityProfile::all()->first(),
                'error' => true,
                'StudyProgram' => 'HWLLO',
            ]);
        }
    }


    public function getRequirements()
    {

        $judicialRequirements = JudicialRequirement::all();

        return $this->successResponse('Berhasil mendapatkan syarat yudisium', [
            'judicial_requirement' => $judicialRequirements,
        ]);
    }

    public function searchStudent(Request $request)
    {
        try {
            $students = Student::where('student_status_id', 'A')->where(function ($q) use ($request) {
                $q->where('name', 'iLike', '%' . $request->name . '%')->orWhere('nim', 'iLike', '%' . $request->name . '%');
            });
            if (mappingAccess() != null) {
                $students = $students->whereIn('study_program_id', mappingAccess());
            }
            $students = $students->get(['nim', 'id', 'name']);

            return $this->successResponse('Berhasil mendapatkan list data mahasiswa', compact('students'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function store(Request $request)
    {
        try {

            DB::beginTransaction();

            $files = [];

            $activeAcademicPeriod = getActiveAcademicPeriod(true);
            $studentCollegeActivity = StudentCollegeActivity::where('student_id', $request->student_id)->where('academic_period_id', $activeAcademicPeriod->id)->first();

            // Validate student (if akm meets with student's total akm)
            $totalAkm = StudentCollegeActivity::where('student_id', $request->student_id)->groupBy('student_id')->sum('credit_total');
            $curriculumAkm = Student::where('id', $request->student_id)->first()->curriculum->credit_total;

            if ($totalAkm < $curriculumAkm) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kurikulum siswa ini belum memenuhi target. Target : ' . $curriculumAkm,
                ], 400);
            }

            foreach ($request->judicial_requirement_id as $jri) {

                $fileName = null;
                $attachmentKey = 'attachment-' . $jri;
                $descriptionKey = 'description-' . $jri;

                // Upload judicial requirement files
                if ($request->hasFile($attachmentKey)) {
                    $fileName = Str::random(10) . '.' . $request->file($attachmentKey)->getClientOriginalExtension();
                    $request->file($attachmentKey)->move(public_path('storage/documents/judicial-requirements'), $fileName);
                }

                $files[$jri] = [
                    'description' => $request->has('description-' . $jri) ? $request['description-' . $jri] : null,
                    'attachment' => $fileName,
                ];

                $request->offsetUnset($attachmentKey);
                $request->offsetUnset($descriptionKey);
            }

            if (!$studentCollegeActivity) throw new Exception('AKM belum terdaftar untuk siswa ini');

            $judicialParticipantId = Uuid::uuid4();

            // Insert judicial participant
            JudicialParticipant::create([
                'id' => (string) $judicialParticipantId,
                'student_id' => $request->student_id,
                'judicial_period_id' => $request->judicial_period_id,
                'decree_date' => $request->decree_date,
                'decree_number' => $request->decree_number,
                'certificate_date' => $request->certificate_date,
                'certificate_number' => $request->certificate_number,
                'transcript_date' => $request->transcript_date,
                'transcript_number' => $request->transcript_number,
                'national_certificate_number' => $request->national_certificate_number,
                'nirl' => $request->nirl,
                'semester' => $activeAcademicPeriod->semester,
                'credit' => $studentCollegeActivity->credit_total,
                'grade' => $studentCollegeActivity->grade,
            ]);

            $judicialRequirementParticipantToInsert = [];

            foreach ($files as $judicialRequirementId => $file) {

                $judicialRequirementParticipantToInsert[] = [
                    'id' => Uuid::uuid4(),
                    'judicial_participant_id' => $judicialParticipantId,
                    'judicial_requirement_id' => $judicialRequirementId,
                    'attachment' => $file['attachment'],
                    'validation_date' => null,
                    'description' => $file['description'],
                    'is_valid' => false,
                    'created_at' => Carbon::now()
                ];
            }

            JudicialParticipantRequirement::insert($judicialRequirementParticipantToInsert);

            DB::commit();

            return $this->successResponse('Berhasil menambahkan peserta yudisium baru');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->exceptionResponse($e);
        }
    }

    public function update(Request $request){

        try{
            $activeAcademicPeriod = getActiveAcademicPeriod(true);
            $studentCollegeActivity = StudentCollegeActivity::where('academic_period_id', $activeAcademicPeriod->id)->first();
            $judicialRequirementId = '' ;
            foreach ($request->judicial_requirement_id as $jri) {

                $fileName = null;
                $attachmentKey = 'attachment-' . $jri;
                $descriptionKey = 'description-' . $jri;

                // Upload judicial requirement files
                if ($request->hasFile($attachmentKey)) {
                    $fileName = Str::random(10) . '.' . $request->file($attachmentKey)->getClientOriginalExtension();
                    $request->file($attachmentKey)->move(public_path('storage/documents/judicial-requirements'), $fileName);
                }

                $judicialRequirementId = $jri;
                $files[$jri] = [
                    'description' => $request->has('description-' . $jri) ? $request['description-' . $jri] : null,
                    'attachment' => $fileName,
                ];

                $request->offsetUnset($attachmentKey);
                $request->offsetUnset($descriptionKey);
            }


            if (!$studentCollegeActivity) throw new Exception('AKM belum terdaftar untuk siswa ini');

            $judicialParticipant = JudicialParticipant::find($request->judicialParticipantId);

            $judicialParticipant->nirl = $request->nirl;
            $judicialParticipant->judicial_period_id = $request->judicial_period_id;
            $judicialParticipant->decree_date = $request->decree_date;
            $judicialParticipant->decree_number = $request->decree_number;
            $judicialParticipant->transcript_number = $request->transcript_number;
            $judicialParticipant->transcript_date = $request->transcript_date;
            $judicialParticipant->certificate_number = $request->certificate_number;
            $judicialParticipant->semester = $studentCollegeActivity->credit_semester;
            $judicialParticipant->credit = $studentCollegeActivity->credit_total;
            $judicialParticipant->grade = $studentCollegeActivity->grade;
            $judicialParticipant->save();

            foreach ($files as $file) {
                $JudicialParticipantRequirements = DB::table('judicial_participant_requiremetns')->where('judicial_participant_id', $judicialParticipant->id);
                $JudicialParticipantRequirements->update([
                    'judicial_participant_id' => $request->judicialParticipantId,
                    'judicial_requirement_id' => $judicialRequirementId,
                    'attachment' => $file['attachment'],
                    'validation_date' => null,
                    'description' => $file['description'],
                    'is_valid' => false,
                    'updated_at' => Carbon::now()
                ]);
            }
            return $this->successResponse('Berhasil memperbarui data yudisium');

        }catch(Exception $e){
            DB::rollBack();
            return $this->exceptionResponse($e);
        }
    }
    public function show($judicialParticipantId)
    {

        $judicialParticipant = JudicialParticipant::where('id' , $judicialParticipantId)->with(['student','judicialPeriod'])->first();
        $JudicialParticipantRequirement = DB::table('judicial_participant_requiremetns')->where('judicial_participant_id', '=' , $judicialParticipant->id)
                                             ->join('judicial_requirements', 'judicial_participant_requiremetns.judicial_requirement_id' , '=' , 'judicial_requirements.id')->get();

        return $this->successResponse('', [
            'judicial_participant' => $judicialParticipant,
            'JudicialParticipantRequirement' => $JudicialParticipantRequirement,
        ]);
    }

    public function destroy(JudicialParticipant $judicialParticipant)
    {
        try {
            $judicialParticipant->delete();
            return $this->successResponse('Berhasil menghapus data yudisium');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
