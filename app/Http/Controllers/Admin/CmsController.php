<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CmsController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::all()->pluck('value', 'key');
        return view('admin.cms.index', compact('settings'));
    }

    public function updateLogo(Request $request)
    {
        $request->validate([
            'site_logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('site_logo')) {
            $logo = $request->file('site_logo');
            $logoName = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
            
            // Store in public/uploads/cms
            $logo->move(public_path('uploads/cms'), $logoName);
            $path = 'uploads/cms/' . $logoName;

            // Delete old logo if exists
            $oldLogo = SiteSetting::where('key', 'site_logo')->first();
            if ($oldLogo && $oldLogo->value && file_exists(public_path($oldLogo->value))) {
                unlink(public_path($oldLogo->value));
            }

            SiteSetting::updateOrCreate(
                ['key' => 'site_logo'],
                ['value' => $path]
            );
        }

        return redirect()->back()->with('success', 'Logo berhasil diperbarui!');
    }

    public function updateText(Request $request)
    {
        $fields = $request->except(['_token', 'site_logo', 'hero_banner', 'about_image']);
        
        foreach ($fields as $key => $value) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui!');
    }

    public function updateImages(Request $request)
    {
        $request->validate([
            'hero_banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'about_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        foreach (['hero_banner', 'about_image'] as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $name = $field . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/cms'), $name);
                $path = 'uploads/cms/' . $name;

                // Delete old
                $old = SiteSetting::where('key', $field)->first();
                if ($old && $old->value && file_exists(public_path($old->value))) {
                    unlink(public_path($old->value));
                }

                SiteSetting::updateOrCreate(['key' => $field], ['value' => $path]);
            }
        }

        return redirect()->back()->with('success', 'Gambar berhasil diperbarui!');
    }
}
