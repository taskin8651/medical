<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::getSettings();
        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::getSettings();

        $data = $request->except(['_token']);

        // File uploads
        foreach (['logo','favicon','loader','og_image','popup_image'] as $file) {
            if ($request->hasFile($file)) {
                $data[$file] = $request->file($file)->store('settings','public');
            } else {
                unset($data[$file]);
            }
        }

        $setting->fill($data)->save();

        return back()->with('success','Settings Updated');
    }
}
