<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class SettingsController extends Controller
{
    // Menampilkan halaman settings pembeli
    public function index()
    {
        return view('Pembeli.Settings'); 
    }

    // Memproses update Profile Details
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->getKey() . ',nim'],
            'phone_number' => ['nullable', 'string', 'max:15'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->save();

        Auth::setUser($user->fresh());

        return redirect()->route('pembeli.settings')->with('success', 'Informasi profil berhasil diperbarui.');
    }

    // Memproses update keamanan password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => ['required'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Password lama yang kamu masukkan salah.']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('pembeli.settings')->with('success', 'Password akun berhasil diganti.');
    }
}