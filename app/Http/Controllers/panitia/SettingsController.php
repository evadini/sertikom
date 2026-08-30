<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class SettingsController extends Controller
{
    /**
     * Menampilkan halaman settings panitia
     */
    public function index()
    {
        // Mengambil data user login beserta relasi application dan ukm-nya
        $user = User::with('latestApplication.ukm')->find(Auth::id());
        return view('panitia.settings', compact('user'));
    }

    /**
     * Mengupdate detail profil (Nama, NIM, Asal UKM, No HP, No Rekening, Foto)
     */
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = User::find(Auth::id());

        // 1. Validasi Input Data
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nim' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->getKey(), $user->getKeyName())],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->getKey(), $user->getKeyName())],
            'phone_number' => ['nullable', 'string', 'max:15'],
            'nomor_rekening' => ['required', 'string', 'max:50'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ], [
            'profile_picture.max' => 'Ukuran foto profil tidak boleh lebih dari 2MB.',
            'profile_picture.image' => 'File yang diupload harus berupa gambar.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'nim.required' => 'NIM wajib diisi.',
            'nim.unique' => 'NIM ini sudah terdaftar pada akun lain.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar pada akun lain.',
            'nomor_rekening.required' => 'Nomor rekening wajib diisi.',
        ]);

        // 2. Handle Upload Foto Profil
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path;
        }

        // 3. Simpan Perubahan ke Tabel Users
        $user->name = $request->name;
        $user->nim = $request->nim;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->save();

        // 4. Simpan Perubahan Nomor Rekening ke Tabel Role Applications
        if ($user->latestApplication) {
            $user->latestApplication->update([
                'nomor_rekening' => $request->nomor_rekening
            ]);
        }

        Auth::setUser($user);

        return redirect()->back()->with('success', 'Informasi profil berhasil diperbarui!');
    }

    /**
     * Mengupdate password akun panitia
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password lama yang kamu masukkan salah.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        Auth::login($user);

        return redirect()->back()->with('success', 'Password akun kamu berhasil diubah!');
    }
}
