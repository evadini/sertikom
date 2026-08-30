@extends('layouts.admin')

@section('title', 'Role Applications')

@section('content')

<div class="px-8 mt-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <p class="text-gray-500 text-sm">Pending Requests</p>
            <h3 class="text-4xl font-black mt-2 text-amber-500">{{ $applications->where('status', 'pending')->count() }}</h3>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <p class="text-gray-500 text-sm">Approved</p>
            <h3 class="text-4xl font-black mt-2 text-emerald-600">{{ $applications->where('status', 'approved')->count() }}</h3>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <p class="text-gray-500 text-sm">Rejected</p>
            <h3 class="text-4xl font-black mt-2 text-red-600">{{ $applications->where('status', 'rejected')->count() }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center gap-3 bg-gray-50 border border-gray-200 rounded-xl px-4 w-full md:w-80 focus-within:border-blue-500 transition-colors mb-6">
            <i class="fa-solid fa-magnifying-glass text-blue-500 text-sm"></i>
            <input type="text" id="searchInput" placeholder="Search applicant by name or NIM..."
                class="w-full py-3 bg-transparent outline-none text-sm text-gray-900 placeholder-gray-400 border-none ring-0 focus:ring-0">
        </div>

        <div class="overflow-x-auto">
            <table class="w-full" id="applicationsTable">
                <thead>
                    <tr class="border-b border-gray-200 text-left text-gray-500 text-sm">
                        <th class="py-4 font-semibold">No</th>
                        <th class="py-4 font-semibold">Applicant Name</th>
                        <th class="py-4 font-semibold">NIM</th>
                        <th class="py-4 font-semibold">Organization / UKM</th>
                        <th class="py-4 font-semibold">Bank Account</th>
                        <th class="py-4 font-semibold">Status</th>
                        <th class="py-4 font-semibold text-center">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @php
                        $badgeClass = [
                            'pending'   => 'bg-amber-50 text-amber-600',
                            'approved'  => 'bg-emerald-50 text-emerald-600',
                            'rejected'  => 'bg-red-50 text-red-600',
                        ];
                    @endphp

                    @forelse ($applications as $i => $app)
                        <tr class="hover:bg-gray-50 transition-colors searchable-row">
                            <td class="py-4 text-gray-500 text-sm">{{ $i + 1 }}</td>
                            <td class="py-4">
                                <span class="font-medium text-sm text-gray-900">{{ $app->user->name }}</span>
                            </td>
                            <td class="py-4 text-gray-500 text-sm font-mono">{{ $app->user->nim }}</td>
                            <td class="py-4 text-gray-500 text-sm font-medium text-blue-600">{{ $app->ukm->nama_ukm ?? 'N/A' }}</td>
                            <td class="py-4 text-gray-500 text-sm font-mono">{{ $app->nomor_rekening }}</td>
                            <td class="py-4">
                                <span class="px-2.5 py-1 rounded-md text-xs font-bold {{ $badgeClass[$app->status] ?? 'bg-gray-100 text-gray-500' }}">
                                    {{ ucfirst($app->status) }}
                                </span>
                            </td>
                            <td class="py-4">
                                <div class="flex justify-center gap-2">
                                    @if ($app->status === 'pending')
                                        <button type="button"
                                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-colors text-xs font-semibold"
                                            data-app='@json($app)'
                                            onclick="openApproveModal(this)">
                                            <i class="fa-solid fa-check text-[11px]"></i> Approve
                                        </button>
                                        <button type="button"
                                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors text-xs font-semibold"
                                            data-app='@json($app)'
                                            onclick="openRejectModal(this)">
                                            <i class="fa-solid fa-times text-[11px]"></i> Reject
                                        </button>
                                    @else
                                        <span class="text-gray-400 text-xs italic">No actions available</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-500">
                                <i class="fa-solid fa-inbox text-4xl mb-4 block opacity-50"></i>
                                No role applications found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- APPROVE MODAL --}}
<div id="approveModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md border border-gray-200 shadow-2xl text-center">
        <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-check text-emerald-500 text-2xl"></i>
        </div>
        <h3 class="text-xl font-black text-gray-900 mb-2">Setujui Pengajuan Panitia?</h3>
        <p class="text-gray-500 text-sm mb-1" id="approveUserName"></p>
        <p class="text-gray-400 text-xs mb-6" id="approveUserDetail"></p>
        <form id="approveForm" method="POST">
            @csrf
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('approveModal')"
                    class="flex-1 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-700 hover:bg-gray-50 transition">Batal</button>
                <button type="submit"
                    class="flex-1 py-2.5 bg-emerald-500 text-white rounded-xl text-sm font-bold hover:bg-emerald-600 transition">
                    Ya, Setujui
                </button>
            </div>
        </form>
    </div>
</div>

{{-- REJECT MODAL --}}
<div id="rejectModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl p-6 w-full max-w-lg border border-gray-200 shadow-2xl">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-gray-900">Tolak Pengajuan Panitia</h2>
                <p class="text-xs text-gray-500" id="rejectUserInfo"></p>
            </div>
        </div>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="mb-4">
                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2 block">
                    Alasan Penolakan <span class="text-red-500">*</span>
                </label>
                <textarea name="alasan_ditolak" id="rejectReason" rows="3"
                    placeholder="Jelaskan alasan mengapa pengajuan ini ditolak... (min. 10 karakter)"
                    class="w-full bg-white border border-gray-200 rounded-xl p-3 text-sm text-gray-900 outline-none focus:border-red-500 transition resize-none placeholder:text-gray-400"
                    maxlength="500"></textarea>
                <div class="flex justify-between mt-1">
                    <p id="rejectReasonError" class="text-xs text-red-500 hidden">Alasan minimal 10 karakter.</p>
                    <p class="text-xs text-gray-400 ml-auto"><span id="rejectReasonCount">0</span>/500</p>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('rejectModal')"
                    class="flex-1 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-700 hover:bg-gray-50 transition">Batal</button>
                <button type="submit"
                    class="flex-1 py-2.5 bg-red-500 text-white rounded-xl text-sm font-bold hover:bg-red-600 transition">
                    <i class="fa-solid fa-xmark mr-1"></i> Tolak
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.searchable-row');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.getElementById(id).classList.remove('flex');
    }

    function openApproveModal(btn) {
        const app = JSON.parse(btn.getAttribute('data-app'));
        document.getElementById('approveUserName').textContent = app.user?.name || 'Unknown';
        document.getElementById('approveUserDetail').textContent = (app.user?.nim || '-') + ' — ' + (app.ukm?.nama_ukm || 'N/A');
        document.getElementById('approveForm').action = '{{ url("admin/role-applications") }}/' + app.id + '/approve';
        document.getElementById('approveModal').classList.remove('hidden');
        document.getElementById('approveModal').classList.add('flex');
    }

    function openRejectModal(btn) {
        const app = JSON.parse(btn.getAttribute('data-app'));
        document.getElementById('rejectUserInfo').textContent = (app.user?.name || 'Unknown') + ' — ' + (app.user?.nim || '-');
        document.getElementById('rejectReason').value = '';
        document.getElementById('rejectReasonCount').textContent = '0';
        document.getElementById('rejectReasonError').classList.add('hidden');
        document.getElementById('rejectForm').action = '{{ url("admin/role-applications") }}/' + app.id + '/reject';
        document.getElementById('rejectModal').classList.remove('hidden');
        document.getElementById('rejectModal').classList.add('flex');
    }

    document.getElementById('rejectReason')?.addEventListener('input', function() {
        document.getElementById('rejectReasonCount').textContent = this.value.length;
        if (this.value.length >= 10) {
            document.getElementById('rejectReasonError').classList.add('hidden');
        }
    });

    document.getElementById('rejectForm')?.addEventListener('submit', function(e) {
        const reason = document.getElementById('rejectReason').value;
        if (reason.length < 10) {
            e.preventDefault();
            document.getElementById('rejectReasonError').classList.remove('hidden');
        }
    });
</script>
@endpush

@endsection
