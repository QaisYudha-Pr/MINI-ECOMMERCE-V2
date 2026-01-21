<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreItemShopRequest;
use App\Http\Requests\UpdateItemShopRequest;
use App\Models\ItemShop;
use App\Services\FileService;
use Illuminate\Http\Request;

class ItemShopController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
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
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        $items = $query->paginate(8)->withQueryString();

        // Stock count logic
        $lowStockQuery = ItemShop::where('stok', '<=', 5);
        if (!$user->hasRole('admin')) {
            $lowStockQuery->where('user_id', $user->id);
        }
        $lowStockCount = $lowStockQuery->count();

        if ($request->ajax()) {
            return response()->json([
                'items' => $items->items(),
                'pagination' => (string) $items->links()
            ]);
        }

        return view('admin.item-shop.index', compact('items', 'lowStockCount'));
    }

    public function create()
    {
        $this->authorize('create', ItemShop::class);
        return view('admin.item-shop.create');
    }

    public function store(StoreItemShopRequest $request)
    {
        $this->authorize('create', ItemShop::class);

        $data = $request->validated();
        $data['user_id'] = auth()->id();

        if ($request->filled('gambar')) {
            $data['gambar'] = $this->fileService->uploadBase64($request->gambar);
        }

        ItemShop::create($data);

        return redirect()->route('item-shop.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(ItemShop $itemShop)
    {
        $this->authorize('update', $itemShop);
        return view('admin.item-shop.edit', compact('itemShop'));
    }

    public function update(UpdateItemShopRequest $request, ItemShop $itemShop)
    {
        $this->authorize('update', $itemShop);

        $data = $request->validated();

        if ($request->filled('gambar')) {
            $this->fileService->deleteFile($itemShop->gambar);
            $data['gambar'] = $this->fileService->uploadBase64($request->gambar);
        }

        $itemShop->update($data);

        return redirect()->route('item-shop.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(ItemShop $itemShop)
    {
        $this->authorize('delete', $itemShop);

        $this->fileService->deleteFile($itemShop->gambar);
        $itemShop->delete();

        return back()->with('success', 'Produk berhasil dihapus!');
    }
}
