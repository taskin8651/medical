<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::first();

        if (!$setting) {
            $setting = new Setting();
        }

        $data = $request->all();

        // File uploads
        foreach (['logo','favicon','loader','og_image','popup_image'] as $file) {
            if ($request->hasFile($file)) {
                $data[$file] = $request->file($file)->store('settings','public');
            } else {
                unset($data[$file]);
            }
        }

        $setting->update($data);

        return back()->with('success','Settings Updated');
    }
}