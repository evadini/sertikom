@extends('layouts.admin')

@section('title', 'Event Categories')

@section('content')

<div class="px-8 mt-6">

    <x-alert-toast />

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <p class="text-gray-500 text-sm">Total Categories</p>
            <h3 class="text-4xl font-black mt-2 text-gray-900">{{ $totalCategories }}</h3>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <p class="text-gray-500 text-sm">Deleted</p>
            <h3 class="text-4xl font-black mt-2 text-red-600">{{ $totalDeleted }}</h3>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <p class="text-gray-500 text-sm">Events Tagged</p>
            <h3 class="text-4xl font-black mt-2 text-blue-600">{{ $totalEventsTagged }}</h3>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <p class="text-gray-500 text-sm">Most Used</p>
            <h3 class="text-lg font-black mt-2 text-purple-600">{{ $mostUsed }}</h3>
        </div>
    </div>

    <div class="flex gap-2 mb-6">
        @foreach(['active' => 'Events', 'ukm' => 'UKM', 'deleted' => 'Deleted'] as $key => $label)
            <a href="{{ route('admin.categories', ['tab' => $key, 'search' => $search]) }}"
                class="px-4 py-2 rounded-xl text-sm font-bold {{ $tab === $key ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        <form method="GET" action="{{ route('admin.categories') }}" class="w-full md:w-80">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div class="flex items-center gap-3 bg-gray-50 border border-gray-200 rounded-xl px-4 focus-within:border-blue-500 transition">
                <i class="fa-solid fa-magnifying-glass text-blue-500 text-sm"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search category..."
                    class="w-full py-2.5 bg-transparent text-sm text-gray-900 placeholder-gray-400 outline-none border-none ring-0 focus:ring-0">
            </div>
        </form>

        @if(in_array($tab, ['active', 'ukm']))
            <button onclick="openAddModal()"
                class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition active:scale-95">
                <i class="fa-solid fa-plus mr-1"></i> Add Category
            </button>
        @endif
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 text-left text-gray-500 text-sm">
                        <th class="py-4 w-12">No</th>
                        <th class="py-4">Category Name</th>
                        <th class="py-4 text-center">Events</th>
                        <th class="py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($categories as $i => $category)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-4 text-gray-500 text-sm">{{ $i + 1 }}</td>
                            <td class="py-4">
                                <span class="text-gray-900 font-medium">{{ $category->name ?? $category->nama_ukm }}</span>
                                @if($tab === 'ukm')
                                    <span class="ml-2 text-[10px] uppercase tracking-wider bg-purple-100 text-purple-600 px-2 py-0.5 rounded">UKM</span>
                                @endif
                            </td>
                            <td class="py-4 text-center">
                                <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-600 text-xs font-semibold">
                                    {{ $eventCountByCategory[$category->name ?? $category->nama_ukm] ?? 0 }} events
                                </span>
                            </td>
                            <td class="py-4">
                                <div class="flex justify-center gap-2">
                                    <button onclick="openEditModal({{ $category->id }}, '{{ addslashes($category->name ?? $category->nama_ukm) }}')"
                                        class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 text-xs font-semibold hover:bg-blue-100 transition">
                                        <i class="fa-solid fa-pen mr-1"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="type" value="{{ $tab }}">
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-50 text-red-600 text-xs font-semibold hover:bg-red-100 transition">
                                            <i class="fa-solid fa-trash mr-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-gray-500">Tidak ada data ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalAdd" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70" onclick="handleBackdropClick(event, 'modalAdd')">
    <div class="bg-white border border-gray-200 rounded-2xl p-6 w-full max-w-sm mx-4 shadow-2xl">
        <h3 class="text-lg font-black text-gray-900 mb-5">Add Category</h3>
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <input type="hidden" name="type" value="{{ $tab === 'ukm' ? 'ukm' : 'general' }}">
            <div class="mb-6">
                <label class="text-sm text-gray-500 mb-1.5 block">Category Name</label>
                <input type="text" name="name" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900">
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 rounded-xl border border-gray-200 text-gray-600 text-sm hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 text-white text-sm font-bold">Save</button>
            </div>
        </form>
    </div>
</div>

<div id="modalEdit" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70" onclick="handleBackdropClick(event, 'modalEdit')">
    <div class="bg-white border border-gray-200 rounded-2xl p-6 w-full max-w-sm mx-4 shadow-2xl">
        <div class="flex justify-between items-center mb-5">
            <h3 class="text-lg font-black text-gray-900">Edit Category</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form method="POST" id="editForm" action="">
            @csrf
            @method('PUT')
            <input type="hidden" name="type" value="{{ $tab }}">
            <div class="mb-6">
                <label class="text-sm text-gray-500 mb-1.5 block">Category Name</label>
                <input type="text" name="name" id="editName" required autocomplete="off" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900">
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl border border-gray-200 text-gray-600 text-sm hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 text-white text-sm font-bold">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() { document.getElementById('modalAdd').classList.remove('hidden'); }
    function closeAddModal() { document.getElementById('modalAdd').classList.add('hidden'); }

    function openEditModal(id, name) {
        document.getElementById('editForm').action = `/admin/categories/${id}`;
        document.getElementById('editName').value  = name;
        document.getElementById('modalEdit').classList.remove('hidden');
    }
    function closeEditModal() { document.getElementById('modalEdit').classList.add('hidden'); }

    function handleBackdropClick(event, modalId) {
        if (event.target.id === modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddModal();
            closeEditModal();
        }
    });
</script>

@endsection
