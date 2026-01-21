<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('lihat-user');
        $users = User::paginate(8);
        return view('admin.user-manage', compact('users'));
    }

    public function create()
    {
        $this->authorize('tambah-user');
        return view('admin.user-create');
    }

    public function store(Request $request)
    {
        $this->authorize('tambah-user');
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|exists:roles,name', // role dari Spatie
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role); // assign role via Spatie

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        $this->authorize('edit-user');
        return view('admin.user-edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('edit-user');
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->id}",
            'role' => 'required|exists:roles,name',
            'password' => 'nullable|string|min:6|confirmed',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $user->syncRoles([$request->role]); // update role via Spatie

        // Sync individual permissions
        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions);
        } else {
            // If no permissions checked, clear all direct permissions
            $user->syncPermissions([]);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate');
    }

    public function destroy(User $user)
    {
        $this->authorize('hapus-user');
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }
}
