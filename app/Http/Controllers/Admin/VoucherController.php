<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Voucher::query();
        
        // Admin sees all, seller sees only their own
        if (!$user->hasRole('admin')) {
            $query->where('seller_id', $user->id);
        }
        
        // Filter by status
        if ($request->status === 'active') {
            $query->active();
        } elseif ($request->status === 'expired') {
            $query->where(function ($q) {
                $q->where('expires_at', '<', now())
                    ->orWhere('is_active', false);
            });
        }
        
        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('code', 'like', "%{$request->search}%")
                    ->orWhere('name', 'like', "%{$request->search}%");
            });
        }
        
        $vouchers = $query->latest()->paginate(10);
        
        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('admin.vouchers.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:percentage,fixed,free_shipping',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'per_user_limit' => 'required|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
        ]);
        
        // Only admin can create platform vouchers
        if (!$user->hasRole('admin')) {
            $validated['seller_id'] = $user->id;
        } else {
            $validated['seller_id'] = $request->input('seller_id'); // null for platform voucher
        }
        
        $validated['code'] = strtoupper($validated['code']);
        
        Voucher::create($validated);
        
        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher berhasil dibuat bolo!');
    }

    public function edit(Voucher $voucher)
    {
        $this->authorize('update', $voucher);
        
        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $this->authorize('update', $voucher);
        
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('vouchers')->ignore($voucher->id)],
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:percentage,fixed,free_shipping',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'per_user_limit' => 'required|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'boolean',
        ]);
        
        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->boolean('is_active');
        
        $voucher->update($validated);
        
        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher berhasil diupdate bolo!');
    }

    public function destroy(Voucher $voucher)
    {
        $this->authorize('delete', $voucher);
        
        $voucher->delete();
        
        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher berhasil dihapus!');
    }

    // Toggle active status
    public function toggle(Voucher $voucher)
    {
        $this->authorize('update', $voucher);
        
        $voucher->update(['is_active' => !$voucher->is_active]);
        
        return back()->with('success', 'Status voucher berhasil diubah!');
    }
}
