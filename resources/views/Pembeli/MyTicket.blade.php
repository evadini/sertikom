<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticketify | My Tickets</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @include('components.fonts')
</head>
<body class="bg-[#F8FAFC] text-gray-900 antialiased">

    <div class="flex flex-col min-h-screen">
        @include('components.pembeli-nav')

        <main class="flex-1 overflow-y-auto p-8">
            <header class="mb-8">
                <h2 class="text-2xl font-black tracking-tight text-gray-900">Ticket Summary</h2>
                <p class="text-gray-500 text-sm mt-1">Track your event journeys here.</p>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-10">
                <div class="bg-white border border-gray-200 shadow-sm p-6 rounded-2xl relative overflow-hidden group">
                    <i class="fa-solid fa-layer-group absolute -right-2 -bottom-2 text-5xl text-gray-100 group-hover:text-blue-100 transition-colors"></i>
                    <p class="text-[10px] font-bold uppercase tracking-widest mb-1 text-blue-600">Total Ticket</p>
                    <p class="text-3xl font-black text-gray-900">{{ $totalTickets }}</p>
                </div>
                <div class="bg-white border border-gray-200 shadow-sm p-6 rounded-2xl relative overflow-hidden group">
                    <i class="fa-solid fa-circle-check absolute -right-2 -bottom-2 text-5xl text-gray-100 group-hover:text-emerald-100 transition-colors"></i>
                    <p class="text-[10px] font-bold uppercase tracking-widest mb-1 text-emerald-600">Active</p>
                    <p class="text-3xl font-black text-emerald-600">{{ $activeTickets }}</p>
                </div>
                <div class="bg-white border border-gray-200 shadow-sm p-6 rounded-2xl relative overflow-hidden group">
                    <i class="fa-solid fa-clock-rotate-left absolute -right-2 -bottom-2 text-5xl text-gray-100 group-hover:text-gray-200 transition-colors"></i>
                    <p class="text-[10px] font-bold uppercase tracking-widest mb-1 text-red-600">Used</p>
                    <p class="text-3xl font-black text-gray-500">{{ $usedTickets }}</p>
                </div>
                <div class="bg-white border border-gray-200 shadow-sm p-6 rounded-2xl relative overflow-hidden group">
                    <i class="fa-solid fa-ban absolute -right-2 -bottom-2 text-5xl text-gray-100 group-hover:text-red-100 transition-colors"></i>
                    <p class="text-[10px] font-bold uppercase tracking-widest mb-1 text-red-600">Canceled</p>
                    <p class="text-3xl font-black text-red-600">{{ $canceledTickets }}</p>
                </div>
            </div>

            <h2 class="text-xl font-black tracking-tight text-gray-900 mb-6">Purchase History</h2>

            <div class="space-y-4">
                @forelse($tickets as $ticket)
                    @php
                        $badgeBg = match($ticket->status) {
                            'Active' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                            'Used' => 'bg-red-50 text-red-600 border-red-200',
                            'Canceled' => 'bg-gray-100 text-gray-500 border-gray-200',
                            default => 'bg-gray-100 text-gray-500 border-gray-200',
                        };
                        $isDimmed = in_array($ticket->status, ['Used', 'Canceled']);
                    @endphp
                    <div class="ticket-card bg-white border border-gray-200 shadow-sm rounded-2xl p-5 transition-all duration-300 {{ $isDimmed ? 'opacity-60' : '' }}">
                        <div class="flex flex-wrap md:flex-nowrap items-center gap-6">
                            <div class="w-full md:w-32 h-20 rounded-xl overflow-hidden border border-gray-200 {{ $isDimmed ? 'grayscale' : '' }}">
                                @if($ticket->event && $ticket->event->banner)
                                    <img src="{{ asset('images/events/' . $ticket->event->banner) }}" class="w-full h-full object-cover {{ $isDimmed ? 'opacity-40' : '' }}" alt="Event">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center">
                                        <i class="fa-solid fa-ticket text-2xl text-white/50"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1">
                                <span class="inline-block px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-tight border {{ $badgeBg }}">{{ $ticket->status }}</span>
                                <h3 class="text-lg font-black tracking-tight mt-1 text-gray-900">{{ $ticket->event->name ?? 'Event Tidak Ditemukan' }}</h3>
                                <p class="text-xs text-gray-500 mt-1"><i class="fa-solid fa-calendar mr-2"></i>{{ $ticket->event->date_start ? $ticket->event->date_range : 'TBA' }}</p>
                            </div>

                            <div class="flex-1">
                                <span class="inline-block px-2 py-0.5 bg-blue-50 text-blue-600 border border-blue-200 rounded text-[9px] font-black uppercase tracking-tight">{{ $ticket->ticket_type }}</span>
                                <p class="text-xs text-gray-500 mt-1">Ticket Type</p>
                            </div>

                            <div class="text-right">
                                <p class="text-[10px] font-bold text-gray-400 uppercase">Date Purchased</p>
                                <p class="text-sm font-black text-gray-900">{{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                            </div>

                            @if($ticket->status === 'Active')
                                <a href="{{ route('pembeli.ticketdigital', $ticket->id) }}" class="w-full md:w-auto px-6 py-2.5 bg-blue-600 text-white font-black rounded-xl text-[10px] uppercase hover:bg-blue-700 transition-all inline-block text-center shadow-sm">
                                    View Ticket
                                </a>
                            @elseif($ticket->status === 'Canceled')
                                <button type="button" onclick="openRefundModal({{ $ticket->id }})" class="w-full md:w-auto px-6 py-2.5 bg-red-50 border border-red-200 text-red-600 font-black rounded-xl text-[10px] hover:bg-red-100 transition-all uppercase">
                                    <i class="fa-solid fa-circle-info mr-1"></i> Refund Info
                                </button>
                            @else
                                <button class="w-full md:w-auto px-6 py-2.5 bg-gray-100 border border-gray-200 text-gray-500 font-black rounded-xl text-[10px] cursor-not-allowed uppercase">
                                    {{ $ticket->status === 'Used' ? 'Used' : 'Expired' }}
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-16 text-center bg-white border border-gray-200 shadow-sm rounded-2xl">
                        <i class="fa-solid fa-ticket text-5xl text-gray-200 mb-4"></i>
                        <p class="text-gray-500 font-semibold mb-2">Belum Ada Tiket Pembelian</p>
                        <p class="text-gray-400 text-sm mb-4">Mulai cari event menarik dan beli tiket sekarang!</p>
                        <a href="{{ route('pembeli.event') }}" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-xl text-sm transition shadow-sm">
                            Jelajahi Event
                        </a>
                    </div>
                @endforelse
            </div>
        </main>
    </div>

    @php
        $refundData = [];
        foreach ($tickets as $t) {
            if ($t->status === 'Canceled' && $t->event) {
                $refundData[$t->id] = [
                    'name' => $t->event->name,
                    'unpublish_reason' => $t->event->unpublish_reason,
                    'refund_date' => $t->event->refund_date,
                    'refund_location' => $t->event->refund_location,
                    'refund_info' => $t->event->refund_info,
                ];
            }
        }
    @endphp

    <div id="refundModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md border border-gray-200 shadow-2xl">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900">Event Dibatalkan</h2>
                    <p class="text-xs text-gray-500">Informasi refund tiket kamu</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Nama Event</p>
                    <p id="refundEventName" class="text-sm font-bold text-gray-900">-</p>
                </div>

                <div>
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Alasan Pembatalan</p>
                    <p id="refundReason" class="text-sm text-gray-700 bg-gray-50 rounded-xl p-3 border border-gray-200">-</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Tanggal Refund</p>
                        <p id="refundDate" class="text-sm font-bold text-gray-900">-</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Lokasi Refund</p>
                        <p id="refundLocation" class="text-sm font-bold text-gray-900">-</p>
                    </div>
                </div>

                <div>
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Info Tambahan</p>
                    <p id="refundInfo" class="text-sm text-gray-700 bg-gray-50 rounded-xl p-3 border border-gray-200">-</p>
                </div>
            </div>

            <button type="button" onclick="closeRefundModal()" class="w-full mt-6 py-2.5 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-50 transition">
                Tutup
            </button>
        </div>
    </div>

    <script>
        const refundData = @json($refundData);

        function openRefundModal(ticketId) {
            const data = refundData[ticketId] || {};
            document.getElementById('refundEventName').textContent = data.name || '-';
            document.getElementById('refundReason').textContent = data.unpublish_reason || '-';
            document.getElementById('refundDate').textContent = data.refund_date
                ? new Date(data.refund_date).toLocaleString('id-ID', { dateStyle: 'long', timeStyle: 'short' })
                : 'Belum ditentukan';
            document.getElementById('refundLocation').textContent = data.refund_location || 'Belum ditentukan';
            document.getElementById('refundInfo').textContent = data.refund_info || 'Tidak ada info tambahan';
            document.getElementById('refundModal').classList.remove('hidden');
            document.getElementById('refundModal').classList.add('flex');
        }

        function closeRefundModal() {
            document.getElementById('refundModal').classList.add('hidden');
            document.getElementById('refundModal').classList.remove('flex');
        }
    </script>
</body>
</html>
