<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $withdrawals = Withdrawal::with('user')->latest()->get();
        } else {
            $withdrawals = Withdrawal::where('user_id', $user->id)->latest()->get();
        }

        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->balance < $request->amount) {
            return back()->with('error', 'Saldo jualan tidak mencukupi bolo! (Saldo Platform tidak bisa ditarik lewat sini)');
        }

        $request->validate([
            'amount' => 'required|numeric|min:10000',
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'account_name' => 'required|string',
        ]);

        $user = Auth::user();

        if ($user->balance < $request->amount) {
            return back()->with('error', 'Saldo tidak mencukupi bolo!');
        }

        DB::transaction(function () use ($request, $user) {
            // Kita potong saldo di depan biar "aman" (locked)
            $user->decrement('balance', $request->amount);

            Withdrawal::create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'account_name' => $request->account_name,
                'status' => 'pending',
            ]);
        });

        return back()->with('success', 'Permintaan penarikan berhasil dikirim!');
    }

    public function update(Request $request, Withdrawal $withdrawal)
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:approved,rejected,completed',
            'admin_note' => 'nullable|string',
            'reference_proof' => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request, $withdrawal) {
            $oldStatus = $withdrawal->status;
            $newStatus = $request->status;

            // Jika ditolak, kembalikan saldo
            if ($newStatus === 'rejected' && $oldStatus !== 'rejected') {
                $withdrawal->user->increment('balance', $withdrawal->amount);
            }

            $data = [
                'status' => $newStatus,
                'admin_note' => $request->admin_note,
            ];

            if ($request->hasFile('reference_proof')) {
                $path = $request->file('reference_proof')->store('withdrawals', 'public');
                $data['reference_proof'] = $path;
            }

            $withdrawal->update($data);
        });

        return back()->with('success', 'Status penarikan berhasil diperbarui!');
    }
}
