<?php

namespace App\Http\Controllers\Api\Lecture;

use App\Exports\ThesisExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Lecture\ThesesRequest;
use App\Imports\ThesisImport;
use App\Models\Thesis;
use App\Models\ThesisGuidance;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ThesesController extends Controller
{
    public function index(Request $request)
    {
        $query = Thesis::with(['student.studyProgram.educationLevel', 'academicPeriod', 'thesisStage', 'thesisGuidance.employee']);
        if (!empty($request->thesis_type) and $request->thesis_type != '' and $request->thesis_type != 'all') {
            $query->where('thesis_type', $request->thesis_type);
        }
        if (!empty($request->academic_period_id) and $request->academic_period_id != '' and $request->academic_period_id != 'all') {
            $query->where('academic_period_id', $request->academic_period_id);
        }
        if (!empty($request->academic_year_id) and $request->academic_year_id != '' and $request->academic_year_id != 'all') {
            $query->whereHas('student', function ($q) use ($request) {
                $q->whereHas('academicPeriod', function ($qe) use ($request) {
                    $qe->where('academic_year_id', $request->academic_year_id);
                });
            });
        }
        if (!empty($request->study_program_id) and $request->study_program_id != '' and $request->study_program_id != 'all') {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('study_program_id', $request->study_program_id);
            });
        }
        return DataTables::of($query)->addColumn('guidance_count', function ($data) {
            return $data->thesisGuidance->count();
        })->editColumn('filing_date', function ($data) {
            return Carbon::parse($data->filing_date)->isoFormat('D MMM Y');
        })->make();
    }

    public function show(Thesis $thesis)
    {
        $data = [
            'thesis' => $thesis,
            'employee_1' => $thesis->thesisGuidance[0]->employee->id,
            'employee_2' => $thesis->thesisGuidance[1]->employee->id,
        ];
        return $this->successResponse(null, compact('data'));
    }

    public function store(ThesesRequest $request)
    {
        try {
            DB::beginTransaction();
            $thesis = Thesis::create([
                'id' => Uuid::uuid4(),
                'academic_period_id' => $request->academic_period,
                'student_id' => $request->student,
                'filing_date' => $request->filing_date,
                'start_date' => $request->start_date,
                'finish_date' => $request->finish_date,
                'topic' => $request->topic,
                'topic_en' => $request->topic_en,
                'title' => $request->title,
                'title_en' => $request->title_en,
                'abstract' => $request->abstract,
                '_number' => $request->decree_number,
                'decree_date' => $request->decree_date,
                'thesis_type' => $request->thesis_type,
                'is_active' => true,
                'is_acc' => true,
            ]);
            ThesisGuidance::insert([
                [
                    'id' => Uuid::uuid4(),
                    'thesis_id' => $thesis->id,
                    'employee_id' => $request->employee_1,
                    'is_acc' => true,
                ],
                [
                    'id' => Uuid::uuid4(),
                    'thesis_id' => $thesis->id,
                    'employee_id' => $request->employee_2,
                    'is_acc' => true,
                ],
            ]);
            DB::commit();
            return $this->successResponse('Berhasil membuat data tugas akhir');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(Thesis $thesis, ThesesRequest $request)
    {
        try {
            DB::beginTransaction();
            $thesis->update([
                'academic_period_id' => $request->academic_period,
                'student_id' => $request->student,
                'filing_date' => $request->filing_date,
                'start_date' => $request->start_date,
                'finish_date' => $request->finish_date,
                'topic' => $request->topic,
                'topic_en' => $request->topic_en,
                'title' => $request->title,
                'title_en' => $request->title_en,
                'abstract' => $request->abstract,
                'decree_number' => $request->decree_number,
                'decree_date' => $request->decree_date,
                'thesis_type' => $request->thesis_type,
            ]);
            ThesisGuidance::where('thesis_id', $thesis->id)->delete();
            ThesisGuidance::insert([
                [
                    'id' => Uuid::uuid4(),
                    'thesis_id' => $thesis->id,
                    'employee_id' => $request->employee_1,
                    'is_acc' => true,
                ],
                [
                    'id' => Uuid::uuid4(),
                    'thesis_id' => $thesis->id,
                    'employee_id' => $request->employee_2,
                    'is_acc' => true,
                ],
            ]);
            DB::commit();
            return $this->successResponse('Berhasil mengupdate data tugas akhir');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(Thesis $thesis)
    {
        try {
            $thesis->delete();
            return $this->successResponse('Berhasil menghapus data tugas akhir');
        } catch (QueryException $e) {
            return $this->exceptionResponse($e);
        }
    }
    public function importDataTheses(Request $request)
    {
        try{

            $file = $request->file('file_import');

            // membuat nama file unik
            $name_file = $file->getClientOriginalName();

            //temporary file
            $path = $file->storeAs('public/excel/',$name_file);

            $res = Excel::import(new ThesisImport, storage_path('app/public/excel/'.$name_file));


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
    public function downloadTemplateImport()
    {

        try {
            return Excel::download(new ThesisExport, 'TemplateImportThesis.xlsx');
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
