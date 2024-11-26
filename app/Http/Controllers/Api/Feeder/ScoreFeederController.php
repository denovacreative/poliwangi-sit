<?php

namespace App\Http\Controllers\Api\Feeder;

use App\Http\Controllers\Controller;
use App\Models\CollegeClass;
use App\Models\Score;
use App\Models\ScoreScale;
use App\Models\Student;
use App\Models\StudyProgram;
use App\Services\FeederService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class ScoreFeederController extends Controller
{
    public function sync(Request $request)
    {
        try {
            if (!method_exists($this, $request->act)) {
                return $this->errorResponse(500, 'Aksi tidak di temukan!');
            }
            $action = $request->act;
            return $this->$action($request);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // Score Scale
    public function getScoreScale($request)
    {
        try {
            set_time_limit(0);
            $data = new FeederService('GetListSkalaNilaiProdi');
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = ScoreScale::where('feeder_id', $value['id_bobot_nilai'])->first();
                $findStudyProgram = StudyProgram::where('feeder_id', $value['id_prodi'])->first();
                if ($findStudyProgram) {
                    if (!$check) {
                        ScoreScale::create([
                            'id' => $value['id_bobot_nilai'],
                            'study_program_id' => $findStudyProgram->id,
                            'grade' => $value['nilai_huruf'],
                            'index_score' => $value['nilai_indeks'],
                            'min_score' => $value['bobot_minimum'],
                            'max_score' => $value['bobot_maksimum'],
                            'date_start' => Carbon::parse($value['tanggal_mulai_efektif'])->format('Y-m-d'),
                            'date_end' => Carbon::parse($value['tanggal_akhir_efektif'])->format('Y-m-d'),
                            'is_score_def' => false,
                            'feeder_id' => $value['id_bobot_nilai'],
                            // 'feeder_status' => 'SUKSES'
                        ]);
                    } else {
                        $check->update([
                            'study_program_id' => $findStudyProgram->id,
                            'grade' => $value['nilai_huruf'],
                            'index_score' => $value['nilai_indeks'],
                            'min_score' => $value['bobot_minimum'],
                            'max_score' => $value['bobot_maksimum'],
                            'date_start' => Carbon::parse($value['tanggal_mulai_efektif'])->format('Y-m-d'),
                            'date_end' => Carbon::parse($value['tanggal_akhir_efektif'])->format('Y-m-d'),
                            'is_score_def' => false,
                        ]);
                    }
                }
            }
            return $this->successResponse('Berhasil sync skala nilai', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // Score 
    public function getScore($request)
    {
        try {
            set_time_limit(0);
            $opt = [
                'limit' => $request->limit,
                'offset' => $request->offset,
                'filter' => $request->filter
            ];
            $data = new FeederService('GetDetailNilaiPerkuliahanKelas', $opt);
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $findStudent = Student::where('feeder_id', $value['id_mahasiswa'])->first();
                $findCollegeClass = CollegeClass::where('feeder_id', $value['id_kelas_kuliah'])->first();
                if ($findCollegeClass && $findStudent) {
                    $check = Score::where(['student_id' => $findStudent->id, 'college_class_id' => $findCollegeClass->id])->first();
                    if (!$check) {
                        Score::create([
                            'id' => Uuid::uuid4(),
                            'college_class_id' => $findCollegeClass->id,
                            'student_id' => $findStudent->id,
                            'final_score' => $value['nilai_angka'], // NA Feeder
                            'remedial_score' => 0, // UP
                            'final_grade' => $value['nilai_huruf'], // NH
                            'score' => $value['nilai_angka'], // NA
                            'grade' => null, // NHU
                            'index_score' => $value['nilai_indeks'], // Indeks Feeder
                            'is_publish' => true,
                            'is_score_def' => false,
                            'feeder_id' => $value['id_kelas_kuliah'],
                            'feeder_status' => 'SUKSES',
                        ]);
                    }
                }
            }
            return $this->successResponse('Berhasil sync nilai', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    //insert score scale
    public function uploadScoreScale($request)
    {
        set_time_limit(0);
        try{

            if(!isset($request->scoreScale)){
                $data = ScoreScale::with(['studyProgram'])->where('feeder_id', NULL);

                if(
                    isset($request->study_program_id) &&
                    $request->study_program_id != NULL &&
                    $request->study_program_id != "" &&
                    $request->study_program_id != "all"
                ){
                    $data->where('study_program_id', $request->study_program_id);
                }

                $datas = $data->get();

                foreach($datas as $g){
                    $list = [
                        "record" => [
                            "id_prodi" => $g->study_program_id,
                            "nilai_huruf" => $g->grade,
                            "nilai_indeks" => $g->index_score,
                            "bobot_minimum" => $g->min_score,
                            "bobot_maksimum" => $g->max_score,
                            "tanggal_mulai_efektif" => $g->date_start,
                            "tanggal_akhir_efektif" => $g->date_end
                        ],
                    ];

                    $data = new FeederService('InsertSkalaNilaiProdi', $list);
                    $res = $data->runWS();

                    $val = $res['error_code'] == '0' ? "SUKSES" : 'GAGAL';

                    ScoreScale::where('id', $g->id)->update([
                        'feeder_id' => $res['error_code'] == '0' ? $res['data']['id_bobot_nilai'] : $g->feeder_id,
                        // 'feeder_status' => $val,
                        // 'feeder_description' => $res['error_desc'],
                    ]);
                }
            }else{
                for ($numArray=0; $numArray < count($request->scoreScale); $numArray++) {
                    $g = ScoreScale::with(['studyProgram'])->where('id', $request->scoreScale[$numArray])->where('feeder_id', null)->first();

                    $list = [
                        "record" => [
                            "id_prodi" => $g->study_program_id,
                            "nilai_huruf" => $g->grade,
                            "nilai_indeks" => $g->index_score,
                            "bobot_minimum" => $g->min_score,
                            "bobot_maksimum" => $g->max_score,
                            "tanggal_mulai_efektif" => $g->date_start,
                            "tanggal_akhir_efektif" => $g->date_end
                        ],
                    ];

                    $data = new FeederService('InsertSkalaNilaiProdi', $list);
                    $res = $data->runWS();

                    $val = $res['error_code'] == '0' ? "SUKSES" : 'GAGAL';

                    ScoreScale::where('id', $g->id)->update([
                        'feeder_id' => $res['error_code'] == '0' ? $res['data']['id_bobot_nilai'] : $g->feeder_id,
                        // 'feeder_status' => $val,
                        // 'feeder_description' => $res['error_desc'],
                    ]);
                }
            }

            return $this->successResponse('Berhasil upload feeder Skala Nilai');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }
    //update score scale
    public function updateScoreScale($request)
    {
        try{

            for ($numArray=0; $numArray < count($request->scoreScale); $numArray++) {
                $g = ScoreScale::with(['studyProgram'])->where('id', $request->scoreScale[$numArray])->first();

                $list = [
                    "key" => [
                        'id_bobot_nilai' => $g->feeder_id
                    ],
                    "record" => [
                        "id_prodi" => $g->study_program_id,
                        "nilai_huruf" => $g->grade,
                        "nilai_indeks" => $g->index_score,
                        "bobot_minimum" => $g->min_score,
                        "bobot_maksimum" => $g->max_score,
                        "tanggal_mulai_efektif" => $g->date_start,
                        "tanggal_akhir_efektif" => $g->date_end
                    ],
                ];

                $data = new FeederService('UpdateSkalaNilaiProdi', $list);
                $res = $data->runWS();

                $val = $res['error_code'] == '0' ? "SUKSES" : 'GAGAL';

                ScoreScale::where('id', $g->id)->update([
                    'feeder_id' => $res['error_code'] == '0' ? $res['data']['id_bobot_nilai'] : $g->feeder_id,
                    // 'feeder_status' => $val,
                    // 'feeder_description' => $res['error_desc'],
                ]);
            }

            return $this->successResponse('Berhasil ubah feeder Skala Nilai');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    //delete score scale
    public function deleteScoreScale($request)
    {
        try{

            for ($numArray=0; $numArray < count($request->scoreScale); $numArray++) {
                $g = ScoreScale::with(['studyProgram'])->where('id', $request->scoreScale[$numArray])->first();

                $list = [
                    "key" => [
                        'id_bobot_nilai' => $g->feeder_id
                    ],
                ];

                $data = new FeederService('DeleteSkalaNilaiProdi', $list);
                $res = $data->runWS();

                $val = $res['error_code'] == '0' ? "SUKSES" : 'GAGAL';

                ScoreScale::where('id', $g->id)->update([
                    'feeder_id' => $res['error_code'] == '0' ? NULL : $g->feeder_id,
                    // 'feeder_status' => $val,
                    // 'feeder_description' => $res['error_desc'],
                ]);
            }

            return $this->successResponse('Berhasil hapus feeder Skala Nilai');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    //update score
    public function updateScore($request)
    {
        set_time_limit(0);
        try{
            for ($numArray=0; $numArray < count($request->scores); $numArray++) {
                $g = Score::with(['collegeClass', 'student'])->where('id', $request->scores[$numArray])->first();

                $list = [
                    "key"=>[
                        "id_kelas_kuliah"=> $g->college_class_id,
                        "id_registrasi_mahasiswa"=> $g->student->reg_id
                    ],
                    "record"=>[
                        "nilai_angka"=>$g->score,
                        "nilai_huruf"=> $g->final_grade,
                        "nilai_indeks"=> $g->index_score
                    ]
                ];

                $data = new FeederService('UpdateNilaiPerkuliahanKelas', $list);
                $res = $data->runWS();

                $val = $res['error_code'] == '0' ? "SUKSES" : 'GAGAL';

                Score::where('id', $g->id)->update([
                    'feeder_id' => $res['error_code'] == '0' ? $res['data']['id_kelas_kuliah'] : $g->feeder_id,
                    'feeder_status' => $val,
                    'feeder_description' => $res['error_desc'],
                ]);

            }

            return $this->successResponse('Berhasil ubah feeder nilai');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }
}
