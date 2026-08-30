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

<body class="bg-[#F8FAFC] text-gray-900 min-h-screen" x-data="{ tab: 'ticket' }">

@include('components.panitia-nav')

<div class="max-w-6xl mx-auto space-y-8 p-6 md:p-10">

    {{-- ===== HEADER ===== --}}
    <header class="flex items-center justify-between flex-wrap">
        <div class="flex items-center gap-4">
            <button onclick="window.history.back()"
                class="bg-gray-100 hover:bg-gray-200 hover:text-black transition-all px-4 py-2 rounded-lg text-xs font-bold border border-gray-200 flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-arrow-left"></i> Back
            </button>
            <div>
                <h1 class="text-2xl font-black uppercase truncate">{{ $event->name }}</h1>
                <p class="text-xs text-gray-500 mt-1">{{ $event->category?->name }}</p>
            </div>
        </div>

        
    </header>

    {{-- ===== TAB NAVIGATION ===== --}}
    <nav class="border-b border-gray-200 flex justify-center overflow-x-auto">
        <div class="flex gap-2 flex-nowrap">
            @php
                $tabs = [
                    'ticket'    => ['icon' => 'fa-ticket',       'label' => 'Ticket'],
                    'details'   => ['icon' => 'fa-circle-info', 'label' => 'Details'],
                    'organiser' => ['icon' => 'fa-users',       'label' => 'Organiser'],
                ];
            @endphp

            @foreach ($tabs as $key => $item)
                <button @click="tab = '{{ $key }}'"
                        :class="tab === '{{ $key }}'
                            ? 'border-b-2 border-blue-500 text-blue-500'
                            : 'text-gray-500 hover:text-gray-700'"
                        class="pb-4 px-4 font-bold text-sm transition-all flex items-center gap-2">
                    <i class="fa-solid {{ $item['icon'] }}"></i>
                    {{ $item['label'] }}
                </button>
            @endforeach
        </div>
    </nav>

    {{-- ===== TAB CONTENT ===== --}}
    <main class="bg-white rounded-3xl p-8 border border-gray-200 shadow-2xl">

        {{-- TAB: TICKET --}}
        <div x-show="tab === 'ticket'" x-transition>
            <div class="grid md:grid-cols-2 gap-10 mb-10 pb-10 border-b border-gray-200">
                <div class="rounded-2xl overflow-hidden border border-gray-200 shadow-lg aspect-video">
                    <img src="{{ $event->banner ? asset('images/events/' . $event->banner) : asset('images/kmipn.jpeg') }}"
                         alt="Banner {{ $event->name }}"
                         class="w-full h-full object-cover">
                </div>

                <div class="space-y-4">
                    <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200">
                        <h4 class="text-blue-600 font-bold text-sm mb-2">
                            <i class="fa-solid fa-calendar-days mr-2"></i>Tanggal Event
                        </h4>
                        <p class="text-xs text-gray-700 font-bold">{{ $event->date_range }}, {{ substr($event->time_start, 0, 5) }} - {{ substr($event->time_end, 0, 5) }} WIB</p>
                    </div>

                    <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200">
                        <h4 class="text-blue-600 font-bold text-sm mb-2">
                            <i class="fa-solid fa-location-dot text-red-500 mr-2"></i>Location
                        </h4>
                        <p class="text-xs text-gray-700 font-bold leading-relaxed">{{ $event->location }}</p>
                    </div>


                </div>
            </div>

            <div class="space-y-4 max-w-4xl mx-auto">
                @forelse ($event->tickets->whereNull('order_id') as $ticket)
                    <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200 flex items-center justify-between">
                        <h3 class="font-bold text-gray-900">
                            <i class="fa-solid fa-ticket text-blue-600 mr-2"></i>
                            {{ $ticket->ticket_type }}
                        </h3>
                        <div class="text-right">
                            <span class="text-[10px] text-gray-600 block">Stock: {{ $ticket->stock }}</span>
                            <span class="font-black text-blue-600">
                                IDR {{ number_format($ticket->price, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-6">Tidak ada tipe tiket yang tersedia.</p>
                @endforelse
            </div>
        </div>

        {{-- TAB: DETAILS --}}
        <div x-show="tab === 'details'" x-transition style="display:none">
            <div class="space-y-6">
                <div class="bg-gray-50 p-8 rounded-3xl border border-gray-200">
                    <h3 class="text-center font-bold text-blue-600 mb-4 text-sm">
                        <i class="fa-solid fa-align-left mr-2"></i>Description
                    </h3>
                    <p class="text-sm text-gray-700 leading-relaxed text-justify">
                        {!! nl2br(e($event->description)) !!}
                    </p>
                </div>

                <div class="bg-gray-50 p-8 rounded-3xl border border-gray-200">
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
                <div class="bg-gray-50 p-10 rounded-3xl border border-gray-200 text-center">
                    <i class="fa-solid fa-users text-blue-600 text-3xl mb-6"></i>
                    <p class="max-w-2xl mx-auto text-sm text-gray-600 leading-relaxed italic">
                        {{ $event->organiser_description ?? 'Belum ada deskripsi organiser.' }}
                    </p>
                </div>

                <div class="bg-gray-50 p-8 rounded-3xl border border-gray-200 text-center">
                    <h3 class="font-bold mb-6 text-gray-900 uppercase tracking-widest text-sm">
                        Organizing Committee
                    </h3>
                    <img src="{{ $event->organiser_photo ? asset('images/organizers/' . $event->organiser_photo) : asset('images/panitia_kmipn.jpeg') }}"
                         alt="Organizing Committee"
                         class="w-full rounded-2xl border border-gray-200">
                </div>
            </div>
        </div>

    </main>

</div>
</body>
</html>
