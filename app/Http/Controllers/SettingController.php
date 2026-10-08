<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class SettingController extends Controller {
    public function index() {
        return view('settings.index');
    }
    public function update(Request $request) {
        if ($request->has('theme')) {
            session(['theme' => $request->theme]);
        }
        if ($request->has('language')) {
            session(['language' => $request->language]);
        }
        
        return back()->with('success', __('Pengaturan berhasil disimpan!'));
    }
}
