<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Exports\RevenueExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CmsController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::all()->pluck('value', 'key');
        $couriers = \App\Models\Courier::all();

        // Financial Metrics (Aligned with Dashboard)
        $statuses = ['paid', 'success', 'shipped', 'completed'];
        
        // Gabungan Pendapatan Platform (Admin Fee + Komisi Seller)
        $totalAdminFee = \App\Models\Transaction::whereIn('status', $statuses)->sum('admin_fee');
        
        // Hitung Komisi Seller yang sudah tersimpan (jika ada logic history-nya)
        // Sementara kita asumsikan pendapatan dikelola di Platform Balance Admin
        $platformEarnings = \App\Models\User::role('admin')->first()->platform_balance ?? 0;
        
        // Sum items (total_price - shipping_fee - admin_fee = items total)
        $totalSalesItems = \App\Models\Transaction::whereIn('status', $statuses)
            ->selectRaw('SUM(total_price - shipping_fee - admin_fee) as items_sum')
            ->value('items_sum') ?? 0;

        return view('admin.cms.index', compact('settings', 'couriers', 'totalAdminFee', 'totalSalesItems', 'platformEarnings'));
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

            if (request()->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Logo berhasil diperbarui!', 'path' => asset($path)]);
            }
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

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Pengaturan berhasil diperbarui!']);
        }
        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui!');
    }

    public function updateImages(Request $request)
    {
        // Handle Sliders (Multiple / Cropped)
        if ($request->hasFile('home_sliders') || $request->hasFile('home_slider') || $request->filled('cropped_home_slider')) {
            $sliders = SiteSetting::where('key', 'home_sliders')->first();
            $sliderArray = $sliders ? json_decode($sliders->value, true) : [];

            // Handle Cropped One
            if ($request->filled('cropped_home_slider')) {
                $imageData = $request->input('cropped_home_slider');
                $filename = 'slider_' . time() . '_' . rand(100, 999) . '.jpg';
                $path = 'uploads/cms/' . $filename;
                
                $data = substr($imageData, strpos($imageData, ',') + 1);
                $data = base64_decode($data);
                
                if (!file_exists(public_path('uploads/cms'))) {
                    mkdir(public_path('uploads/cms'), 0777, true);
                }
                
                file_put_contents(public_path($path), $data);
                $sliderArray[] = $path;
            } 
            // Fallback to standard multiple upload
            elseif ($request->hasFile('home_sliders')) {
                foreach ($request->file('home_sliders') as $file) {
                    $filename = 'slider_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/cms'), $filename);
                    $sliderArray[] = 'uploads/cms/' . $filename;
                }
            }

            SiteSetting::updateOrCreate(['key' => 'home_sliders'], ['value' => json_encode($sliderArray)]);
            
            if (request()->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Slider berhasil ditambahkan!', 'sliders' => $sliderArray]);
            }
            return back()->with('success', 'Slider berhasil ditambahkan!');
        }

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

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Gambar berhasil diperbarui!']);
        }
        return redirect()->back()->with('success', 'Gambar berhasil diperbarui!');
    }

    public function updateSettings(Request $request)
    {
        $settings = $request->except('_token');
        $oldSettings = [];
        foreach ($settings as $key => $value) {
            $old = \App\Models\SiteSetting::where('key', $key)->first();
            $oldSettings[$key] = $old ? $old->value : null;
            \App\Models\SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // Cek jika ada perubahan komisi atau gratis ongkir
        if (isset($settings['seller_commission_percent']) && $settings['seller_commission_percent'] != $oldSettings['seller_commission_percent']) {
            $sellers = \App\Models\User::role('seller')->get();
            foreach ($sellers as $seller) {
                \App\Models\Notification::create([
                    'user_id' => $seller->id,
                    'title' => 'Perubahan Komisi Platform 📢',
                    'message' => "Halo bolo! Ada penyesuaian komisi platform menjadi {$settings['seller_commission_percent']}%. Cek detailnya di pengaturan toko ya.",
                    'type' => 'warning'
                ]);
            }
        }

        if (isset($settings['free_shipping_min_order']) || isset($settings['free_shipping_max_subsidy'])) {
            $users = \App\Models\User::all();
            foreach ($users as $user) {
                \App\Models\Notification::create([
                    'user_id' => $user->id,
                    'title' => 'Promo Gratis Ongkir Baru! 🚚',
                    'message' => "Makin hemat belanja di MiniQ! Nikmati subsidi ongkir hingga Rp" . number_format($settings['free_shipping_max_subsidy'] ?? 10000, 0, ',', '.') . " dengan minimal belanja Rp" . number_format($settings['free_shipping_min_order'] ?? 25000, 0, ',', '.') . ". Serbu bolo!",
                    'type' => 'info'
                ]);
            }
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Pengaturan berhasil diperbarui dan notifikasi terkirim!']);
        }
        return back()->with('success', 'Pengaturan berhasil diperbarui!');
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

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Kurir berhasil ditambahkan.']);
        }
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

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Data kurir berhasil diperbarui.']);
        }
        return back()->with('success', 'Data kurir berhasil diperbarui.');
    }

    public function deleteSlider(Request $request)
    {
        $path = $request->path;
        $sliders = SiteSetting::where('key', 'home_sliders')->first();
        if ($sliders) {
            $sliderArray = json_decode($sliders->value, true);
            if (($key = array_search($path, $sliderArray)) !== false) {
                unset($sliderArray[$key]);
                if (file_exists(public_path($path))) {
                    unlink(public_path($path));
                }
                $sliders->update(['value' => json_encode(array_values($sliderArray))]);
            }
        }
        
        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Slider berhasil dihapus!']);
        }
        return back()->with('success', 'Slider berhasil dihapus!');
    }

    public function toggleCourier(\App\Models\Courier $courier)
    {
        $courier->update(['is_active' => !$courier->is_active]);
        
        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Status kurir berhasil diubah.']);
        }
        return back()->with('success', 'Status kurir berhasil diubah.');
    }

    public function deleteCourier(\App\Models\Courier $courier)
    {
        $courier->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Kurir berhasil dihapus.']);
        }
        return back()->with('success', 'Kurir berhasil dihapus.');
    }

    public function exportRevenue()
    {
        return Excel::download(new RevenueExport, 'Laporan_Keuangan_MiniQ_' . date('d_M_Y') . '.xlsx');
    }
}
