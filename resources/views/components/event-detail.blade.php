<div class="max-w-6xl mx-auto" x-data="{ tab: 'ticket' }">

    {{-- HEADER --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('panitia.event') }}" class="bg-[#18181b] hover:bg-white hover:text-black transition-all px-4 py-2 rounded-lg text-xs font-bold border border-white/5 flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Back
        </a>

        <h1 class="text-2xl font-black tracking-tight italic uppercase">
            {{ $event->title ?? 'Event Detail' }}
        </h1>
    </div>

    {{-- TAB --}}
    <div class="flex justify-center mb-8 border-b border-white/5">
        <div class="flex gap-8">
            <button @click="tab = 'ticket'"
                    :class="tab === 'ticket' ? 'border-b-2 border-blue-500 text-blue-500' : 'text-gray-500'"
                    class="pb-4 px-4 font-bold text-sm transition-all flex items-center gap-2">
                <i class="fa-solid fa-ticket"></i>
                Ticket
            </button>

            <button @click="tab = 'details'"
                    :class="tab === 'details' ? 'border-b-2 border-blue-500 text-blue-500' : 'text-gray-500'"
                    class="pb-4 px-4 font-bold text-sm transition-all flex items-center gap-2">
                <i class="fa-solid fa-circle-info"></i>
                Details
            </button>

            <button @click="tab = 'organiser'"
                    :class="tab === 'organiser' ? 'border-b-2 border-blue-500 text-blue-500' : 'text-gray-500'"
                    class="pb-4 px-4 font-bold text-sm transition-all flex items-center gap-2">
                <i class="fa-solid fa-users"></i>
                Organiser
            </button>
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="bg-[#121212] rounded-3xl p-8 border border-white/5 shadow-2xl">

        {{-- TAB TICKET --}}
        <div x-show="tab === 'ticket'" x-transition>
            <div class="grid md:grid-cols-2 gap-10 mb-10 pb-10 border-b border-white/5">

                {{-- IMAGE --}}
                <div class="rounded-2xl overflow-hidden border border-white/10 shadow-lg h-[400px]">
                    <img src="{{ $event->banner ? asset('storage/' . $event->banner) : asset('images/default-event.jpg') }}"
                         alt="{{ $event->title }}"
                         class="w-full h-full object-cover">
                </div>

                {{-- INFO --}}
                <div class="space-y-6">
                    <div class="bg-[#18181b] p-4 rounded-xl border border-white/5 flex items-center gap-4">
                        <i class="fa-solid fa-qrcode text-blue-500"></i>
                        <span class="text-sm font-bold">Our Social Media Event</span>
                    </div>

                    {{-- LOCATION --}}
                    <div class="bg-[#18181b] p-5 rounded-2xl border border-white/5">
                        <h4 class="text-blue-500 font-bold text-sm mb-2">
                            <i class="fa-solid fa-location-dot text-red-500 mr-2"></i>
                            Location
                        </h4>
                        <p class="text-xs text-gray-300 font-bold leading-relaxed">
                            {{ $event->location ?? 'Location not available' }}
                        </p>
                    </div>

                    {{-- DATE --}}
                    <div class="text-sm font-bold text-gray-400">
                        <i class="fa-solid fa-calendar-days text-blue-500 mr-2"></i>
                        @if($event->date_start)
                            {{ $event->date_range }}, {{ substr($event->time_start, 0, 5) }} - {{ substr($event->time_end, 0, 5) }} WIB
                        @else
                            Date not available
                        @endif
                    </div>

                    {{-- CATEGORY --}}
                    @if(isset($event->category))
                        <div class="flex flex-wrap gap-2">
                            <span class="px-4 py-2 bg-[#18181b] rounded-full text-[10px] border border-white/5 font-bold uppercase text-blue-400">
                               {{ $event->category?->name }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- TICKET INFORMATION --}}
            <div class="text-center mb-8">
                <h2 class="text-lg font-bold">Ticket Information</h2>
                <span class="inline-block mt-2 px-4 py-1 bg-[#18181b] rounded-full text-[10px] border border-white/5 font-bold">
                    <i class="fa-solid fa-calendar-check mr-2"></i>
                    @if($event->date_start)
                        Event Date : {{ $event->date_range }}, {{ substr($event->time_start, 0, 5) }} - {{ substr($event->time_end, 0, 5) }} WIB
                    @else
                        Date not available
                    @endif
                </span>
            </div>

            {{-- TICKET LIST --}}
            <div class="space-y-4 max-w-4xl mx-auto">
                @if($event->tickets && $event->tickets->count())
                    @foreach($event->tickets as $ticket)
                        <div class="bg-[#18181b] p-6 rounded-2xl border border-white/5 flex items-center justify-between flex-col md:flex-row gap-6">
                            <div>
                                <h3 class="font-bold text-white">
                                    <i class="fa-solid fa-ticket text-blue-500 mr-2"></i>
                                    {{ $ticket->name }}
                                </h3>
                                <p class="text-[10px] text-gray-500 ml-6 mt-1">
                                    {{ $ticket->description ?? 'No description available.' }}
                                </p>
                            </div>

                            <div class="flex items-center gap-6">
                                <div class="text-right">
                                    <span class="text-[10px] text-gray-600 block">
                                        Stok: {{ $ticket->stock ?? 0 }}
                                    </span>
                                    <span class="font-black text-blue-400">
                                        IDR {{ number_format($ticket->price ?? 0, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-10 text-gray-500 bg-[#18181b] rounded-2xl border border-white/5">
                        <i class="fa-solid fa-ticket text-3xl mb-4"></i>
                        <p class="font-bold">No tickets available yet.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- TAB DETAILS --}}
        <div x-show="tab === 'details'" x-transition style="display: none;">
            <div class="bg-[#18181b] p-8 rounded-3xl border border-white/5">
                <h2 class="text-xl font-bold mb-6 text-blue-500">Event Description</h2>
                <p class="text-sm text-gray-300 leading-relaxed text-justify whitespace-pre-line">
                    {{ $event->description ?? 'No description available.' }}
                </p>
            </div>
        </div>

        {{-- TAB ORGANISER --}}
        <div x-show="tab === 'organiser'" x-transition style="display: none;">
            <div class="bg-[#18181b] p-10 rounded-3xl border border-white/5 text-center">
                <i class="fa-solid fa-users text-blue-500 text-4xl mb-6"></i>
                <h3 class="font-bold text-2xl mb-4 uppercase tracking-wide">
                    {{ $event->organizer->name ?? 'Organizer Not Found' }}
                </h3>
                <p class="text-sm text-gray-400 leading-relaxed max-w-2xl mx-auto">
                    {{ $event->organizer->description ?? 'No organizer description available.' }}
                </p>
            </div>
        </div>

    </div>
</div>
