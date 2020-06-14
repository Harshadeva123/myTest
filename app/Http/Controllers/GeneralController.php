<?php

namespace App\Http\Controllers;

use App\SystemSetting;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function getAppVersion(Request $request){
        $app = $request['os'];

        if($app == null){
            return response()->json(['error' => 'Please provide OS!', 'statusCode' => -99]);
        }
        $version = SystemSetting::where('status',1)->first()->$app;
        if($version == null){
            return response()->json(['error' => 'Invalid!', 'statusCode' => -99]);
        }
        return response()->json(['success' => ['version'=>$version], 'statusCode' => 0]);

    }

    public function appVersion(Request $request){

        $setting = SystemSetting::where('status',1)->first();
        return view('setting.app_version')->with(['title'=>'App Version','setting'=>$setting]);
    }

    public function storeAppVersion(Request $request){
        $validationMessages = [
            'android.required' => 'Android version should be provided!',
            'ios.required' => 'Ios version should be provided!',
        ];

        $validator = \Validator::make($request->all(), [
            'android' => 'required',
            'ios' => 'required',
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $setting = SystemSetting::first();
        if($setting != null){
            if($setting->android != $request['android']){
                $setting->android_date = now();
            }
            if($setting->ios != $request['ios']){
                $setting->ios_date = now();
            }
            $setting->android = $request['android'];
            $setting->ios = $request['ios'];
            $setting->status = 1;
            $setting->save();
        }
        else{
            $setting = new SystemSetting();
            $setting->android = $request['android'];
            $setting->ios = $request['ios'];
            $setting->android_date = now();
            $setting->ios_date = now();
            $setting->statsu = 1;
            $setting->save();
        }
        return response()->json(['success' => 'success']);

    }

}
