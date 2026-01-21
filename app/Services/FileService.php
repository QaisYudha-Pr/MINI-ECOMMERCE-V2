<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FileService
{
    /**
     * Upload Base64 image to physical file
     * Reusable Up gambar tinggal panggil aja kaya contoh gini
     * if ($request->filled('gambar')) {
     * Tinggal panggil service-nya, nggak perlu pusing mikirin cara convert base64
     * $data['gambar'] = $this->fileService->uploadBase64($request->gambar);
     * } 
     */
    public function uploadBase64($base64Data, $folderPath = 'uploads/items/', $prefix = 'item_')
    {
        try {
            if (!$base64Data) {
                return null;
            }

            // Format: data:image/png;base64,ABC...
            $image_parts = explode(";base64,", $base64Data);
            if (count($image_parts) < 2) {
                return null;
            }

            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1] ?? 'png';
            $image_base64 = base64_decode($image_parts[1]);

            $fileName = $prefix . Str::random(10) . '_' . time() . '.' . $image_type;

            // Ensure directory exists
            if (!File::isDirectory(public_path($folderPath))) {
                File::makeDirectory(public_path($folderPath), 0777, true, true);
            }

            File::put(public_path($folderPath . $fileName), $image_base64);

            return $folderPath . $fileName;
        } catch (\Exception $e) {
            \Log::error('Upload failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete file if exists
     */
    public function deleteFile($path)
    {
        if ($path && File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}
