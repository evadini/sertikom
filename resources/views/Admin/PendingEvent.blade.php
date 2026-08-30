@extends('layouts.admin')

@section('title', 'Pending Events')

@section('content')

<div class="px-8 mt-6">
    <x-event-filter :categories="$categories" />
</div>

<x-alert-toast />

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 px-8 pb-8 mt-6">
    @forelse($pendingEvents as $event)
        @php
            $ticketsJson = json_encode($event->tickets->whereNull('order_id')->values()->map(fn($t) => ['ticket_type' => $t->ticket_type, 'price' => $t->price, 'stock' => $t->stock]));
            $minPrice = $event->tickets->whereNull('order_id')->min('price');
            $priceLabel = $minPrice ? 'Rp ' . number_format($minPrice, 0, ',', '.') : 'Gratis';
        @endphp
        <x-event-card
            :image="$event->banner ? asset('images/events/' . $event->banner) : asset('images/events/banner_1779635248.jpg')"
            :day="\Carbon\Carbon::parse($event->date_start)->format('d')"
            :month="\Carbon\Carbon::parse($event->date_start)->translatedFormat('M')"
            :year="\Carbon\Carbon::parse($event->date_start)->format('Y')"
            :category="$event->category?->name"
            :title="$event->name"
            :location="$event->location"
            :startTime="\Carbon\Carbon::parse($event->time_start)->format('H:i')"
            :endTime="\Carbon\Carbon::parse($event->time_end)->format('H:i')"
            :price="$priceLabel"
        >
            <button type="button"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-xs font-bold transition"
                data-id="{{ $event->id }}"
                data-banner="{{ $event->banner }}"
                data-name="{{ $event->name }}"
                data-date="{{ $event->date_start }}"
                data-time="{{ $event->time_start }}"
                data-location="{{ $event->location }}"
                data-category="{{ $event->category?->name }}"
                data-description="{{ $event->description }}"
                data-tickets='{!! $ticketsJson !!}'
                onclick="openDetailFromElement(this)">
                Detail
            </button>
            <button type="button"
                class="px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-lg text-xs font-bold transition"
                data-event='@json($event)'
                onclick="openApproveModal(this)">
                Setujui
            </button>
            <button type="button"
                class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-xs font-bold transition"
                data-event='@json($event)'
                onclick="openRejectModal(this)">
                Tolak
            </button>
        </x-event-card>
    @empty
        <div class="col-span-full text-center text-gray-500 py-20 bg-white border border-gray-200 rounded-3xl shadow-sm">
            <i class="fa-solid fa-calendar-xmark text-3xl text-gray-300 mb-3 block"></i>
            Tidak ada event pending.
        </div>
    @endforelse
</div>

<div id="detailModal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-5xl max-h-[90vh] rounded-3xl border border-gray-200 overflow-hidden shadow-2xl">
        <div class="bg-blue-600 px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-eye text-white"></i>
                <h2 class="text-white font-bold text-lg">Detail Event</h2>
            </div>
            <button onclick="closeDetail()" class="text-white text-xl hover:opacity-70 transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="p-6 space-y-6 overflow-y-auto max-h-[calc(90vh-140px)]">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                <div>
                    <div class="rounded-2xl overflow-hidden border border-gray-200">
                        <img id="detailPoster" src="" alt="Poster Event" class="w-full h-[340px] object-cover">
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="flex items-center gap-2">
                            <i class="fa-regular fa-calendar text-blue-500 w-4"></i>
                            <span class="text-gray-500 text-xs">Tanggal</span>
                        </div>
                        <div class="text-right">
                            <span id="detailDate" class="font-semibold text-gray-900">-</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-regular fa-clock text-blue-500 w-4"></i>
                            <span class="text-gray-500 text-xs">Waktu</span>
                        </div>
                        <div class="text-right">
                            <span id="detailTime" class="font-semibold text-gray-900">-</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-red-500 w-4"></i>
                            <span class="text-gray-500 text-xs">Lokasi</span>
                        </div>
                        <div class="text-right">
                            <span id="detailLocation" class="font-semibold text-gray-900">-</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-folder text-yellow-500 w-4"></i>
                            <span class="text-gray-500 text-xs">Kategori</span>
                        </div>
                        <div class="text-right">
                            <span id="detailCategory" class="font-semibold text-gray-900">-</span>
                        </div>
                    </div>
                    <div>
                        <h3 id="detailTitle" class="text-blue-600 font-bold text-2xl">-</h3>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-2 font-bold">Deskripsi</p>
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm text-gray-700 leading-relaxed max-h-32 overflow-y-auto">
                            <span id="detailDesc">-</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="space-y-4">
                <h3 class="text-xl font-bold text-gray-900">Available Tickets</h3>
                <div id="detailTickets" class="space-y-3"></div>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 text-right">
            <button onclick="closeDetail()" class="px-6 py-2 bg-gray-100 rounded-xl text-sm text-gray-700 hover:bg-gray-200 transition">Tutup</button>
        </div>
    </div>
</div>

<div id="approveModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md border border-gray-200 shadow-2xl text-center">
        <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-check text-emerald-500 text-2xl"></i>
        </div>
        <h3 class="text-xl font-black text-gray-900 mb-2">Setujui Event?</h3>
        <p id="approveEventName" class="text-gray-500 text-sm mb-6">Event akan dipublikasikan ke halaman utama.</p>
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

<div id="rejectModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl p-6 w-full max-w-lg border border-gray-200 shadow-2xl">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-gray-900">Tolak Event</h2>
                <p class="text-xs text-gray-500">Berikan alasan mengapa event ini ditolak</p>
            </div>
        </div>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="mb-4">
                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2 block">
                    Alasan Penolakan <span class="text-red-500">*</span>
                </label>
                <textarea name="reason" id="rejectReason" rows="3"
                    placeholder="Jelaskan alasan mengapa event ini ditolak... (min. 10 karakter)"
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
    function openDetailFromElement(btn) {
        const dv = btn.dataset;
        const banner  = dv.banner;
        const name    = dv.name;
        const dateStr = dv.date;
        const timeStr = dv.time;
        const loc     = dv.location;
        const cat     = dv.category;
        const desc    = dv.description;
        let tickets   = [];

        try { tickets = JSON.parse(dv.tickets || '[]'); } catch(e) {}

        const date = new Date(dateStr);
        const day = String(date.getDate()).padStart(2, '0');
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        const month = months[date.getMonth()] || '';
        const fullDate = `${day} ${month} ${date.getFullYear()}`;

        document.getElementById('detailPoster').src = banner
            ? '/images/events/' + banner
            : '/images/events/banner_1779635248.jpg';
        document.getElementById('detailDate').textContent = fullDate || '-';
        const timeDisplay = timeStr ? timeStr.slice(0, 5) : '-';
        document.getElementById('detailTime').textContent = `${timeDisplay} WIB`;
        document.getElementById('detailTitle').textContent = name || '-';
        document.getElementById('detailLocation').textContent = loc || '-';
        document.getElementById('detailCategory').textContent = cat || '-';
        document.getElementById('detailDesc').textContent = desc || 'Tidak ada deskripsi.';

        const ticketContainer = document.getElementById('detailTickets');
        ticketContainer.innerHTML = '';
        if (tickets.length > 0) {
            tickets.forEach(t => {
                const div = document.createElement('div');
                div.className = 'flex justify-between items-center bg-gray-50 border border-gray-200 rounded-xl p-4';
                div.innerHTML = `
                    <div>
                        <p class="font-bold text-sm text-gray-900">${t.ticket_type || 'Reguler'}</p>
                        <p class="text-xs text-gray-500 mt-1">Stok: ${t.stock ?? 'Unlimited'}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-black text-blue-600">${t.price ? 'Rp ' + Number(t.price).toLocaleString('id-ID') : 'Gratis'}</p>
                    </div>
                `;
                ticketContainer.appendChild(div);
            });
        } else {
            ticketContainer.innerHTML = '<p class="text-gray-500 text-sm">Tidak ada tiket tersedia.</p>';
        }

        document.getElementById('detailModal').classList.remove('hidden');
        document.getElementById('detailModal').classList.add('flex');
    }

    function closeDetail() {
        document.getElementById('detailModal').classList.add('hidden');
        document.getElementById('detailModal').classList.remove('flex');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.getElementById(id).classList.remove('flex');
    }

    function openApproveModal(btn) {
        const event = JSON.parse(btn.getAttribute('data-event'));
        document.getElementById('approveEventName').textContent = `"${event.name}" akan dipublikasikan ke halaman utama.`;
        const form = document.getElementById('approveForm');
        form.action = `{{ url('admin/events') }}/${event.id}/approve`;
        document.getElementById('approveModal').classList.remove('hidden');
        document.getElementById('approveModal').classList.add('flex');
    }

    function openRejectModal(btn) {
        const event = JSON.parse(btn.getAttribute('data-event'));
        const form = document.getElementById('rejectForm');
        form.action = `{{ url('admin/events') }}/${event.id}/reject`;
        document.getElementById('rejectReason').value = '';
        document.getElementById('rejectReasonCount').textContent = '0';
        document.getElementById('rejectReasonError').classList.add('hidden');
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
