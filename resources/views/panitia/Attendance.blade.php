<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ticketify - Attendance Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @include('components.fonts')
</head>
<body class="bg-[#F8FAFC] text-gray-900 flex flex-col min-h-screen" x-data="attendanceApp()">

    @include('components.panitia-nav')

    <main class="flex-1 p-6 lg:p-10 overflow-y-auto">

        @php
            $eventsJson = $userEvents->map(fn($e) => ['id' => $e->id, 'name' => $e->name])->values();
            $recentJson = $recentAttendances->map(fn($t) => [
                'id' => $t->id,
                'event_id' => $t->event_id,
                'name' => $t->user->name ?? 'User Terhapus',
                'ticket_type' => $t->ticket_type,
                'email' => $t->user->email ?? '',
                'attended_at' => $t->attended_at?->diffForHumans(),
            ]);
        @endphp

        @if($userEvents->count() > 0)

        {{-- ─── EVENT BAR ─── --}}
        <div class="bg-white border border-gray-200 rounded-[2rem] p-5 mb-8 shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center gap-5">
                <div class="flex-1">
                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2 block">Pilih Event</label>
                    <div class="flex gap-3">
                        <select x-model="selectedEventId" @change="onEventChange()"
                            class="flex-1 lg:max-w-md bg-white border border-gray-200 rounded-xl px-5 py-3.5 text-sm text-gray-900 focus:border-blue-500 outline-none appearance-none cursor-pointer">
                            <option value="">— Pilih Event —</option>
                            @foreach($userEvents as $event)
                                <option value="{{ $event->id }}">{{ $event->name }}</option>
                            @endforeach
                        </select>
                        <template x-if="selectedEventId">
                            <button @click="manualTicketId = ''; showResult = false; $refs.ticketInput.focus()"
                                    class="bg-white border border-gray-200 rounded-xl px-4 text-gray-500 hover:text-gray-900 hover:border-gray-300 transition-all text-sm">
                                <i class="fa-solid fa-arrows-rotate"></i>
                            </button>
                        </template>
                    </div>
                </div>
                <template x-if="statistics && selectedEventId">
                    <div class="flex items-center gap-5">
                        <div class="flex items-center gap-2.5 px-4 py-2 bg-blue-50 rounded-xl border border-blue-200">
                            <i class="fa-solid fa-ticket text-blue-500 text-[10px]"></i>
                            <span class="text-sm font-bold text-gray-900" x-text="statistics.total_tickets"></span>
                            <span class="text-[9px] text-gray-500 uppercase tracking-wider">Total</span>
                        </div>
                        <div class="flex items-center gap-2.5 px-4 py-2 bg-green-50 rounded-xl border border-green-200">
                            <i class="fa-solid fa-user-check text-green-500 text-[10px]"></i>
                            <span class="text-sm font-bold text-green-600" x-text="statistics.attended_tickets"></span>
                            <span class="text-[9px] text-gray-500 uppercase tracking-wider">Hadir</span>
                        </div>
                        <div class="flex items-center gap-2.5 px-4 py-2 bg-purple-50 rounded-xl border border-purple-200">
                            <i class="fa-solid fa-chart-line text-purple-500 text-[10px]"></i>
                            <span class="text-sm font-bold text-purple-600" x-text="statistics.attendance_rate + '%'"></span>
                            <span class="text-[9px] text-gray-500 uppercase tracking-wider">Rate</span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- ─── VERIFY HERO ─── --}}
        <div class="relative mb-8">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/5 via-transparent to-purple-500/5 rounded-[2.5rem]"></div>
            <div class="relative bg-white border border-gray-200 rounded-[2.5rem] p-8 lg:p-12 shadow-sm">
                <div class="max-w-2xl mx-auto text-center">
                    <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-2xl shadow-lg shadow-blue-500/20 mb-6">
                        <i class="fa-solid fa-ticket"></i>
                    </div>

                    <h2 class="text-xl lg:text-2xl font-black mb-2 text-gray-900">Verifikasi Tiket</h2>
                    <p class="text-sm text-gray-500 mb-8">Masukkan kode tiket peserta untuk verifikasi kehadiran.</p>

                    <div class="flex gap-3 max-w-xl mx-auto">
                        <div class="flex-1 relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                                <i class="fa-solid fa-hashtag text-blue-500 text-xs"></i>
                            </div>
                            <input type="text" x-model="manualTicketId" x-ref="ticketInput"
                                   placeholder="Kode Tiket"
                                   @keyup.enter="verifyManualTicket()"
                                   class="w-full bg-white border border-gray-200 rounded-xl pl-14 pr-4 py-4 text-base text-gray-900 focus:border-blue-500 outline-none transition-all"
                                   :class="{'border-green-500 ring-2 ring-green-500/20': showResult && resultData?.type === 'success', 'border-red-500 ring-2 ring-red-500/20': showResult && resultData?.type === 'error'}">
                        </div>
                        <button @click="verifyManualTicket()"
                                :disabled="!selectedEventId || !manualTicketId"
                                :class="!selectedEventId || !manualTicketId ? 'opacity-40 cursor-not-allowed' : 'hover:bg-blue-700 active:scale-95'"
                                class="bg-blue-600 text-white px-8 rounded-xl text-sm font-bold transition-all shadow-lg shadow-blue-600/20 whitespace-nowrap">
                            <i class="fa-solid fa-check mr-2"></i> Verifikasi
                        </button>
                    </div>

                    <template x-if="!selectedEventId">
                        <div class="flex items-center justify-center gap-2 mt-5 text-xs text-gray-500">
                            <i class="fa-solid fa-circle-info"></i>
                            <span>Pilih event terlebih dahulu sebelum verifikasi</span>
                        </div>
                    </template>
                    <template x-if="selectedEventId && !manualTicketId">
                        <div class="flex items-center justify-center gap-2 mt-5 text-xs text-gray-500">
                            <i class="fa-solid fa-keyboard"></i>
                            <span>Ketik kode tiket lalu tekan Enter atau tombol Verifikasi</span>
                        </div>
                    </template>

                    <div class="flex items-center justify-center gap-6 mt-6 text-[10px] text-gray-500">
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-hashtag text-[8px]"></i> Kode tiket ada di email</span>
                        <span class="w-px h-3 bg-gray-200"></span>
                        <span class="flex items-center gap-1.5"><i class="fa-regular fa-clock text-[8px]"></i> Real-time verification</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── RESULT AREA ─── --}}
        <div class="mb-8">
            <template x-if="showResult && resultData">
                <div x-cloak x-show="showResult" x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     :class="resultData.type === 'success' ? 'bg-green-50 border-green-200' : resultData.type === 'error' ? 'bg-red-50 border-red-200' : 'bg-yellow-50 border-yellow-200'"
                     class="border rounded-[2rem] p-6 lg:p-8 relative overflow-hidden">

                    <div :class="resultData.type === 'success' ? 'bg-green-500' : resultData.type === 'error' ? 'bg-red-500' : 'bg-yellow-500'"
                         class="absolute top-0 left-0 right-0 h-1.5"></div>

                    <div class="flex flex-col lg:flex-row lg:items-start gap-6 mt-2">
                        <div :class="resultData.type === 'success' ? 'bg-green-500 shadow-lg shadow-green-500/30' : resultData.type === 'error' ? 'bg-red-500 shadow-lg shadow-red-500/30' : 'bg-yellow-500 shadow-lg shadow-yellow-500/30'"
                             class="w-16 h-16 rounded-2xl flex items-center justify-center text-white text-3xl flex-shrink-0 mx-auto lg:mx-0">
                            <i :class="resultData.type === 'success' ? 'fa-solid fa-check-double' : resultData.type === 'error' ? 'fa-solid fa-xmark' : 'fa-solid fa-exclamation'"></i>
                        </div>
                        <div class="flex-1 min-w-0 text-center lg:text-left">
                            <h4 :class="resultData.type === 'success' ? 'text-green-600' : resultData.type === 'error' ? 'text-red-600' : 'text-yellow-600'"
                                class="font-black uppercase tracking-widest text-sm mb-1" x-text="resultData.message"></h4>

                            <template x-if="resultData.success && resultData.data?.name">
                                <div class="mt-5 bg-gray-50 rounded-2xl p-6 border border-gray-200">
                                    <div class="flex flex-col lg:flex-row lg:items-center gap-5">
                                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 text-white flex items-center justify-center font-bold text-xl uppercase flex-shrink-0 shadow-lg mx-auto lg:mx-0"
                                             x-text="(resultData.data.name || '?').charAt(0)"></div>
                                        <div class="text-center lg:text-left">
                                            <p class="text-xl font-black text-gray-900" x-text="resultData.data.name"></p>
                                            <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 text-sm text-gray-500 mt-1.5">
                                                <span class="flex items-center gap-1.5"><i class="fa-solid fa-tag text-[10px]"></i> <span x-text="resultData.data.ticket_type"></span></span>
                                                <template x-if="resultData.data.email">
                                                    <span class="flex items-center gap-1.5"><i class="fa-regular fa-envelope text-[10px]"></i> <span x-text="resultData.data.email"></span></span>
                                                </template>
                                            </div>
                                            <template x-if="resultData.data.event">
                                                <p class="text-xs text-gray-600 mt-2 flex items-center justify-center lg:justify-start gap-1.5">
                                                    <i class="fa-regular fa-calendar"></i> <span x-text="resultData.data.event"></span>
                                                </p>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <template x-if="!resultData.success">
                                <div class="mt-5 bg-gray-50 rounded-2xl p-5 border border-gray-200">
                                    <p class="text-sm text-gray-600 text-center lg:text-left" x-text="resultData.message"></p>
                                </div>
                            </template>

                            <div class="flex flex-col lg:flex-row items-center gap-4 mt-5">
                                <button @click="dismissResult()"
                                        class="text-xs font-bold text-gray-500 hover:text-gray-700 uppercase tracking-widest transition-all flex items-center gap-2">
                                    <i class="fa-solid fa-rotate-right"></i> Verifikasi Tiket Lainnya
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- ─── STATS + CHECKINS ─── --}}
        <div class="grid lg:grid-cols-12 gap-8">

            {{-- Left: Stats + Progress --}}
            <div class="lg:col-span-5 space-y-6">
                {{-- Stats Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-white border border-gray-200 rounded-2xl p-5 text-center shadow-sm">
                        <div class="w-9 h-9 mx-auto rounded-xl bg-blue-50 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-ticket text-blue-500 text-sm"></i>
                        </div>
                        <p class="text-2xl font-black text-gray-900" x-text="statistics?.total_tickets ?? '-'"></p>
                        <p class="text-[9px] text-gray-500 uppercase tracking-widest mt-1">Total</p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-2xl p-5 text-center shadow-sm">
                        <div class="w-9 h-9 mx-auto rounded-xl bg-green-50 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-user-check text-green-500 text-sm"></i>
                        </div>
                        <p class="text-2xl font-black text-green-600" x-text="statistics?.attended_tickets ?? '-'"></p>
                        <p class="text-[9px] text-gray-500 uppercase tracking-widest mt-1">Hadir</p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-2xl p-5 text-center shadow-sm">
                        <div class="w-9 h-9 mx-auto rounded-xl bg-purple-50 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-chart-line text-purple-500 text-sm"></i>
                        </div>
                        <p class="text-2xl font-black text-purple-600" x-text="statistics?.attendance_rate != null ? statistics.attendance_rate + '%' : '-'"></p>
                        <p class="text-[9px] text-gray-500 uppercase tracking-widest mt-1">Rate</p>
                    </div>
                </div>

                {{-- Progress --}}
                <template x-if="statistics && selectedEventId">
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-[10px] text-gray-500 uppercase tracking-widest">Progress Kehadiran</span>
                            <span class="text-xs font-bold text-gray-900" x-text="statistics.attended_tickets + ' / ' + statistics.total_tickets"></span>
                        </div>
                        <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-blue-500 via-purple-500 to-green-500 rounded-full transition-all duration-700 ease-out"
                                 :style="{ width: statistics.attendance_rate + '%' }"></div>
                        </div>
                    </div>
                </template>

                {{-- Empty state --}}
                <template x-if="!selectedEventId">
                    <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center shadow-sm">
                        <div class="w-14 h-14 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                            <i class="fa-solid fa-hand-pointer text-2xl text-gray-400"></i>
                        </div>
                        <p class="text-sm font-bold text-gray-600">Pilih Event</p>
                        <p class="text-[10px] text-gray-400 mt-1">Pilih event untuk melihat statistik.</p>
                    </div>
                </template>
            </div>

            {{-- Right: Recent Checkins --}}
            <div class="lg:col-span-7">
                <div class="bg-white border border-gray-200 rounded-[2rem] p-6 lg:p-8 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                                <i class="fa-solid fa-clock-rotate-left text-blue-500 text-sm"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900">Checkin Terbaru</h3>
                                <p class="text-[9px] text-gray-500">Peserta yang sudah terverifikasi</p>
                            </div>
                        </div>
                        <template x-if="filteredCheckins.length > 0">
                            <span class="text-[10px] font-bold px-3 py-1.5 rounded-full bg-blue-50 text-blue-600" x-text="filteredCheckins.length + ' peserta'"></span>
                        </template>
                    </div>

                    <template x-if="filteredCheckins.length > 0">
                        <div class="space-y-2 max-h-[520px] overflow-y-auto custom-scrollbar pr-1">
                            <template x-for="(item, idx) in filteredCheckins" :key="item.id">
                                <div x-data="{ hovered: false }"
                                     @mouseenter="hovered = true" @mouseleave="hovered = false"
                                     class="flex items-center gap-4 p-4 rounded-2xl transition-all duration-200"
                                     :class="hovered ? 'bg-gray-50 border border-gray-200' : 'bg-white border border-transparent'">
                                    <div class="relative flex-shrink-0">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 text-white flex items-center justify-center font-bold text-xs uppercase shadow-lg"
                                             x-text="item.initials">
                                        </div>
                                        <div class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-green-500 rounded-full border-2 border-white flex items-center justify-center">
                                            <i class="fa-solid fa-check text-[6px] text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-900 truncate" x-text="item.name"></p>
                                        <div class="flex items-center gap-2 text-[10px] text-gray-500 mt-0.5">
                                            <span class="flex items-center gap-1"><i class="fa-solid fa-tag text-[8px]"></i> <span x-text="item.ticket_type"></span></span>
                                            <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                            <span class="flex items-center gap-1"><i class="fa-regular fa-clock text-[8px]"></i> <span x-text="item.attended_at"></span></span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[8px] font-black px-3 py-1.5 rounded-full bg-green-50 text-green-600 uppercase tracking-wider border border-green-200">Hadir</span>
                                        <span class="text-[10px] text-gray-500 font-mono" x-text="'#' + (idx + 1)"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    <template x-if="filteredCheckins.length === 0 && selectedEventId">
                        <div class="text-center py-16">
                            <div class="w-20 h-20 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-5">
                                <i class="fa-solid fa-user-check text-3xl text-gray-400"></i>
                            </div>
                            <p class="text-base text-gray-600 font-bold">Belum Ada Checkin</p>
                            <p class="text-sm text-gray-400 mt-1 max-w-xs mx-auto">Verifikasi tiket pertama akan muncul di sini.</p>
                            <template x-if="!showResult">
                                <div class="mt-6 flex items-center justify-center gap-2 text-[10px] text-gray-500">
                                    <i class="fa-solid fa-arrow-up text-blue-500"></i>
                                    <span>Masukkan kode tiket di atas untuk memulai</span>
                                </div>
                            </template>
                        </div>
                    </template>

                    <template x-if="!selectedEventId">
                        <div class="text-center py-16">
                            <div class="w-20 h-20 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-5">
                                <i class="fa-solid fa-hand-pointer text-3xl text-gray-400"></i>
                            </div>
                            <p class="text-base text-gray-600 font-bold">Pilih Event</p>
                            <p class="text-sm text-gray-400 mt-1">Pilih event untuk melihat daftar checkin.</p>
                        </div>
                    </template>
                </div>
            </div>

        </div>

        @else
        <div class="flex flex-col items-center justify-center py-20">
            <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mb-8">
                <i class="fa-solid fa-calendar-xmark text-4xl text-gray-400"></i>
            </div>
            <h2 class="text-xl font-black text-gray-900 mb-2">Belum Ada Event</h2>
            <p class="text-gray-500 text-sm mb-8 text-center max-w-md">Buat event terlebih dahulu untuk mulai melakukan verifikasi kehadiran peserta.</p>
            <a href="{{ route('panitia.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold text-sm transition-all shadow-lg shadow-blue-600/20">
                <i class="fa-solid fa-plus"></i> Buat Event Baru
            </a>
        </div>
        @endif

    </main>

<script>
    function attendanceApp() {
        return {
            showResult: false,
            resultData: null,
            manualTicketId: '',
            selectedEventId: '',
            statistics: null,
            recentCheckins: @json($recentJson),

            get filteredCheckins() {
                if (!this.selectedEventId) return [];
                let items = this.recentCheckins.filter(c => c.event_id == this.selectedEventId);
                return items.map(item => ({
                    ...item,
                    initials: item.name ? item.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2) : '??'
                }));
            },

            init() {
                const events = @json($eventsJson);
                if (events.length === 1) {
                    this.selectedEventId = events[0].id;
                    this.loadStatistics();
                }
            },

            onEventChange() {
                this.showResult = false;
                this.resultData = null;
                this.loadStatistics();
            },

            dismissResult() {
                this.showResult = false;
                this.resultData = null;
                this.manualTicketId = '';
                this.$nextTick(() => {
                    if (this.$refs.ticketInput) this.$refs.ticketInput.focus();
                });
            },

            verifyManualTicket() {
                if (!this.manualTicketId || !this.selectedEventId) {
                    this.resultData = {
                        success: false,
                        message: 'Lengkapi data terlebih dahulu',
                        type: 'error',
                        data: { name: '', email: '', event: '', ticket_type: '' }
                    };
                    this.showResult = true;
                    return;
                }

                fetch('{{ route("panitia.verify-ticket") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        ticket_id: this.manualTicketId,
                        event_id: this.selectedEventId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.data && data.data.name) {
                        data.data.initials = data.data.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
                    }
                    this.resultData = data;
                    this.showResult = true;
                    this.manualTicketId = '';

                    if (data.success) {
                        this.loadStatistics();
                        this.loadCheckins();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.resultData = {
                        success: false,
                        message: 'Terjadi kesalahan',
                        type: 'error',
                        data: { name: '', email: '', event: '', ticket_type: '' }
                    };
                    this.showResult = true;
                });
            },

            loadStatistics() {
                if (!this.selectedEventId) {
                    this.statistics = null;
                    return;
                }
                fetch(`{{ route("panitia.attendance-stats") }}?event_id=${this.selectedEventId}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Failed to load statistics');
                        return response.json();
                    })
                    .then(data => {
                        this.statistics = data;
                    })
                    .catch(error => {
                        console.error('Error loading statistics:', error);
                        this.statistics = null;
                    });
            },

            loadCheckins() {
                fetch(`{{ route("panitia.attendance-stats") }}?event_id=${this.selectedEventId}&checkins=1`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.checkins) {
                            this.recentCheckins = data.checkins.map(item => ({
                                ...item,
                                initials: item.name ? item.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2) : '??'
                            }));
                        }
                    })
                    .catch(() => {});
            }
        }
    }
</script>

<style>
    [x-cloak] { display: none !important; }
    .custom-scrollbar::-webkit-scrollbar { width: 3px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
</style>


</body>
</html>