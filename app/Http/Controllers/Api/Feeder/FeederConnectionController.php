<?php

namespace App\Http\Controllers\Api\Feeder;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\FeederService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeederConnectionController extends Controller
{
    public function connection(Request $request)
    {
        if ($request->method() == 'POST') {
            $request->validate([
                'feeder_url' => 'required',
                'feeder_port' => 'required',
                'feeder_username' => 'required',
                'feeder_password' => 'required'
            ]);
        }
        try {
            set_time_limit(0);
            $fields = ['feeder_url', 'feeder_path', 'feeder_port', 'feeder_username', 'feeder_password'];
            if ($request->method() == 'POST') {
                DB::beginTransaction();
                foreach ($request->all() as $key => $value) {
                    if (in_array($key, $fields)) {
                        Setting::where('key', $key)->update(['value' => $value]);
                    }
                }
                $data = new FeederService('GetToken');
                $res = $data->runWS();
                Setting::where('key', 'feeder_err_code')->update(['value' => $res['error_code']]);
                Setting::where('key', 'feeder_err_message')->update(['value' => $res['error_code'] == 0 ? 'Terkoneksi' : $res['error_desc']]);
                DB::commit();
                return $this->successResponse('Berhasil update koneksi feeder');
            } else if ($request->method() == 'GET') {
                $data = [
                    'feeder_url' => getSetting('feeder_url'),
                    'feeder_path' => getSetting('feeder_path'),
                    'feeder_port' => getSetting('feeder_port'),
                    'feeder_username' => getSetting('feeder_username'),
                    'feeder_password' => getSetting('feeder_password'),
                    'feeder_err_code' => getSetting('feeder_err_code'),
                    'feeder_err_message' => getSetting('feeder_err_message'),
                ];
                return $this->successResponse(null, compact('data'));
            } else {
                return $this->errorResponse(500, 'Method tidak valid');
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
