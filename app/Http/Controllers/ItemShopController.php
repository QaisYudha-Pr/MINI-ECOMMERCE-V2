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

    public function index(Request $request)
    {
        $this->authorize('viewAny', ItemShop::class);
        $user = auth()->user();
        $search = $request->query('search');

        $query = $user->hasRole('admin')
            ? ItemShop::latest()
            : $user->itemShops()->latest();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        $items = $query->paginate(8)->withQueryString();

        // Hitung Low Stock sesuai role
        $lowStockQuery = ItemShop::where('stok', '<=', 5);
        if (!$user->hasRole('admin')) {
            $lowStockQuery->where('user_id', $user->id);
        }
        $lowStockCount = $lowStockQuery->count();

        // Jika request datang dari AJAX/Fetch, balikin JSON
        if ($request->ajax()) {
            return response()->json([
                'items' => $items->items(),
                'pagination' => (string) $items->links()
            ]);
        }

        return view('item-shop.admin', compact('items', 'lowStockCount'));
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
            'stok' => 'required|numeric|min:1|max:999999',
            'kategori' => 'required|string', // Tambahkan validasi kategori
        ], [
            // ... (pesan error kamu) ...
            'stok.required' => 'Stok awal harus diisi',
            'kategori.required' => 'Kategori harus dipilih bolo!',
        ]);

        $data = [
            'nama_barang' => $validated['nama_barang'],
            'harga' => $validated['harga'],
            'deskripsi' => $validated['deskripsi'],
            'stok' => $validated['stok'],      // <--- Tambahkan ini
            'kategori' => $validated['kategori'],
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
            'stok'  => 'required|numeric|min:1|max:999999',
            'kategori' => 'required|string',
        ]);

        $data = [
            'nama_barang' => $validated['nama_barang'],
            'harga' => $validated['harga'],
            'deskripsi' => $validated['deskripsi'],
            'stok' => $validated['stok'],
            'kategori' => $validated['kategori'],
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

    public function stats()
    {
        return response()->json(ItemShop::select('id', 'total_terjual', 'stok')->get());
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
