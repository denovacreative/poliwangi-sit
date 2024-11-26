<?php

use App\Models\AcademicPeriod;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

if (!function_exists('customView')) {
    function customView($view, $data = [])
    {
        if (\Request::ajax()) {
            return view($view, $data);
        } else {
            return view('layouts.app', $data);
        }
    }
}

if (!function_exists('checkAuth')) {
    function checkAuth()
    {
        if (Auth::check()) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('getInfoLogin')) {
    function getInfoLogin()
    {
        if (checkAuth()) {
            return Auth::user();
        } else {
            return null;
        }
    }
}
if (!function_exists('getActiveAcademicPeriod')) {
    function getActiveAcademicPeriod($isUse = true, $mapArr = false)
    {
        $query = AcademicPeriod::where(['is_active' => true]);
        if ($isUse == true) {
            $query->where('is_use', true);
        }
        if ($mapArr == true) {
            $dataID = [];
            foreach ($query->get() as $key => $item) {
                $dataID[] = $item->id;
            }
            return $dataID;
        }
        return $isUse == true ? $query->first() : $query->get();
    }
}

if (!function_exists('greet')) {
    function greet($time)
    {
        if ($time >= 0 && $time <= 12) {
            return "morning";
        } else {
            if ($time > 12 && $time <= 17) {
                return "afternoon";
            } else {
                if ($time > 17) {
                    return "night";
                }
            }
        }
    }
}
if (!function_exists('filterQuery')) {
    function filterQuery($model, $request, $fields, $switch = false, $iLike = false)
    {
        if ($switch) {
            if (count($request) > 0) {
                foreach ($request as $key => $value) {
                    if (in_array($key, $fields)) {
                        if ($iLike) {
                            $model = $model->where($key, 'iLike', '%' . $value . '%');
                        } else {
                            $model = $model->where($key, $value);
                        }
                    } else {
                        throw new Exception('Parameter tidak ditemukan');
                    }
                }
            }
        } else {
            if (!empty($request->query())) {
                foreach ($request->query() as $key => $value) {
                    if (in_array($key, $fields)) {
                        if ($iLike) {
                            $model = $model->where($key, 'iLike', '%' . $value . '%');
                        } else {
                            $model = $model->where($key, $value);
                        }
                    } else {
                        throw new Exception('Parameter tidak ditemukan');
                    }
                }
            }
        }
        return $model;
    }
}

if (!function_exists('randomFileName')) {
    function randomFileName($fileName, $file)
    {
        $fileName =  $fileName . '_' . time() . rand(0, 99999999999) . '_' . rand(0, 99999999999) . '.' . $file->getClientOriginalExtension();
        return $fileName;
    }
}

if (!function_exists('getSetting')) {
    function getSetting($optionName, $instantGet = true)
    {
        $setting = Setting::where('key', $optionName)->first();
        return $instantGet ? $setting->value : $setting;
    }
}
if (!function_exists('mappingAccess')) {
    function mappingAccess()
    {
        $userAccess = getInfoLogin()->userAccess->map(function ($item) {
            return $item->study_program_id;
        });
        return count($userAccess) > 0 ? $userAccess : null;
    }
}

if (!function_exists('idDay')) {
    function idDay($dayOfWeek)
    {
        return [
            'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'
        ][$dayOfWeek - 1];
    }
}
if (!function_exists('setEnv')) {
    function setEnv($key, $value)
    {
        file_put_contents(app()->environmentFilePath(), str_replace(
            $key . '=' . env($value),
            $key . '=' . $value,
            file_get_contents(app()->environmentFilePath())
        ));
    }
}
if (!function_exists('generateUuid4')) {
    function generateUuid4()
    {
        return Uuid::uuid4();
    }
}
