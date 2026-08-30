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
</head>

<body class="bg-[#F8FAFC] text-gray-900 min-h-screen p-6 md:p-10" x-data="{ tab: 'ticket' }">
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
                <p class="text-xs text-gray-500 mt-1">{{ $event->category?->name ?? 'Umum' }}</p>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 px-4 py-2 rounded-lg">
            <span class="text-blue-700 text-xs font-bold uppercase tracking-wider">
                <i class="fa-solid fa-check-circle mr-1"></i> Tersedia
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

            {{-- Form Pembelian Tiket --}}
            <form action="#" method="POST" class="space-y-4 max-w-4xl mx-auto" x-data="{ tickets: {} }">
                @csrf
                <h3 class="font-bold text-lg text-gray-900 mb-4">Pilih Tipe Tiket</h3>
                @forelse ($event->tickets->whereNull('order_id') as $index => $ticket)
                    <div x-init="tickets[{{ $index }}] = 0"
                         class="bg-gray-50 p-6 rounded-2xl border border-gray-100 flex items-center justify-between hover:border-blue-300 transition-all">
                        <div>
                            <h3 class="font-bold text-gray-900"><i class="fa-solid fa-ticket text-blue-600 mr-2"></i> {{ $ticket->ticket_type }}</h3>
                            <span class="text-[10px] text-gray-500">Stock: {{ $ticket->stock }}</span>
                        </div>
                        <div class="flex items-center gap-6">
                            <span class="font-black text-blue-600">IDR {{ number_format($ticket->price, 0, ',', '.') }}</span>

                            {{-- Counter Plus/Minus --}}
                            <div class="flex items-center bg-white rounded-lg border border-gray-200">
                                <button type="button" @click="if(tickets[{{ $index }}] > 0) tickets[{{ $index }}]--"
                                        class="px-3 py-1 text-gray-600 hover:text-blue-600 font-bold">-</button>
                                <span class="px-3 font-bold text-sm text-gray-900" x-text="tickets[{{ $index }}]">0</span>
                                <input type="hidden" name="tickets[{{ $index }}][qty]" :value="tickets[{{ $index }}]">
                                <input type="hidden" name="tickets[{{ $index }}][name]" value="{{ $ticket->ticket_type }}">
                                <button type="button" @click="if(tickets[{{ $index }}] < {{ $ticket->stock }}) tickets[{{ $index }}]++"
                                        class="px-3 py-1 text-gray-600 hover:text-blue-600 font-bold">+</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 py-6">Tidak ada tipe tiket yang tersedia.</p>
                @endforelse

                <div class="pt-6 border-t border-gray-100 flex justify-center">
                    <button type="button"
                            onclick="window.location.href='{{ route('register') }}'"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold transition flex items-center gap-2 shadow-lg shadow-blue-600/10">
                        <i class="fa-solid fa-cart-shopping"></i> Beli Tiket
                    </button>
                </div>
            </form>
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
</body>
</html>