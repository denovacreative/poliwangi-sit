<?php

namespace App\Http\Controllers\Api\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Exception;

class SettingController extends Controller
{
    const PICTURE_PATH = 'storage/images/app/';
    public function getSetting() 
    {
        $setting = Setting::all();
        $data = [];

        foreach($setting as $item) {
            if($item->key == 'logo' || $item->key == 'favicon'){
                $data[$item->key] = asset('storage/images/app/'. $item->value);
            } else {
                $data[$item->key] = $item->value;
            }
        }

        $data['manifest'] = asset('storage/manifest.json');

        return response()->json([
            'message' => 'success',
            'data' => $data
        ]);
    }

    public function updateSetting(Request $request)
    {
        try{
            $name = $request->name;
            $logo = $request->logo;
            $icon = $request->icon;
            $description = $request->description;
            
            Setting::where('key', 'college_name')->update([
                'value' => $name
            ]);
            Setting::where('key', 'college_description')->update([
                'value' => $description
            ]);
            if (isset($logo) && $request->hasFile('logo')) {
                $pictureName = uniqid('logo-') . '.' . $request->file('logo')->getClientOriginalExtension();
                $request->file('logo')->move(public_path(self::PICTURE_PATH), $pictureName);
                
                Setting::where('key', 'logo')->update([
                    'value' => $pictureName
                ]);
            }

            if (isset($icon) && $request->hasFile('icon')) {
                $iconName = uniqid('icon-') . '.' . $request->file('icon')->getClientOriginalExtension();
                $request->file('icon')->move(public_path(self::PICTURE_PATH), $iconName);
                
                Setting::where('key', 'favicon')->update([
                    'value' => $iconName
                ]);
            }

            return $this->successResponse('Berhasil update setting aplikasi');
        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

}
