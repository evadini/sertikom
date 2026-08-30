<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticketify - Create Event</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @include('components.fonts')
    <style>
        [x-cloak] {
            display: none;
        }

        img[x-show] {
            max-height: 300px;
            object-fit: cover;
        }
    </style>
</head>
@php
    $ticketRows = old('ticket_type', [['ticket_type' => 'Reguler', 'price' => 0, 'stock' => 100]]);
@endphp
<body class="bg-[#F8FAFC] text-gray-900 flex flex-col min-h-screen" x-data='{
        activeTab: "ticket",
        bannerName: "",
        bannerPreview: "",
        orgPhotoName: "",
        orgPhotoPreview: "",
        ticketRows: @json($ticketRows),
        handleBannerChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.bannerName = file.name;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.bannerPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        handleOrgPhotoChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.orgPhotoName = file.name;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.orgPhotoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    }'>

    @include('components.panitia-nav')

    <main class="flex-1 p-10 overflow-y-auto">
        <header class="mb-10">
            <h1 class="text-3xl font-black tracking-tight uppercase italic text-blue-500">Create Event</h1>
            <p class="text-gray-500 text-sm mt-2 font-medium">Isi formulir di bawah untuk mengajukan event baru ke Admin.</p>
        </header>

        {{-- Menampilkan Error Validasi Jika Ada Inputan yang Kurang/Salah --}}
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl text-red-600 text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Navigasi Step Menu --}}
        <div class="flex gap-8 mb-8 border-b border-gray-200 overflow-x-auto flex-nowrap">
            <button type="button" @click="activeTab = 'ticket'" :class="activeTab === 'ticket' ? 'border-b-2 border-blue-500 text-blue-500' : 'text-gray-500'" class="pb-4 font-bold text-sm transition-all flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-[10px]">1</span> Ticket
            </button>
            <button type="button" @click="activeTab = 'detail'" :class="activeTab === 'detail' ? 'border-b-2 border-blue-500 text-blue-500' : 'text-gray-500'" class="pb-4 font-bold text-sm transition-all flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-[10px]">2</span> Event Detail
            </button>
            <button type="button" @click="activeTab = 'organiser'" :class="activeTab === 'organiser' ? 'border-b-2 border-blue-500 text-blue-500' : 'text-gray-500'" class="pb-4 font-bold text-sm transition-all flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-[10px]">3</span> Organiser
            </button>
        </div>

        {{-- TUNGGAL FORM UTAMA UNTUK SUBMIT KE BACKEND --}}
        <form action="{{ route('panitia.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            {{-- ALERT VALIDASI GLOBAL --}}
            <div id="form-alert" class="hidden mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl text-red-600 text-sm space-y-1"></div>

            {{-- ========================================================================= --}}
            {{-- STEP 1: TICKET & INFO UTAMA --}}
            {{-- ========================================================================= --}}
            <div x-show="activeTab === 'ticket'" class="space-y-8">
                <div class="grid md:grid-cols-2 gap-8">
                    {{-- Input Poster --}}
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Poster Event</label>
                        <div class="border-2 border-dashed border-gray-200 rounded-3xl p-8 sm:p-[110px] text-center hover:border-blue-500 transition-colors bg-gray-50 group relative overflow-hidden" :class="bannerPreview ? 'p-4' : 'p-8 sm:p-[110px]'">
                            <template x-if="!bannerPreview">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-image text-4xl text-gray-300 group-hover:text-blue-500 transition-colors mb-4 block"></i>
                                    <p class="text-[11px] text-gray-500 font-medium">Klik atau seret file poster di sini</p>
                                    <button type="button" onclick="document.getElementById('poster').click()" class="mt-4 px-6 py-2 bg-gray-200 rounded-full text-[10px] font-bold hover:bg-gray-300 transition-all border border-gray-200">Pilih File</button>
                                </div>
                            </template>
                            <template x-if="bannerPreview">
                                <div class="relative">
                                    <img :src="bannerPreview" alt="Banner Preview" class="w-full h-64 object-cover rounded-2xl">
                                    <button type="button" @click="bannerName = ''; bannerPreview = ''; document.getElementById('poster').value = '';" class="absolute top-2 right-2 w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white text-xs hover:bg-red-600 transition">
                                        <i class="fa-solid fa-times"></i>
                                    </button>
                                    <p class="text-[11px] text-gray-400 font-medium mt-2" x-text="'File: ' + bannerName"></p>
                                </div>
                            </template>
                            <input type="file" name="banner" class="hidden" id="poster" accept="image/*" @change="handleBannerChange($event)">
                        </div>
                    </div>

                    {{-- Form Field Kiri --}}
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Nama Event</label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama event" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:border-blue-500 outline-none transition-all" required>
                        </div>

                       <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Kategori Event</label>
                    <select name="category_id" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:border-blue-500 outline-none transition-all" required>
                        <option value="" disabled selected>Pilih Kategori</option>
                        @foreach(\App\Models\Category::all() as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                        </div>

                         <div class="space-y-2">
                             <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Event Location</label>
                             <input type="text" name="location" value="{{ old('location') }}" placeholder="Masukkan lokasi event (contoh: Batam, Kepulauan Riau)" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:border-blue-500 outline-none transition-all" required>
                        </div>


                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Tanggal Mulai</label>
                                <input type="date" name="date_start" id="date_start" value="{{ old('date_start') }}" min="{{ date('Y-m-d') }}" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-xs text-gray-900 focus:border-blue-500 outline-none transition-all" required>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Tanggal Selesai <span class="text-gray-400 font-normal"></span></label>
                                <input type="date" name="date_end" id="date_end" value="{{ old('date_end') }}" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-xs text-gray-900 focus:border-blue-500 outline-none transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Waktu Mulai</label>
                                <input type="time" name="time_start" value="{{ old('time_start') }}" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-xs text-gray-900 focus:border-blue-500 outline-none transition-all" required>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Waktu Selesai</label>
                                <input type="time" name="time_end" value="{{ old('time_end') }}" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-xs text-gray-900 focus:border-blue-500 outline-none transition-all" required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Skema Tiket Dinamis Alpine JS --}}
                <div class="bg-white p-8 rounded-3xl border border-gray-200 shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-sm flex items-center gap-2 text-gray-900">
                            <i class="fa-solid fa-ticket text-blue-500"></i> Ticket Pricing & Stock
                        </h3>
                        <button type="button" @click="ticketRows.push({ ticket_type: 'Reguler', price: 0, stock: 100 })" class="px-4 py-2 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase tracking-wide hover:bg-blue-500 transition">Tambah Jenis Tiket</button>
                    </div>

                    <template x-for="(ticket, index) in ticketRows" :key="index">
                        <div class="grid md:grid-cols-12 gap-4 items-end bg-gray-50 p-4 rounded-2xl border border-gray-200 mb-4">
                            <div class="md:col-span-4 space-y-2">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Jenis Tiket</label>
                                <input :name="`ticket_types[${index}][ticket_type]`" type="text" x-model="ticket.ticket_type" placeholder="Contoh: Reguler / VIP" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:border-blue-500 outline-none" required>
                            </div>
                            <div class="md:col-span-4 space-y-2">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Harga (IDR)</label>
                                <input :name="`ticket_types[${index}][price]`" type="number" x-model="ticket.price" min="0" placeholder="0" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:border-blue-500 outline-none" required>
                            </div>
                            <div class="md:col-span-3 space-y-2">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Stok Tiket</label>
                                <input :name="`ticket_types[${index}][stock]`" type="number" x-model="ticket.stock" min="0" placeholder="100" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:border-blue-500 outline-none" required>
                            </div>
                            <div class="md:col-span-1 flex items-end justify-end">
                                <button type="button" @click="ticketRows.splice(index, 1)" x-show="ticketRows.length > 1" class="w-full py-3 bg-red-50 text-red-600 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-100 transition">Hapus</button>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="flex justify-end">
                    <button type="button" @click="activeTab = 'detail'" class="px-10 py-4 rounded-2xl bg-blue-600 text-white text-xs font-black uppercase tracking-widest hover:bg-blue-700 transition-all">Lanjut ke Detail</button>
                </div>
            </div>

            {{-- ========================================================================= --}}
            {{-- STEP 2: EVENT DETAIL --}}
            {{-- ========================================================================= --}}
            <div x-show="activeTab === 'detail'" class="space-y-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Deskripsi Event</label>
                    <textarea name="description" rows="6" class="w-full bg-white border border-gray-200 rounded-2xl p-4 text-sm text-gray-900 focus:border-blue-500 outline-none" placeholder="Ceritakan detail acaramu..." required>{{ old('description') }}</textarea>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Syarat & Ketentuan</label>
                    <textarea name="terms" rows="4" class="w-full bg-white border border-gray-200 rounded-2xl p-4 text-sm text-gray-900 focus:border-blue-500 outline-none" placeholder="1. Peserta wajib..." required>{{ old('terms') }}</textarea>
                </div>

                <div class="flex justify-end gap-4">
                    <button type="button" @click="activeTab = 'ticket'" class="px-8 py-4 rounded-2xl bg-gray-100 text-gray-700 text-xs font-black uppercase tracking-widest hover:bg-gray-200 transition-all">Kembali</button>
                    <button type="button" @click="activeTab = 'organiser'" class="px-10 py-4 rounded-2xl bg-blue-600 text-white text-xs font-black uppercase tracking-widest hover:bg-blue-700 transition-all">Lanjut ke Organiser</button>
                </div>
            </div>

            {{-- ========================================================================= --}}
            {{-- STEP 3: ORGANISER (FINAL TAB) --}}
            {{-- ========================================================================= --}}
            <div x-show="activeTab === 'organiser'" class="space-y-8">
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Deskripsi Organisasi</label>
                        <textarea name="organiser_description" rows="8" class="w-full bg-white border border-gray-200 rounded-2xl p-4 text-sm text-gray-900 focus:border-blue-500 outline-none" placeholder="Profil singkat penyelenggara...">{{ old('organiser_description') }}</textarea>
                    </div>
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Foto Organisasi / Tim</label>
                        <div class="border-2 border-dashed border-gray-200 rounded-3xl p-10 text-center bg-gray-50 group" :class="orgPhotoPreview ? 'p-4' : 'p-10'">
                            <template x-if="!orgPhotoPreview">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-users text-4xl text-gray-300 group-hover:text-blue-500 transition-colors mb-4 block"></i>
                                    <p class="text-[11px] text-gray-500 font-medium mb-2">Belum ada foto terpilih</p>
                                    <button type="button" onclick="document.getElementById('org_photo').click()" class="px-6 py-2 bg-gray-200 rounded-full text-[10px] font-bold hover:bg-gray-300 transition-all border border-gray-200">Upload Photo</button>
                                </div>
                            </template>
                            <template x-if="orgPhotoPreview">
                                <div class="relative">
                                    <img :src="orgPhotoPreview" alt="Organiser Photo Preview" class="w-full h-64 object-cover rounded-2xl">
                                    <button type="button" @click="orgPhotoName = ''; orgPhotoPreview = ''; document.getElementById('org_photo').value = '';" class="absolute top-2 right-2 w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white text-xs hover:bg-red-600 transition">
                                        <i class="fa-solid fa-times"></i>
                                    </button>
                                    <p class="text-[11px] text-gray-400 font-medium mt-2" x-text="'File: ' + orgPhotoName"></p>
                                </div>
                            </template>
                            <input type="file" name="organiser_photo" class="hidden" id="org_photo" accept="image/*" @change="handleOrgPhotoChange($event)">
                        </div>
                    </div>
                </div>

        {{-- Action Akhir Tombol Submit --}}
                <div class="pt-10 flex justify-end gap-4 border-t border-gray-200">
                    <button type="button" @click="activeTab = 'detail'" class="px-8 py-4 rounded-2xl bg-gray-100 text-gray-700 text-xs font-black uppercase tracking-widest hover:bg-gray-200 transition-all">
                        Kembali
                    </button>
                    <button type="submit" id="submit-event-btn" class="px-10 py-4 rounded-2xl bg-blue-600 text-white text-xs font-black uppercase tracking-widest hover:bg-blue-700 shadow-lg shadow-blue-600/20 active:scale-95 transition-all">
                        Ajukan Event Sekarang
                    </button>
                </div>
            </div>

        </form>
    </main>

    {{-- Script Handler Sidebar Responsif --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');

            if (form) {
                const fieldLabels = {
                    'name': 'Nama Event', 'category_id': 'Kategori Event', 'location': 'Lokasi',
                    'date_start': 'Tanggal Mulai', 'date_end': 'Tanggal Selesai',
                    'time_start': 'Waktu Mulai', 'time_end': 'Waktu Selesai',
                    'description': 'Deskripsi', 'terms': 'Syarat & Ketentuan',
                };
                const fieldTabMap = {
                    'name': 'ticket', 'category_id': 'ticket', 'location': 'ticket',
                    'date_start': 'ticket', 'date_end': 'ticket',
                    'time_start': 'ticket', 'time_end': 'ticket',
                    'description': 'detail', 'terms': 'detail',
                };

                form.addEventListener('submit', function(e) {
                    const alertEl = document.getElementById('form-alert');
                    alertEl.classList.add('hidden');
                    alertEl.innerHTML = '';

                    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
                    const errors = [];
                    let firstInvalid = null;
                    let targetTab = null;

                    inputs.forEach(input => {
                        if (!input.value || input.value.trim() === '') {
                            if (!firstInvalid) firstInvalid = input;
                            const tab = fieldTabMap[input.name];
                            if (tab && !targetTab) targetTab = tab;
                            errors.push(fieldLabels[input.name] || input.name);
                        }
                    });

                    const ticketTypeInputs = form.querySelectorAll('[name*="[ticket_type]"]');
                    if (ticketTypeInputs.length === 0) {
                        errors.push('Minimal 1 jenis tiket');
                        if (!targetTab) targetTab = 'ticket';
                    }

                    if (errors.length > 0) {
                        e.preventDefault();
                        const title = document.createElement('p');
                        title.className = 'font-bold mb-1';
                        title.textContent = 'ⓘ Lengkapi field berikut:';
                        alertEl.appendChild(title);
                        const listEl = document.createElement('ul');
                        listEl.className = 'list-disc pl-5 space-y-0.5';
                        errors.forEach(f => {
                            const li = document.createElement('li');
                            li.textContent = f;
                            listEl.appendChild(li);
                        });
                        alertEl.appendChild(listEl);
                        alertEl.classList.remove('hidden');

                        if (targetTab && typeof Alpine !== 'undefined') {
                            Alpine.$data(document.querySelector('[x-data]')).activeTab = targetTab;
                        }
                        if (firstInvalid) {
                            setTimeout(() => {
                                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }, 150);
                        }
                        return false;
                    }
                    return true;
                });

                const dateStart = document.getElementById('date_start');
                const dateEnd = document.getElementById('date_end');
                if (dateStart && dateEnd) {
                    if (dateStart.value) {
                        dateEnd.min = dateStart.value;
                    }
                    dateStart.addEventListener('change', function() {
                        dateEnd.min = this.value;
                        if (dateEnd.value && dateEnd.value < this.value) {
                            dateEnd.value = this.value;
                        }
                    });
                }
            }
        });
    </script>
</body>
</html>
