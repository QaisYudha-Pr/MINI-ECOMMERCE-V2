<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemShop;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ItemShopController extends Controller
{
    public function public()
    {
        $items = ItemShop::latest()->paginate(8);
        return view('item-shop.public', compact('items'));
    }

    public function show(ItemShop $itemShop)
    {
        $reviews = $itemShop->reviews()->latest()->get();
        return view('item-shop.show', compact('itemShop', 'reviews'));
    }

    public function index()
    {
        $this->authorize('viewAny', ItemShop::class);
        $user = auth()->user();

        $items = $user->hasRole('admin')
            ? ItemShop::latest()->paginate(8)
            : $user->itemShops()->latest()->paginate(8);

        return view('item-shop.admin', compact('items'));
    }

    public function create()
    {
        $this->authorize('create', ItemShop::class);
        return view('item-shop.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', ItemShop::class);

        $validated = $request->validate([
            'nama_barang' => 'required|string|min:3|max:255',
            'harga' => 'required|numeric|min:0|max:999999999',
            'deskripsi' => 'required|string|min:10|max:5000',
            'gambar' => 'nullable|string',
        ], [
            'nama_barang.required' => 'Nama produk tidak boleh kosong',
            'nama_barang.min' => 'Nama produk minimal 3 karakter',
            'nama_barang.max' => 'Nama produk maksimal 255 karakter',
            'harga.required' => 'Harga tidak boleh kosong',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga tidak boleh negatif',
            'harga.max' => 'Harga terlalu besar',
            'deskripsi.required' => 'Deskripsi tidak boleh kosong',
            'deskripsi.min' => 'Deskripsi minimal 10 karakter',
            'deskripsi.max' => 'Deskripsi maksimal 5000 karakter',
        ]);

        $data = [
            'nama_barang' => $validated['nama_barang'],
            'harga' => $validated['harga'],
            'deskripsi' => $validated['deskripsi'],
            'user_id' => auth()->id(),
        ];

        if ($request->filled('gambar')) {
            $data['gambar'] = $this->uploadBase64($request->gambar);
        }

        ItemShop::create($data);

        return redirect()->route('item-shop.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(ItemShop $itemShop)
    {
        $this->authorize('update', $itemShop);
        return view('item-shop.edit', compact('itemShop'));
    }

    public function update(Request $request, ItemShop $itemShop)
    {
        $this->authorize('update', $itemShop);

        $validated = $request->validate([
            'nama_barang' => 'required|string|min:3|max:255',
            'harga' => 'required|numeric|min:0|max:999999999',
            'deskripsi' => 'required|string|min:10|max:5000',
            'gambar' => 'nullable|string',
        ], [
            'nama_barang.required' => 'Nama produk tidak boleh kosong',
            'nama_barang.min' => 'Nama produk minimal 3 karakter',
            'nama_barang.max' => 'Nama produk maksimal 255 karakter',
            'harga.required' => 'Harga tidak boleh kosong',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga tidak boleh negatif',
            'harga.max' => 'Harga terlalu besar',
            'deskripsi.required' => 'Deskripsi tidak boleh kosong',
            'deskripsi.min' => 'Deskripsi minimal 10 karakter',
            'deskripsi.max' => 'Deskripsi maksimal 5000 karakter',
        ]);

        $data = [
            'nama_barang' => $validated['nama_barang'],
            'harga' => $validated['harga'],
            'deskripsi' => $validated['deskripsi'],
        ];

        if ($request->filled('gambar')) {
            if ($itemShop->gambar && File::exists(public_path($itemShop->gambar))) {
                File::delete(public_path($itemShop->gambar));
            }
            $data['gambar'] = $this->uploadBase64($request->gambar);
        }

        $itemShop->update($data);

        return redirect()->route('item-shop.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(ItemShop $itemShop)
    {
        $this->authorize('delete', $itemShop);

        if ($itemShop->gambar && File::exists(public_path($itemShop->gambar))) {
            File::delete(public_path($itemShop->gambar));
        }

        $itemShop->delete();

        return back()->with('success', 'Produk berhasil dihapus!');
    }

    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5',
        ]);

        \App\Models\Review::create([
            'user_id' => auth()->id(),
            'item_shop_id' => $id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Ulasan berhasil dikirim, Mantappp !');
    }

    /**
     * Helper Function untuk memproses Base64 ke File Fisik
     */
    private function uploadBase64($base64Data)
    {
        try {
            // Format: data:image/png;base64,ABC...
            $image_parts = explode(";base64,", $base64Data);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);

            $fileName = 'item_' . Str::random(10) . '_' . time() . '.' . $image_type;
            $folderPath = 'uploads/items/';

            // Pastikan folder ada
            if (!File::isDirectory(public_path($folderPath))) {
                File::makeDirectory(public_path($folderPath), 0777, true, true);
            }

            File::put(public_path($folderPath . $fileName), $image_base64);

            return $folderPath . $fileName;
        } catch (\Exception $e) {
            return null;
        }
    }
}
