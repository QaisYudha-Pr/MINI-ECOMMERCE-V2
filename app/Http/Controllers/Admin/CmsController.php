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
        $couriers = \App\Models\Courier::all();
        return view('admin.cms.index', compact('settings', 'couriers'));
    }

    public function updateLogo(Request $request)
    {
        if ($request->filled('cropped_site_logo')) {
            $imageData = $request->input('cropped_site_logo');
            $fileName = 'logo_' . time() . '.jpg';
            $path = 'uploads/cms/' . $fileName;
            
            // Handle Base64
            $data = substr($imageData, strpos($imageData, ',') + 1);
            $data = base64_decode($data);
            
            if (!file_exists(public_path('uploads/cms'))) {
                mkdir(public_path('uploads/cms'), 0777, true);
            }
            
            file_put_contents(public_path($path), $data);

            // Delete old
            $oldLogo = SiteSetting::where('key', 'site_logo')->first();
            if ($oldLogo && $oldLogo->value && file_exists(public_path($oldLogo->value))) {
                unlink(public_path($oldLogo->value));
            }

            SiteSetting::updateOrCreate(['key' => 'site_logo'], ['value' => $path]);
            return redirect()->back()->with('success', 'Logo berhasil diperbarui!');
        }

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
        foreach (['hero_banner', 'about_image'] as $field) {
            // Priority for Cropped Image
            if ($request->filled('cropped_' . $field)) {
                $imageData = $request->input('cropped_' . $field);
                $fileName = $field . '_' . time() . '.jpg';
                $path = 'uploads/cms/' . $fileName;
                
                $data = substr($imageData, strpos($imageData, ',') + 1);
                $data = base64_decode($data);
                
                if (!file_exists(public_path('uploads/cms'))) {
                    mkdir(public_path('uploads/cms'), 0777, true);
                }
                
                file_put_contents(public_path($path), $data);

                $old = SiteSetting::where('key', $field)->first();
                if ($old && $old->value && file_exists(public_path($old->value))) {
                    unlink(public_path($old->value));
                }

                SiteSetting::updateOrCreate(['key' => $field], ['value' => $path]);
                continue;
            }

            // Fallback to standard upload
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

    public function storeCourier(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'service_name' => 'required|string|max:255',
            'multiplier' => 'required|numeric|min:0',
            'base_extra_cost' => 'required|numeric|min:0',
            'max_distance' => 'nullable|numeric|min:0',
            'max_weight' => 'nullable|numeric|min:0',
            'icon' => 'nullable|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        \App\Models\Courier::create($request->all());

        return back()->with('success', 'Kurir berhasil ditambahkan.');
    }

    public function updateCourier(Request $request, \App\Models\Courier $courier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'service_name' => 'required|string|max:255',
            'multiplier' => 'required|numeric|min:0',
            'base_extra_cost' => 'required|numeric|min:0',
            'max_distance' => 'nullable|numeric|min:0',
            'max_weight' => 'nullable|numeric|min:0',
            'icon' => 'nullable|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $courier->update($request->all());

        return back()->with('success', 'Data kurir berhasil diperbarui.');
    }

    public function toggleCourier(\App\Models\Courier $courier)
    {
        $courier->update(['is_active' => !$courier->is_active]);
        return back()->with('success', 'Status kurir berhasil diubah.');
    }

    public function deleteCourier(\App\Models\Courier $courier)
    {
        $courier->delete();
        return back()->with('success', 'Kurir berhasil dihapus.');
    }
}
