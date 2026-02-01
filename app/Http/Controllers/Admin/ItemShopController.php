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
        $tab = $request->query('tab', 'all'); // 'all', 'mine', 'sellers'

        $query = ItemShop::query();

        // Policy-based filtering
        if (!$user->hasRole('admin')) {
            $query->where('user_id', $user->id);
        } else {
            // Admin can filter by tabs
            if ($tab === 'mine') {
                $query->where('user_id', $user->id);
            } elseif ($tab === 'sellers') {
                $query->where('user_id', '!=', $user->id);
            }
        }

        if ($search) {
            $words = array_filter(explode(' ', strtolower($search)));
            $query->where(function ($q) use ($words) {
                foreach ($words as $word) {
                    $q->where(function ($sub) use ($word) {
                        $sub->whereRaw('LOWER(nama_barang) like ?', ["%{$word}%"])
                            ->orWhereRaw('LOWER(kategori) like ?', ["%{$word}%"])
                            ->orWhereRaw('LOWER(deskripsi) like ?', ["%{$word}%"]);
                    });
                }
            });
        }

        if ($request->query('category')) {
            $query->where('kategori', $request->query('category'));
        }

        $items = $query->with('user')->orderBy('id', 'desc')->paginate(8)->withQueryString();

        // Get all unique categories for filter
        $categories = ItemShop::distinct()->pluck('kategori')->filter()->values();

        // Stock count logic
        $lowStockQuery = ItemShop::where('stok', '<=', 5);
        if (!$user->hasRole('admin')) {
            $lowStockQuery->where('user_id', $user->id);
        }
        $lowStockCount = $lowStockQuery->count();

        // Admin Stats
        $adminStats = [];
        if ($user->hasRole('admin')) {
            $adminStats = [
                'total_mine' => ItemShop::where('user_id', $user->id)->count(),
                'total_sellers' => ItemShop::where('user_id', '!=', $user->id)->count(),
            ];
        }

        if ($request->ajax()) {
            return response()->json([
                'items' => $items->through(function($item) use ($user) {
                    return [
                        'id' => $item->id,
                        'nama_barang' => $item->nama_barang,
                        'gambar' => $item->gambar ? asset($item->gambar) : null,
                        'kategori' => $item->kategori,
                        'stok' => $item->stok,
                        'harga' => $item->harga,
                        'seller' => $item->user->nama_toko ?? $item->user->name,
                        'is_admin_product' => $item->user->hasRole('admin'),
                        'can_update' => $user->can('update', $item),
                        'can_delete' => $user->can('delete', $item),
                        'show_url' => route('shop.show', $item->id),
                        'edit_url' => route('item-shop.edit', $item->id),
                        'delete_url' => route('item-shop.destroy', $item->id),
                    ];
                })->items(),
                'pagination' => (string) $items->links()
            ]);
        }

        return view('admin.item-shop.index', compact('items', 'lowStockCount', 'adminStats', 'tab', 'categories'));
    }

    public function quickStock(Request $request, ItemShop $itemShop)
    {
        $this->authorize('update', $itemShop);
        
        $request->validate(['type' => 'required|in:plus,minus']);
        
        if ($request->type === 'plus') {
            $itemShop->increment('stok');
        } else {
            if ($itemShop->stok > 0) {
                $itemShop->decrement('stok');
            }
        }

        return response()->json(['success' => true, 'new_stock' => $itemShop->stok]);
    }

    public function export()
    {
        $user = auth()->user();
        $items = $user->hasRole('admin') ? ItemShop::all() : ItemShop::where('user_id', $user->id)->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=inventory_export_" . date('Y-m-d') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Nama Barang', 'Kategori', 'Stok', 'Harga', 'Seller'];

        $callback = function() use($items, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($items as $item) {
                fputcsv($file, [
                    $item->id,
                    $item->nama_barang,
                    $item->kategori,
                    $item->stok,
                    $item->harga,
                    $item->user->nama_toko ?? $item->user->name,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function create()
    {
        $this->authorize('create', ItemShop::class);
        $categories = ItemShop::distinct()->pluck('kategori')->filter()->values();
        return view('admin.item-shop.create', compact('categories'));
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
        $categories = ItemShop::distinct()->pluck('kategori')->filter()->values();
        return view('admin.item-shop.edit', compact('itemShop', 'categories'));
    }

    public function update(UpdateItemShopRequest $request, ItemShop $itemShop)
    {
        $this->authorize('update', $itemShop);

        $data = $request->validated();

        if ($request->filled('gambar')) {
            $this->fileService->deleteFile($itemShop->gambar);
            $data['gambar'] = $this->fileService->uploadBase64($request->gambar);
        } else {
            // Pastikan field gambar tidak diupdate jika kosong
            unset($data['gambar']);
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
