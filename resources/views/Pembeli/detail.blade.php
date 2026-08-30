<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticketify - {{ $event->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @include('components.fonts')

    <script type="text/javascript"
        src="{{ asset('js/snap.js') }}"
        data-client-key="{{ config('midtrans.client_key') }}">
    </script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-[#F8FAFC] text-gray-900 flex flex-col min-h-screen">

    @include('components.pembeli-nav')

    <main class="flex-1 p-6 md:p-10" x-data="{ tab: 'ticket' }">
    <div class="max-w-6xl mx-auto space-y-8">

        {{-- HEADER --}}
        <header class="flex items-center justify-between flex-wrap">
            <div class="flex items-center gap-4">
                <button onclick="window.history.back()"
                    class="bg-white hover:bg-gray-50 text-gray-700 transition-all px-4 py-2 rounded-lg text-xs font-bold border border-gray-200 flex items-center gap-2 cursor-pointer shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </button>
                <div>
                    <h1 class="text-2xl font-black uppercase text-gray-900 truncate">{{ $event->name }}</h1>
                    <p class="text-xs text-gray-500 mt-1">{{ $event->category?->name }}</p>
                </div>
            </div>

            @php $displayStatus = $event->getDisplayStatus(); @endphp
            <div class="px-4 py-2 rounded-lg border {{ $displayStatus === 'published' ? 'bg-emerald-50 border-emerald-200' : 'bg-red-50 border-red-200' }}">
                <span class="text-xs font-bold uppercase tracking-wider {{ $displayStatus === 'published' ? 'text-emerald-700' : 'text-red-700' }}">
                    <i class="fa-solid {{ $displayStatus === 'published' ? 'fa-check-circle' : 'fa-exclamation-circle' }} mr-1"></i>
                    {{ $displayStatus === 'published' ? 'Tersedia' : ucfirst($displayStatus) }}
                </span>
            </div>
        </header>

        {{-- TAB NAVIGATION --}}
        <nav class="border-b border-gray-200 flex justify-center overflow-x-auto">
            <div class="flex gap-2 flex-nowrap">
                @php
                    $tabs = [
                        'ticket'    => ['icon' => 'fa-ticket',       'label' => 'Ticket'],
                        'details'   => ['icon' => 'fa-circle-info',  'label' => 'Details'],
                        'organiser' => ['icon' => 'fa-users',        'label' => 'Organiser'],
                    ];
                @endphp

                @foreach ($tabs as $key => $item)
                    <button @click="tab = '{{ $key }}'"
                            :class="tab === '{{ $key }}'
                                ? 'border-b-2 border-blue-600 text-blue-600'
                                : 'text-gray-500 hover:text-gray-700'"
                            class="pb-4 px-4 font-bold text-sm transition-all flex items-center gap-2">
                        <i class="fa-solid {{ $item['icon'] }}"></i>
                        {{ $item['label'] }}
                    </button>
                @endforeach
            </div>
        </nav>

        {{-- TAB CONTENT --}}
        <div class="bg-white rounded-3xl p-8 border border-gray-200 shadow-sm">

            {{-- TAB: TICKET --}}
            <div x-show="tab === 'ticket'" x-transition>
                <div class="grid md:grid-cols-2 gap-10 mb-10 pb-10 border-b border-gray-100">
                    <div class="rounded-2xl overflow-hidden border border-gray-200 shadow-sm aspect-video">
                        <img src="{{ $event->banner ? asset('images/events/' . $event->banner) : asset('images/kmipn.jpeg') }}"
                             alt="Banner {{ $event->name }}"
                             class="w-full h-full object-cover">
                    </div>

                    <div class="space-y-4">
                        <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100">
                            <h4 class="text-blue-600 font-bold text-sm mb-2">
                                <i class="fa-solid fa-calendar-days mr-2"></i>Tanggal Event
                            </h4>
                            <p class="text-xs text-gray-700 font-bold">{{ $event->date_range }}, {{ substr($event->time_start, 0, 5) }} - {{ substr($event->time_end, 0, 5) }} WIB</p>
                        </div>

                        <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100">
                            <h4 class="text-blue-600 font-bold text-sm mb-2">
                                <i class="fa-solid fa-location-dot text-red-500 mr-2"></i>Location
                            </h4>
                            <p class="text-xs text-gray-700 font-bold leading-relaxed">{{ $event->location }}</p>
                        </div>


                    </div>
                </div>

                {{-- FORM PILIH TIKET --}}
                <div class="space-y-4 max-w-4xl mx-auto" x-data="ticketApp()">
                    <h3 class="font-bold text-lg text-gray-900 mb-4">Pilih Tipe Tiket</h3>

                    @forelse ($event->tickets->whereNull('order_id') as $index => $ticket)
                        <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 flex items-center justify-between hover:border-blue-300 transition-all">
                            <div>
                                <h3 class="font-bold text-gray-900"><i class="fa-solid fa-ticket text-blue-600 mr-2"></i> {{ $ticket->ticket_type }}</h3>
                                <span class="text-[10px] text-gray-500">Stok tersedia: {{ $ticket->stock ?? 0 }}</span>
                            </div>
                            <div class="flex items-center gap-6">
                                <span class="font-black text-blue-600">
                                    @if(($ticket->price ?? 0) == 0)
                                        GRATIS
                                    @else
                                        IDR {{ number_format($ticket->price, 0, ',', '.') }}
                                    @endif
                                </span>

                                {{-- Counter Plus/Minus --}}
                                <div class="flex items-center bg-white rounded-lg border border-gray-200">
                                    <button type="button"
                                            @click="decrement({{ $index }})"
                                            class="px-3 py-1 text-gray-600 hover:text-blue-600 font-bold">-</button>
                                    <span class="px-3 font-bold text-sm text-gray-900" x-text="quantities[{{ $index }}]">0</span>
                                    <button type="button"
                                            @click="increment({{ $index }}, {{ $ticket->stock ?? 0 }})"
                                            class="px-3 py-1 text-gray-600 hover:text-blue-600 font-bold">+</button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-400 py-6">Tidak ada tipe tiket yang tersedia.</p>
                    @endforelse

                    {{-- Summary total --}}
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 flex justify-between items-center" x-show="totalQty > 0">
                        <span class="text-sm text-gray-500">Total (<span x-text="totalQty"></span> tiket)</span>
                        <span class="font-black text-blue-600 text-lg">IDR <span x-text="formatRupiah(totalAmount)"></span></span>
                    </div>

                    <div class="pt-6 border-t border-gray-100 flex justify-center">
                        <button
                            @click="bayar()"
                            :disabled="totalQty === 0 || isLoading"
                            :class="totalQty === 0 || isLoading
                                ? 'bg-gray-300 cursor-not-allowed opacity-50 text-gray-500'
                                : 'bg-blue-600 hover:bg-blue-700 cursor-pointer text-white'"
                            class="px-8 py-3 rounded-xl font-bold transition flex items-center gap-2">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <span x-text="isLoading ? 'Memproses...' : 'Beli Tiket'"></span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- TAB: DETAILS --}}
            <div x-show="tab === 'details'" x-transition style="display:none">
                <div class="space-y-6">
                    <div class="bg-gray-50 p-8 rounded-3xl border border-gray-100">
                        <h3 class="text-center font-bold text-blue-600 mb-4 text-sm">
                            <i class="fa-solid fa-align-left mr-2"></i>Description
                        </h3>
                        <p class="text-sm text-gray-700 leading-relaxed text-justify">
                            {!! nl2br(e($event->description)) !!}
                        </p>
                    </div>

                    <div class="bg-gray-50 p-8 rounded-3xl border border-gray-100">
                        <h3 class="text-center font-bold text-blue-600 mb-6 text-sm">
                            <i class="fa-solid fa-file-lines mr-2"></i>Terms and Conditions
                        </h3>
                        <div class="text-xs text-gray-700 leading-relaxed whitespace-pre-line">
                            {{ $event->terms }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB: ORGANISER --}}
            <div x-show="tab === 'organiser'" x-transition style="display:none">
                <div class="space-y-6">
                    <div class="bg-gray-50 p-10 rounded-3xl border border-gray-100 text-center">
                        <i class="fa-solid fa-users text-blue-600 text-3xl mb-6"></i>
                        <p class="max-w-2xl mx-auto text-sm text-gray-600 leading-relaxed italic">
                            {{ $event->organiser_description ?? 'Belum ada deskripsi organiser.' }}
                        </p>
                    </div>

                    <div class="bg-gray-50 p-8 rounded-3xl border border-gray-100 text-center">
                        <h3 class="font-bold mb-6 text-gray-900 uppercase tracking-widest text-sm">
                            Organizing Committee
                        </h3>
                        <img src="{{ $event->organiser_photo ? asset('images/organizers/' . $event->organiser_photo) : asset('images/panitia_kmipn.jpeg') }}"
                             alt="Organizing Committee"
                             class="w-full rounded-2xl border border-gray-200">
                    </div>
                </div>
            </div>

        </div>
    </div>
    </main>

    {{-- MODAL PEMBAYARAN BERHASIL --}}
    <div id="success-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/30 backdrop-blur-sm">
        <div class="bg-white border border-gray-200 rounded-3xl p-10 max-w-md w-full mx-4 text-center shadow-2xl">
            <div class="w-20 h-20 rounded-full bg-green-100 border-2 border-green-500 flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-check text-green-600 text-3xl"></i>
            </div>

            <h2 class="text-2xl font-black text-gray-900 mb-2">Pembayaran Berhasil!</h2>
            <p class="text-gray-500 text-sm mb-2">Tiket digital kamu sudah digenerate.</p>
            <p class="text-blue-600 font-bold text-sm mb-6" id="modal-order-code"></p>

            <div class="bg-gray-50 rounded-2xl p-4 mb-6 border border-gray-100 text-left space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Event</span>
                    <span class="font-bold text-gray-900 text-right max-w-[180px]">{{ $event->name }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Tanggal</span>
                    <span class="font-bold text-gray-900">{{ $event->date_range }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Tiket</span>
                    <span class="font-bold text-gray-900 text-right max-w-[220px]" id="modal-ticket-summary">-</span>
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('pembeli.myticket') }}"
                   class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold text-sm transition">
                    <i class="fa-solid fa-ticket mr-2"></i>Lihat Tiket Saya
                </a>
                <button onclick="closeSuccessModal()"
                        class="flex-1 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 px-6 py-3 rounded-xl font-bold text-sm transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- ALPINE DATA + MIDTRANS LOGIC --}}
    @php
        $ticketData = $event->tickets->whereNull('order_id')->map(function($t) {
            return [
                'id'    => $t->id,
                'name'  => $t->ticket_type,
                'price' => $t->price ?? 0,
                'stock' => $t->stock ?? 0,
            ];
        })->values()->toArray();
    @endphp
    <script>
        const ticketTypes = @json($ticketData);
        const snapTokenUrl = "{{ route('payment.snap-token', $event->id) }}";
        const handleSuccessUrl = "{{ route('payment.handle-success') }}";
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function ticketApp() {
            return {
                quantities: ticketTypes.map(() => 0),
                isLoading: false,

                get totalQty() {
                    return this.quantities.reduce((sum, q) => sum + q, 0);
                },

                get totalAmount() {
                    return this.quantities.reduce((sum, q, i) => {
                        return sum + (q * (ticketTypes[i]?.price ?? 0));
                    }, 0);
                },

                increment(index, stock) {
                    const current = this.quantities[index];
                    if (current < stock) {
                        this.quantities[index]++;
                    }
                },

                decrement(index) {
                    if (this.quantities[index] > 0) {
                        this.quantities[index]--;
                    }
                },

                formatRupiah(num) {
                    return num.toLocaleString('id-ID');
                },

                get selectedItems() {
                    return ticketTypes
                        .map((t, i) => ({ ...t, qty: this.quantities[i] }))
                        .filter(t => t.qty > 0);
                },

                get summaryText() {
                    return this.selectedItems.map(t => `${t.name} x${t.qty}`).join(', ');
                },

                async bayar() {
                    if (this.totalQty === 0 || this.isLoading) return;

                    const items = this.selectedItems;
                    if (items.length === 0) return;

                    this.isLoading = true;

                    try {
                        const res = await fetch(snapTokenUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: JSON.stringify({
                                items: items.map(t => ({
                                    ticket_type: t.name,
                                    quantity: t.qty,
                                    price: t.price,
                                })),
                            }),
                        });

                        const data = await res.json();

                        if (!res.ok || data.error) {
                            alert('Gagal membuat transaksi: ' + (data.error ?? 'Unknown error'));
                            this.isLoading = false;
                            return;
                        }

                        const orderId   = data.order_id;
                        const snapToken = data.snap_token;

                        if (typeof window.snap === 'undefined') {
                            alert('Gagal memuat pembayaran. Coba refresh halaman.');
                            this.isLoading = false;
                            return;
                        }

                        window.snap.pay(snapToken, {
                            onSuccess: async (result) => {
                                const successRes = await fetch(handleSuccessUrl, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken,
                                    },
                                    body: JSON.stringify({
                                        order_id:       orderId,
                                        transaction_id: result.transaction_id,
                                        payment_type:   result.payment_type,
                                    }),
                                });

                                const successData = await successRes.json();

                                if (successData.success) {
                                    showSuccessModal(successData.order_code, this.summaryText);
                                }
                            },
                            onPending: (result) => {
                                alert('Pembayaran pending. Silakan selesaikan pembayaran kamu.');
                            },
                            onError: (result) => {
                                alert('Pembayaran gagal. Silakan coba lagi.');
                            },
                            onClose: () => {},
                        });

                    } catch (err) {
                        alert('Terjadi kesalahan: ' + err.message);
                    } finally {
                        this.isLoading = false;
                    }
                },
            };
        }

        function showSuccessModal(orderCode, summary) {
            document.getElementById('modal-order-code').textContent = 'Order: ' + orderCode;
            document.getElementById('modal-ticket-summary').textContent = summary;
            const modal = document.getElementById('success-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeSuccessModal() {
            const modal = document.getElementById('success-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>

</body>
</html>