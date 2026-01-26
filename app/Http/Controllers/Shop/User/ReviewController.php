<?php

namespace App\Http\Controllers\Shop\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\ItemShop;

class ReviewController extends Controller
{
    public function store(Request $request, ItemShop $itemShop)
    {
        $this->authorize('create', Review::class);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:1|max:1000',
            'photo' => 'nullable|image|max:2048', // Max 2MB
            'cropped_photo' => 'nullable|string',
        ], [
            'rating.required' => 'Rating harus dipilih',
            'rating.min' => 'Rating minimal 1 bintang',
            'rating.max' => 'Rating maksimal 5 bintang',
            'comment.required' => 'Komentar tidak boleh kosong',
            'comment.min' => 'Komentar minimal 10 karakter',
            'comment.max' => 'Komentar maksimal 1000 karakter',
            'photo.image' => 'File harus berupa gambar',
            'photo.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        $photoPath = null;

        // Prioritaskan Cropped Photo (Base64)
        if ($request->filled('cropped_photo')) {
            $image_data = $request->input('cropped_photo');
            
            if (preg_match('/^data:image\/(\w+);base64,/', $image_data, $type)) {
                $image_data = substr($image_data, strpos($image_data, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, etc

                if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
                    throw new \Exception('Invalid image type');
                }

                $image_data = base64_decode($image_data);

                if ($image_data === false) {
                    throw new \Exception('base64_decode failed');
                }
                
                $filename = 'review_' . time() . '_' . uniqid() . '.' . $type;
                $directory = public_path('uploads/reviews');
                
                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }

                file_put_contents($directory . '/' . $filename, $image_data);
                $photoPath = 'uploads/reviews/' . $filename;
            }
        } 
        // Fallback ke normal upload jika crop gagal/kosong
        elseif ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = 'review_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/reviews'), $filename);
            $photoPath = 'uploads/reviews/' . $filename;
        }

        Review::create([
            'item_shop_id' => $itemShop->id,
            'user_id' => auth()->id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'photo' => $photoPath,
        ]);

        return back()->with('success', 'Review berhasil ditambahkan!');
    }

    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);

        $review->delete();

        return back()->with('success', 'Review berhasil dihapus!');
    }
}
