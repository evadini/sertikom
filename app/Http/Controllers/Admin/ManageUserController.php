<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class ManageUserController extends Controller
{
    // Tampilkan semua user aktif + user yang dihapus (soft deleted)
    public function index(Request $request)
    {
        $search = $request->search;

        $admins = User::where('role', 'admin')
            ->when($search, fn($q) => $q->where('name', 'like', "%$search%"))
            ->latest()->get();

        $pembeli = User::where('role', 'pembeli')
            ->when($search, fn($q) => $q->where('name', 'like', "%$search%"))
            ->latest()->get();

        $panitia = User::where('role', 'panitia')
            ->with(['approvedApplication.ukm'])
            ->when($search, fn($q) => $q->where('name', 'like', "%$search%"))
            ->latest()
            ->get();

        $totalUsers      = User::count();
        $totalAdmins     = User::where('role', 'admin')->count();
        $totalOrganizers = User::where('role', 'panitia')->count();
        $totalCustomers  = User::where('role', 'pembeli')->count();
        $deletedUsers    = User::onlyTrashed()->get();

        return view('Admin.ManageUser', compact(
            'admins', 'pembeli', 'panitia',
            'totalUsers', 'totalAdmins', 'totalOrganizers', 'totalCustomers', 'deletedUsers'
        ));
    }

    // Update — hanya field yang diisi saja yang diupdate
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'         => 'nullable|string|max:255',
            'nim'          => 'nullable|string|max:255|unique:users,nim,' . $user->getKey() . ',nim',
            'phone_number' => 'nullable|string|max:20',
            'role'         => 'nullable|in:pembeli,panitia,admin',
            'password'     => 'nullable|string|min:8',
        ]);

        if ($request->filled('name'))
            $user->name = $request->name;

        if ($request->filled('nim'))
            $user->nim = $request->nim;

        if ($request->filled('phone_number'))
            $user->phone_number = $request->phone_number;

        if ($request->filled('role'))
            $user->role = $request->role;

        if ($request->filled('password'))
            $user->password = Hash::make($request->password);

        $user->save();

        return redirect()->route('admin.users')
            ->with('success', 'User berhasil diupdate!');
    }

    // Soft delete — data tidak hilang, hanya ditandai deleted_at
    public function destroy(User $user)
{
    if ($user->getKey() === (string) Auth::id()) {
        return redirect()->route('admin.users')
            ->with('error', 'Tidak bisa menghapus akun sendiri!');
    }

    if ($user->role === 'admin') {
        return redirect()->route('admin.users')
            ->with('error', 'Tidak bisa menghapus admin lain!');
    }

    $user->delete();

    return redirect()->route('admin.users')
        ->with('success', 'User berhasil dihapus!');
}

    // Pulihkan user yang di-soft delete
    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('admin.users')->with('success', 'User berhasil dipulihkan!');
    }

    // Hapus permanen
    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();

        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus permanen!');
    }
}
