<?php

namespace App\Http\Controllers\Api\Feeder;

use App\Http\Controllers\Controller;
use App\Models\Curriculum;
use App\Models\StudyProgram;
use App\Services\FeederService;
use Exception;
use Illuminate\Http\Request;

class CurriculumFeederController extends Controller
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
    // Curriculum
    public function getCurriculum($request)
    {
        try {
            set_time_limit(0);
            $opt = [
                "limit" => $request->limit,
                "offset" => $request->offset,
                "filter" => $request->filter
            ];
            $data = new FeederService('GetListKurikulum', $opt);
            $res = $data->runWS();
            // return dd(count($res['data']));
            foreach ($res['data'] as $key => $value) {
                $check = Curriculum::where('feeder_id', $value['id_kurikulum'])->first();
                $findStudyProgram = StudyProgram::where('feeder_id', $value['id_prodi'])->first();
                if ($findStudyProgram) {
                    if (!$check) {
                        Curriculum::create([
                            'id' => $value['id_kurikulum'],
                            'study_program_id' => $findStudyProgram->id,
                            'academic_period_id' => $value['id_semester'],
                            'name' => $value['nama_kurikulum'],
                            'credit_total' => $value['jumlah_sks_lulus'],
                            'mandatory_credit' => $value['jumlah_sks_wajib'],
                            'choice_credit' => $value['jumlah_sks_pilihan'],
                            'feeder_id' => $value['id_kurikulum'],
                            'feeder_status' => 'SUKSES'
                        ]);
                    } else {
                        $check->update([
                            'study_program_id' => $findStudyProgram->id,
                            'academic_period_id' => $value['id_semester'],
                            'name' => $value['nama_kurikulum'],
                            'credit_total' => $value['jumlah_sks_lulus'],
                            'mandatory_credit' => $value['jumlah_sks_wajib'],
                            'choice_credit' => $value['jumlah_sks_pilihan'],
                        ]);
                    }
                }
            }
            return $this->successResponse('Berhasil sync kurikulum', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function uploadCurriculum($request){
        set_time_limit(0);
        try{
            if(!isset($request->curriculum)){
    
                $get = Curriculum::with(['studyProgram', 'academicPeriod'])->where('feeder_id', NULL);
                if(
                    isset($request->study_program_id) &&
                    $request->study_program_id != NULL &&
                    $request->study_program_id != "" &&
                    $request->study_program_id != "all"
                ){
                    $get->where('study_program_id', $request->study_program_id);
                }
    
                if(
                    isset($request->academic_period_id) &&
                    $request->academic_period_id != NULL &&
                    $request->academic_period_id != "" &&
                    $request->academic_period_id != "all"
                ){
                    $get->where('academic_period_id', $request->academic_period_id);
    
                }
    
                $gets = $get->get();
    
                foreach($gets as $g){
                    $list = [
                        "record" => [
                            'nama_kurikulum' => $g->name,
                            'id_prodi' => $g->studyProgram->feeder_id,
                            'id_semester' => $g->academicPeriod->id,
                            'id_jenjang_pendidikan' => $g->studyProgram->educationLevel->id,
                            'jumlah_sks_lulus' => $g->credit_total,
                            'jumlah_sks_wajib' => $g->mandatory_credit,
                            'jumlah_sks_pilihan' => $g->choice_credit,
                        ],
                    ];
                    $data = new FeederService('InsertKurikulum', $list);
                    $res = $data->runWS();
        
                    $id_curriculum_feeder = isset($res['data']['id_kurikulum']) ? $res['data']['id_kurikulum'] : null;
    
                    Curriculum::with(['studyProgram', 'academicPeriod'])->where('id', $g->id)->update([
                        'feeder_id' => $id_curriculum_feeder,
                        'feeder_status' => $id_curriculum_feeder == null ? 'GAGAL' : 'SUKSES',
                        'feeder_description' => $res['error_desc'],
                    ]);
                }
    
                
            }else{
    
                for ($numArray=0; $numArray < count($request->curriculum); $numArray++) {
                    $get = Curriculum::with(['studyProgram', 'academicPeriod'])->where('id', $request->curriculum[$numArray])->where('feeder_id', null)->first();
    
                    $list = [
                        "record" => [
                            'nama_kurikulum' => $get->name,
                            'id_prodi' => $get->studyProgram->feeder_id,
                            'id_semester' => $get->academicPeriod->id,
                            'id_jenjang_pendidikan' => $get->studyProgram->educationLevel->id,
                            'jumlah_sks_lulus' => $get->credit_total,
                            'jumlah_sks_wajib' => $get->mandatory_credit,
                            'jumlah_sks_pilihan' => $get->choice_credit,
                        ],
                    ];
    
                    $data = new FeederService('InsertKurikulum', $list);
                    $res = $data->runWS();
        
                    $id_curriculum_feeder = isset($res['data']['id_kurikulum']) ? $res['data']['id_kurikulum'] : null;
    
                    Curriculum::with(['studyProgram', 'academicPeriod'])->where('id', $request->curriculum[$numArray])->update([
                        'feeder_id' => $id_curriculum_feeder,
                        'feeder_status' => $id_curriculum_feeder == null ? 'GAGAL' : 'SUKSES',
                        'feeder_description' => $res['error_desc'],
                    ]);
    
                }
                
            }
            return $this->successResponse('Berhasil Upload Feeder Kurikulum');
        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    public function UpdateCurriculum($request){
        try{

            for ($numArray=0; $numArray < count($request->curriculum); $numArray++) {
                $get = Curriculum::with(['studyProgram', 'academicPeriod'])->where('id', $request->curriculum[$numArray])->first();

                $list = [
                    "record" => [
                        'nama_kurikulum' => $get->name,
                        'id_prodi' => $get->studyProgram->feeder_id,
                        'id_semester' => $get->academicPeriod->id,
                        'id_jenjang_pendidikan' => $get->studyProgram->educationLevel->id,
                        'jumlah_sks_lulus' => $get->credit_total,
                        'jumlah_sks_wajib' => $get->mandatory_credit,
                        'jumlah_sks_pilihan' => $get->choice_credit,
                    ],
                    "key" => [
                        'id_kurikulum' => $get->feeder_id
                    ],
                ];

                $data = new FeederService('UpdateKurikulum', $list);
                $res = $data->runWS();
    
                $id_curriculum_feeder = $res['error_code'] == '0' ? 1 : 0;

                Curriculum::with(['studyProgram', 'academicPeriod'])->where('id', $request->curriculum[$numArray])->update([
                    'feeder_id' => $res['error_code'] == '0' ? $res['data']['id_kurikulum'] : $get->feeder_id,
                    'feeder_status' => $id_curriculum_feeder != 0 ? 'GAGAL' : 'SUKSES',
                    'feeder_description' => $res['error_desc'],
                ]);

            }

            return $this->successResponse('Berhasil ubah feeder kurikulum');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }

    }

    public function DeleteCurriculum($request){
        try{

            for ($numArray=0; $numArray < count($request->curriculum); $numArray++) {
                $get = Curriculum::with(['studyProgram', 'academicPeriod'])->where('id', $request->curriculum[$numArray])->first();

                $list = [
                    "key" => [
                        'id_kurikulum' => $get->feeder_id
                    ],
                ];

                $data = new FeederService('DeleteKurikulum', $list);
                $res = $data->runWS();

                Curriculum::with(['studyProgram', 'academicPeriod'])->where('id', $request->curriculum[$numArray])->update([
                    'feeder_id' => $res['error_code'] == '0' ? null : $get->feeder_id,
                    'feeder_status' => $res['error_code'] != '0' ? 'GAGAL' : 'SUKSES',
                    'feeder_description' => $res['error_desc'],
                ]);

            }

            return $this->successResponse('Berhasil hapus feeder kurikulum');
        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }
}
