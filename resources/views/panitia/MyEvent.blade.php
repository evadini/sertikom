<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticketify - My Events</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @include('components.fonts')
</head>
<body class="bg-[#F8FAFC] text-gray-900 flex flex-col min-h-screen">

    @include('components.panitia-nav')

    <main class="flex-1 p-6 lg:p-10 overflow-y-auto">
        <header class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
            <div>
                <h1 class="text-3xl font-black tracking-tight uppercase italic text-blue-500">My Events</h1>
                <p class="text-gray-500 text-sm mt-2 font-medium">Kelola dan pantau seluruh event kamu dalam satu daftar.</p>
            </div>

            <div class="flex gap-3 w-full md:w-auto">
                <a href="{{ route('panitia.create') }}" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 shadow-lg shadow-blue-600/20">
                    <i class="fa-solid fa-plus text-xs"></i> New Event
                </a>
            </div>
        </header>

        <div class="space-y-4">

            <div class="hidden md:grid grid-cols-12 px-8 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-gray-600 border-b border-gray-200">
                <div class="col-span-5">Event Information</div>
                <div class="col-span-2 text-center">Status</div>
                <div class="col-span-3">Ticket Sales</div>
                <div class="col-span-2 text-right">Actions</div>
            </div>

            @forelse($events as $event)
                <div class="event-row bg-white border border-gray-200 rounded-2xl p-4 md:px-8 md:py-5 transition-all duration-300 shadow-sm">
                    <div class="grid grid-cols-1 md:grid-cols-12 items-center gap-6">

                        <div class="md:col-span-5 flex items-center gap-5">
                            <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 border border-gray-200">
                                <img src="{{ $event->banner ? asset('images/events/'.$event->banner) : asset('images/kmipn.jpeg') }}" class="w-full h-full object-cover">
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-sm font-black text-gray-900 truncate uppercase tracking-tight">{{ $event->name }}</h3>
                                <p class="text-[10px] text-gray-500 mt-1 flex items-center gap-2 uppercase font-bold">
                                    <i class="fa-solid fa-calendar text-blue-500"></i> {{ $event->date_range }}
                                </p>
                            </div>
                        </div>

                        {{-- ============ STATUS BADGE ============ --}}
                        <div class="md:col-span-2 flex justify-start md:justify-center">
                            @php
                                $displayStatus = $event->getDisplayStatus();
                            @endphp

                            @if($displayStatus === 'published')
                                <div class="flex items-center gap-2 bg-emerald-500/10 border border-emerald-500/30 text-emerald-500 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                    Active
                                </div>

                            @elseif($displayStatus === 'completed')
                                <div class="flex items-center gap-2 bg-blue-500/10 border border-blue-500/30 text-blue-500 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest">
                                    <i class="fa-solid fa-circle-check"></i> Completed
                                </div>

                            @elseif($displayStatus === 'pending')
                                <div class="flex items-center gap-2 bg-amber-500/10 border border-amber-500/30 text-amber-500 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest">
                                    <i class="fa-solid fa-clock-rotate-left"></i> Pending
                                </div>

                            @elseif($displayStatus === 'rejected')
                                {{-- ✅ PERUBAHAN: Badge rejected sekarang bisa diklik untuk lihat alasan --}}
                                <button type="button"
                                    onclick="showRejectReason(
                                        '{{ addslashes($event->unpublish_reason ?? '') }}',
                                        '{{ $event->unpublished_at ? \Carbon\Carbon::parse($event->unpublished_at)->format('d M Y, H:i') : '-' }}'
                                    )"
                                    class="flex items-center gap-2 bg-red-500/10 border border-red-500/30 text-red-500 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest hover:bg-red-500/20 transition cursor-pointer">
                                    <i class="fa-solid fa-circle-xmark"></i> Rejected
                                    <i class="fa-solid fa-circle-info text-[8px]"></i>
                                </button>

                            @elseif($displayStatus === 'unpublished')
                                <div class="flex items-center gap-2 bg-gray-500/10 border border-gray-500/30 text-gray-400 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest">
                                    <i class="fa-solid fa-ban"></i> Unpublished
                                </div>
                            @else
                                <div class="flex items-center gap-2 bg-gray-500/10 border border-gray-500/30 text-gray-400 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest">
                                    {{ ucfirst($displayStatus) }}
                                </div>
                            @endif
                        </div>
                        {{-- ============ END STATUS BADGE ============ --}}

                        @php
                            $kuota = $event->tickets->sum('stock');
                            $terjual = $event->tickets_sold ?? 0;
                            $persentase = $kuota > 0 ? ($terjual / $kuota) * 100 : 0;
                        @endphp
                        <div class="md:col-span-3 space-y-2">
                            <div class="flex justify-between text-[9px] font-black uppercase tracking-widest text-gray-500">
                                <span>Sold: <b class="text-gray-900">{{ $terjual }}</b></span>
                                <span>Target: {{ $kuota ?: '-' }}</span>
                            </div>
                            <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-500 rounded-full shadow-[0_0_8px_rgba(59,130,246,0.4)]" style="width: {{ $persentase }}%"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end md:col-span-2 gap-1 flex-wrap">
                            @php
                                $isRejected = $event->status === 'rejected';
                                $hideDetailEdit = $displayStatus === 'completed' || $displayStatus === 'unpublished';
                            @endphp

                            @if(!$isRejected && !$hideDetailEdit)
                                {{-- DETAIL EVENT --}}
                                <a href="{{ route('panitia.events.show', $event->id) }}" class="flex flex-col items-center gap-1">
                                    <div class="flex items-center justify-center w-9 h-9 bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-500 rounded-xl transition-colors border border-emerald-500/10">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </div>
                                    <span class="text-[9px] font-bold uppercase text-emerald-500">Detail</span>
                                </a>

                                {{-- EDIT --}}
                                <a href="{{ route('panitia.events.edit', $event->id) }}" class="flex flex-col items-center gap-1">
                                    <div class="flex items-center justify-center w-9 h-9 bg-yellow-50 hover:bg-yellow-100 text-yellow-500 hover:text-yellow-600 rounded-xl transition-colors border border-yellow-200">
                                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                                    </div>
                                    <span class="text-[9px] font-bold uppercase text-yellow-500">Edit</span>
                                </a>
                            @endif

                            @if(!$isRejected)
                                <a href="{{ route('panitia.statistic.detail', $event->id) }}" class="flex flex-col items-center gap-1">
                                    <div class="flex items-center justify-center w-9 h-9 bg-purple-50 hover:bg-purple-100 text-purple-500 hover:text-purple-600 rounded-xl transition-colors border border-purple-200">
                                        <i class="fa-solid fa-chart-bar text-sm"></i>
                                    </div>
                                    <span class="text-[9px] font-bold uppercase text-purple-500">Stats</span>
                                </a>

                                <a href="{{ route('panitia.customerdata', $event->id) }}" class="flex flex-col items-center gap-1">
                                    <div class="flex items-center justify-center w-9 h-9 bg-blue-600/10 hover:bg-blue-600/20 text-blue-500 hover:text-blue-400 rounded-xl transition-colors border border-blue-500/10">
                                        <i class="fa-solid fa-table text-sm"></i>
                                    </div>
                                    <span class="text-[9px] font-bold uppercase text-blue-500/80">Peserta</span>
                                </a>
                            @endif
                        </div>

                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">Belum ada event. Klik "New Event" untuk menambahkan.</div>
            @endforelse

        </div>
    </main>


    {{-- ✅ TAMBAHAN: Modal Alasan Rejected --}}
    <div id="rejectReasonModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl w-full max-w-md border border-red-200 overflow-hidden shadow-2xl">

            {{-- Header --}}
            <div class="bg-red-50 border-b border-red-200 px-6 py-4 flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fa-solid fa-ban text-red-500 text-sm"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-gray-900">Event Ditolak / Unpublished</h3>
                    <p class="text-xs text-gray-500">Alasan dari Admin</p>
                </div>
                <button onclick="closeRejectModal()" class="ml-auto text-gray-400 hover:text-gray-700 transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="px-6 py-5 space-y-4">
                <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                    <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mb-2">Alasan</p>
                    <p id="rejectReasonText" class="text-sm text-gray-800 leading-relaxed">-</p>
                </div>

                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <i class="fa-regular fa-clock"></i>
                    Unpublished pada:
                    <span id="rejectReasonDate" class="text-gray-700 font-medium">-</span>
                </div>

                <p class="text-xs text-gray-600 bg-gray-50 rounded-xl p-3 border border-gray-200">
                    <i class="fa-solid fa-lightbulb text-yellow-500 mr-1"></i>
                    Silakan perbaiki event sesuai alasan di atas, lalu hubungi admin untuk penerbitan ulang.
                </p>
            </div>

            {{-- Footer --}}
            <div class="px-6 pb-5">
                <button onclick="closeRejectModal()"
                    class="w-full py-2.5 border border-gray-200 rounded-xl text-sm text-gray-700 hover:bg-gray-50 transition">
                    Tutup
                </button>
            </div>

        </div>
    </div>
    {{-- ✅ END Modal --}}



    {{-- ✅ Load JS unpublish terpisah --}}
    <script src="{{ asset('js/unpublish.js') }}"></script>

</body>
</html>
