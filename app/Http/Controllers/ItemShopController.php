<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemShop;

class ItemShopController extends Controller
{
    public function public()
    {
        $items = ItemShop::latest()->paginate(8);
        return view('item-shop.public', compact('items'));
    }

    public function show(ItemShop $itemShop)
    {
        return view('item-shop.show', compact('itemShop'));
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

        // Validasi input
        $validated = $request->validate([
            'nama_barang' => 'required|string|min:3|max:255',
            'harga' => 'required|numeric|min:0|max:999999999',
            'deskripsi' => 'required|string|min:10|max:5000',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nama_barang.required' => 'Nama produk tidak boleh kosong',
            'nama_barang.min' => 'Nama produk minimal 3 karakter',
            'nama_barang.max' => 'Nama produk maksimal 255 karakter',
            'harga.required' => 'Harga tidak boleh kosong',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga tidak boleh negatif',
            'deskripsi.required' => 'Deskripsi tidak boleh kosong',
            'deskripsi.min' => 'Deskripsi minimal 10 karakter',
            'deskripsi.max' => 'Deskripsi maksimal 5000 karakter',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.mimes' => 'Format gambar harus JPEG, PNG, JPG, atau GIF',
            'gambar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        $data = [
            'nama_barang' => $validated['nama_barang'],
            'harga' => $validated['harga'],
            'deskripsi' => $validated['deskripsi'],
            'user_id' => auth()->id(),
        ];

        // Upload gambar jika ada
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $data['gambar'] = 'uploads/' . $filename;
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

        // Validasi input
        $validated = $request->validate([
            'nama_barang' => 'required|string|min:3|max:255',
            'harga' => 'required|numeric|min:0|max:999999999',
            'deskripsi' => 'required|string|min:10|max:5000',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nama_barang.required' => 'Nama produk tidak boleh kosong',
            'nama_barang.min' => 'Nama produk minimal 3 karakter',
            'nama_barang.max' => 'Nama produk maksimal 255 karakter',
            'harga.required' => 'Harga tidak boleh kosong',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga tidak boleh negatif',
            'deskripsi.required' => 'Deskripsi tidak boleh kosong',
            'deskripsi.min' => 'Deskripsi minimal 10 karakter',
            'deskripsi.max' => 'Deskripsi maksimal 5000 karakter',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.mimes' => 'Format gambar harus JPEG, PNG, JPG, atau GIF',
            'gambar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        $data = [
            'nama_barang' => $validated['nama_barang'],
            'harga' => $validated['harga'],
            'deskripsi' => $validated['deskripsi'],
        ];

        // Upload gambar baru jika ada
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($itemShop->gambar && file_exists(public_path($itemShop->gambar))) {
                unlink(public_path($itemShop->gambar));
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $data['gambar'] = 'uploads/' . $filename;
        }

        $itemShop->update($data);

        return redirect()->route('item-shop.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(ItemShop $itemShop)
    {
        $this->authorize('delete', $itemShop);

        // Hapus gambar jika ada
        if ($itemShop->gambar && file_exists(public_path($itemShop->gambar))) {
            unlink(public_path($itemShop->gambar));
        }

        $itemShop->delete();

        return back()->with('success', 'Produk berhasil dihapus!');
    }
}
