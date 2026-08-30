@extends('layouts.admin')

@section('title', 'Users Management')

@section('content')

<div class="px-8 mt-6">

    @if(session('success'))
        <div id="toast" class="fixed bottom-6 right-6 z-50 bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-3 rounded-xl shadow-lg font-semibold flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div id="toast" class="fixed bottom-6 right-6 z-50 bg-red-50 border border-red-200 text-red-600 px-6 py-3 rounded-xl shadow-lg font-semibold flex items-center gap-2">
            <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <p class="text-gray-500 text-sm">Total Users</p>
            <h3 class="text-4xl font-black mt-2 text-gray-900">{{ $totalUsers }}</h3>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <p class="text-gray-500 text-sm">Admins</p>
            <h3 class="text-4xl font-black mt-2 text-blue-600">{{ $totalAdmins }}</h3>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <p class="text-gray-500 text-sm">Organizers</p>
            <h3 class="text-4xl font-black mt-2 text-indigo-600">{{ $totalOrganizers }}</h3>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <p class="text-gray-500 text-sm">Customers</p>
            <h3 class="text-4xl font-black mt-2 text-gray-700">{{ $totalCustomers }}</h3>
        </div>
    </div>

    <div class="flex gap-2 mb-6 flex-wrap">
        <button onclick="switchTab('pembeli')" id="tab-pembeli"
            class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all bg-blue-600 text-white">
            <i class="fa-solid fa-users mr-1.5"></i> Pembeli
        </button>
        <button onclick="switchTab('panitia')" id="tab-panitia"
            class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all bg-gray-100 text-gray-500 hover:bg-gray-200">
            <i class="fa-solid fa-people-group mr-1.5"></i> Panitia
        </button>
        <button onclick="switchTab('deleted')" id="tab-deleted"
            class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all bg-gray-100 text-gray-500 hover:bg-gray-200">
            <i class="fa-solid fa-trash mr-1.5"></i> Deleted
            @if($deletedUsers->count() > 0)
                <span class="ml-1 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">{{ $deletedUsers->count() }}</span>
            @endif
        </button>
    </div>

    <div class="flex flex-col md:flex-row justify-between gap-4 mb-6" id="toolbar-search">
        <form method="GET" action="{{ route('admin.users') }}" class="w-full md:w-80">
            <div class="flex items-center gap-3 bg-gray-50 border border-gray-200 rounded-xl px-4 focus-within:border-blue-500 transition-colors">
                <i class="fa-solid fa-magnifying-glass text-blue-500 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search user..."
                    class="w-full py-3 bg-transparent outline-none text-sm text-gray-900 placeholder-gray-400 border-none ring-0 focus:ring-0">
            </div>
        </form>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 rounded-xl px-5 py-4 mb-5 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div id="panel-pembeli">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 text-left text-gray-500 text-sm">
                            <th class="py-4 font-semibold">No</th>
                            <th class="py-4 font-semibold">NIM</th>
                            <th class="py-4 font-semibold">Nama</th>
                            <th class="py-4 font-semibold">No Telepon</th>
                            <th class="py-4 font-semibold text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($pembeli as $i => $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 text-gray-500 text-sm">{{ $i + 1 }}</td>
                                <td class="py-4 text-gray-500 text-sm">{{ $user->nim ?? '-' }}</td>
                                <td class="py-4 font-medium text-sm text-gray-900">{{ $user->name }}</td>
                                <td class="py-4 text-gray-500 text-sm">{{ $user->phone_number ?? '-' }}</td>
                                <td class="py-4">
                                    <div class="flex justify-center gap-2">
                                        <button onclick="openEditUser('{{ $user->nim }}', '{{ addslashes($user->name) }}', '{{ $user->nim }}', '{{ $user->phone_number }}', '{{ $user->role }}')"
                                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors text-xs font-semibold">
                                            <i class="fa-solid fa-pen-to-square text-[11px]"></i> Edit
                                        </button>
                                        <button onclick="openDeleteUser('{{ $user->nim }}', '{{ addslashes($user->name) }}')"
                                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors text-xs font-semibold">
                                            <i class="fa-solid fa-trash text-[11px]"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-gray-500 text-sm">
                                    <i class="fa-solid fa-users-slash text-2xl mb-3 block"></i>
                                    Tidak ada pembeli ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="panel-panitia" class="hidden">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 text-left text-gray-500 text-sm">
                            <th class="py-4 font-semibold">No</th>
                            <th class="py-4 font-semibold">NIM</th>
                            <th class="py-4 font-semibold">Nama</th>
                            <th class="py-4 font-semibold">Asal UKM</th>
                            <th class="py-4 font-semibold">No Telepon</th>
                            <th class="py-4 font-semibold">No Rekening</th>
                            <th class="py-4 font-semibold text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($panitia as $i => $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 text-gray-500 text-sm">{{ $i + 1 }}</td>
                                <td class="py-4 text-gray-500 text-sm">{{ $user->nim ?? '-' }}</td>
                                <td class="py-4 font-medium text-sm text-gray-900">{{ $user->name }}</td>
                                <td class="py-4 text-gray-500 text-sm">{{ $user->approvedApplication?->ukm?->nama_ukm ?? '-' }}</td>
                                <td class="py-4 text-gray-500 text-sm">{{ $user->phone_number ?? '-' }}</td>
                                <td class="py-4 text-gray-500 text-sm">{{ $user->approvedApplication?->nomor_rekening ?? '-' }}</td>
                                <td class="py-4">
                                    <div class="flex justify-center gap-2">
                                        <button onclick="openEditUser('{{ $user->nim }}', '{{ addslashes($user->name) }}', '{{ $user->nim }}', '{{ $user->phone_number }}', '{{ $user->role }}')"
                                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors text-xs font-semibold">
                                            <i class="fa-solid fa-pen-to-square text-[11px]"></i> Edit
                                        </button>
                                        <button onclick="openDeleteUser('{{ $user->nim }}', '{{ addslashes($user->name) }}')"
                                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors text-xs font-semibold">
                                            <i class="fa-solid fa-trash text-[11px]"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-gray-500 text-sm">
                                    <i class="fa-solid fa-users-slash text-2xl mb-3 block"></i>
                                    Tidak ada panitia ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="panel-deleted" class="hidden">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <p class="text-sm text-gray-500 mb-6">
                <i class="fa-solid fa-circle-info mr-1 text-amber-500"></i>
                User di bawah ini sudah dihapus tapi datanya masih tersimpan. Kamu bisa pulihkan kapan saja.
            </p>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 text-left text-gray-500 text-sm">
                            <th class="py-4 font-semibold">No</th>
                            <th class="py-4 font-semibold">Name</th>
                            <th class="py-4 font-semibold">NIM</th>
                            <th class="py-4 font-semibold">Role</th>
                            <th class="py-4 font-semibold">Dihapus Pada</th>
                            <th class="py-4 font-semibold text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($deletedUsers as $i => $user)
                            <tr class="hover:bg-gray-50 transition-colors opacity-70">
                                <td class="py-4 text-gray-500 text-sm">{{ $i + 1 }}</td>
                                <td class="py-4 font-medium text-sm text-gray-500 line-through decoration-red-400">{{ $user->name }}</td>
                                <td class="py-4 text-gray-500 text-sm">{{ $user->nim }}</td>
                                <td class="py-4">
                                    <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-gray-100 text-gray-500">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="py-4 text-gray-500 text-xs">
                                    {{ $user->deleted_at->format('d M Y, H:i') }}
                                </td>
                                <td class="py-4">
                                    <div class="flex justify-center gap-2">
                                        <form action="{{ route('admin.users.restore', $user->nim) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-colors text-xs font-semibold">
                                                <i class="fa-solid fa-rotate-left text-[11px]"></i> Pulihkan
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.users.force-delete', $user->nim) }}" method="POST"
                                            onsubmit="return confirm('Hapus permanen? Data tidak bisa dikembalikan!')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors text-xs font-semibold">
                                                <i class="fa-solid fa-xmark text-[11px]"></i> Hapus Permanen
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-gray-500 text-sm">
                                    <i class="fa-solid fa-trash-can text-2xl mb-3 block"></i>
                                    Tidak ada user yang dihapus.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="editModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70">
    <div class="bg-white border border-gray-200 rounded-2xl p-8 w-full max-w-md mx-4 shadow-2xl">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-xl font-black text-gray-900">Edit User</h3>
            <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <p class="text-xs text-gray-500 mb-5">Kosongkan field yang tidak ingin diubah.</p>
        <form id="editForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="text-sm text-gray-500 mb-1 block">Name <span class="text-gray-400 font-normal">(opsional)</span></label>
                <input type="text" name="name" id="editName" placeholder="Kosongkan jika tidak ingin mengubah"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 outline-none focus:border-blue-500 transition-colors placeholder-gray-400">
            </div>
            <div>
                <label class="text-sm text-gray-500 mb-1 block">NIM <span class="text-gray-400 font-normal">(opsional)</span></label>
                <input type="text" name="nim" id="editNim" placeholder="Kosongkan jika tidak ingin mengubah"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 outline-none focus:border-blue-500 transition-colors placeholder-gray-400">
            </div>
            <div>
                <label class="text-sm text-gray-500 mb-1 block">Phone Number <span class="text-gray-400 font-normal">(opsional)</span></label>
                <input type="text" name="phone_number" id="editPhone" placeholder="Kosongkan jika tidak ingin mengubah"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 outline-none focus:border-blue-500 transition-colors placeholder-gray-400">
            </div>
            <div>
                <label class="text-sm text-gray-500 mb-1 block">Password <span class="text-gray-400 font-normal">(opsional)</span></label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 outline-none focus:border-blue-500 transition-colors placeholder-gray-400">
            </div>
            <div>
                <label class="text-sm text-gray-500 mb-1 block">Role</label>
                <select name="role" id="editRole"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 outline-none focus:border-blue-500 transition-colors">
                    <option value="">-- Tidak mengubah role --</option>
                    <option value="pembeli">Pembeli</option>
                    <option value="panitia">Panitia</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('editModal')"
                    class="flex-1 py-3 rounded-xl border border-gray-200 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                    class="flex-1 py-3 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition-all active:scale-95">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70">
    <div class="bg-white border border-gray-200 rounded-2xl p-8 w-full max-w-sm mx-4 text-center shadow-2xl">
        <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-trash text-red-500 text-2xl"></i>
        </div>
        <h3 class="text-xl font-black text-gray-900 mb-2">Hapus User?</h3>
        <p class="text-gray-500 text-sm mb-1">
            User <span id="deleteUserName" class="text-gray-900 font-semibold"></span> akan dipindahkan ke Deleted.
        </p>
        <p class="text-gray-400 text-xs mb-6">Data masih tersimpan dan bisa dipulihkan kapan saja.</p>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('deleteModal')"
                    class="flex-1 py-3 rounded-xl border border-gray-200 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                    class="flex-1 py-3 rounded-xl bg-red-500 text-white text-sm font-bold hover:bg-red-600 transition-all active:scale-95">
                    Ya, Hapus
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
