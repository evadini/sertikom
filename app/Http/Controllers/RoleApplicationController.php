<?php

namespace App\Http\Controllers;

use App\Models\RoleApplication;
use App\Models\Ukm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleApplicationController extends Controller
{
    public function index()
    {
        $applications = RoleApplication::with('user', 'ukm')->latest()->get();
        return view('Admin.RoleApplication', compact('applications'));
    }

    public function create()
    {
        $ukms = Ukm::all();

        $application = RoleApplication::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->latest()
            ->first();

        $rejectedApplication = RoleApplication::where('user_id', Auth::id())
            ->where('status', 'rejected')
            ->latest()
            ->first();

        return view('Pembeli.BuatEvent', compact('ukms', 'application', 'rejectedApplication'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ukm_id' => 'required|exists:ukms,id',
            'nomor_rekening' => 'required|string|max:50',
        ]);

        $exists = RoleApplication::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Kamu sudah memiliki pengajuan aktif.');
        }

        RoleApplication::create([
            'user_id' => Auth::id(),
            'ukm_id' => $request->ukm_id,
            'nomor_rekening' => $request->nomor_rekening,
            'status' => 'pending',
        ]);

        return redirect()->route('pembeli.buatevent')->with('success', 'Pengajuan berhasil dikirim!');
    }

    public function approve($id)
    {
        $application = RoleApplication::with('user')->findOrFail($id);

        $application->update(['status' => 'approved']);

        if ($application->user) {
            $application->user->update(['role' => 'panitia']);
        }

        return redirect()->route('admin.role-applications')->with('success', 'Pengajuan panitia disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_ditolak' => 'required|string|min:10|max:500',
        ]);

        $application = RoleApplication::findOrFail($id);
        $application->update([
            'status' => 'rejected',
            'alasan_ditolak' => $request->alasan_ditolak,
        ]);

        return redirect()->route('admin.role-applications')->with('success', 'Pengajuan panitia ditolak.');
    }
}
