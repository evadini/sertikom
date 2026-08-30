<header class="p-8">
    <div class="swiper myHeroSwiper rounded-3xl overflow-hidden shadow-2xl">
        <div class="swiper-wrapper">
            @forelse($events->take(5) as $carouselEvent)
                @php
                    // Determine the correct route based on user authentication
                    $detailRoute = auth()->check() && auth()->user()->hasRole('pembeli')
                        ? route('pembeli.detail', $carouselEvent)
                        : route('guest.event.detail', $carouselEvent->id);
                @endphp
                <div class="swiper-slide relative h-[350px] group cursor-pointer" onclick="window.location='{{ $detailRoute }}'">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent z-10"></div>

                    <img src="{{ $carouselEvent->banner ? asset('images/events/' . $carouselEvent->banner) : '' }}"
                    class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:scale-105 transition-transform duration-700"
                    alt="{{ $carouselEvent->name }}">

                    <div class="absolute inset-0 flex flex-col items-start justify-end p-8 z-20">
                        <h2 class="text-4xl font-extrabold mb-2 leading-tight italic tracking-tighter text-white uppercase drop-shadow-lg">
                            {{ $carouselEvent->name }}
                        </h2>
                        <p class="text-blue-100 max-w-lg mb-6 text-sm line-clamp-2 opacity-90">
                            {{ $carouselEvent->description ?? 'Tidak ada deskripsi untuk event ini.' }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="swiper-slide relative h-[350px] bg-[#1e1e1e] flex flex-col items-center justify-center p-8">
                    <i class="fa-solid fa-calendar-xmark text-4xl text-gray-600 mb-3"></i>
                    <p class="text-gray-400 text-sm font-medium">Belum ada event unggulan yang tersedia.</p>
                </div>
            @endforelse
        </div>
        <div class="swiper-pagination"></div>
    </div>
</header>

<form action="{{ url()->current() }}" method="GET" class="px-8 -mt-10 relative z-30">
    <div class="bg-[#1e1e1e] border border-white/10 rounded-2xl p-4 shadow-2xl flex flex-wrap lg:flex-nowrap items-end gap-4">

        <div class="flex-[2] min-w-[200px]">
            <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2 block ml-1">Search Event Name</label>
            <div class="flex items-center gap-3 bg-white/5 rounded-xl px-4 py-3 border border-transparent focus-within:border-blue-500 transition">
                <i class="fa-solid fa-magnifying-glass text-blue-500"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Seminar, workshop, konser..." class="bg-transparent w-full outline-none text-sm text-gray-200">
            </div>
        </div>

        <div class="flex-1 min-w-[150px]">
            <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2 block ml-1">Category</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-tags text-blue-500 text-[12px]"></i>
                </div>

                <select name="category" class="w-full bg-white/5 border border-transparent rounded-xl py-3 pl-10 pr-8 text-xs font-bold text-gray-300 focus:border-blue-500 outline-none transition-all appearance-none cursor-pointer">
                    <option value="" class="bg-[#1e1e1e]">Semua</option>
                    <option value="Music Concert" class="bg-[#1e1e1e]" {{ request('category') == 'Music Concert' ? 'selected' : '' }}>Music Concert</option>
                    <option value="Seminar" class="bg-[#1e1e1e]" {{ request('category') == 'Seminar' ? 'selected' : '' }}>Seminar</option>
                    <option value="Workshop" class="bg-[#1e1e1e]" {{ request('category') == 'Workshop' ? 'selected' : '' }}>Workshop</option>
                    <option value="Festival" class="bg-[#1e1e1e]" {{ request('category') == 'Festival' ? 'selected' : '' }}>Festival</option>
                    <option value="Sport" class="bg-[#1e1e1e]" {{ request('category') == 'Sport' ? 'selected' : '' }}>Sport</option>
                    <option value="Competition" class="bg-[#1e1e1e]" {{ request('category') == 'Competition' ? 'selected' : '' }}>Competition</option>
                    <option value="Exhibition" class="bg-[#1e1e1e]" {{ request('category') == 'Exhibition' ? 'selected' : '' }}>Exhibition</option>
                    <option value="Community" class="bg-[#1e1e1e]" {{ request('category') == 'Community' ? 'selected' : '' }}>Community</option>
                    <option value="Education" class="bg-[#1e1e1e]" {{ request('category') == 'Education' ? 'selected' : '' }}>Education</option>
                    <option value="Entertainment" class="bg-[#1e1e1e]" {{ request('category') == 'Entertainment' ? 'selected' : '' }}>Entertainment</option>
                </select>

                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-500">
                    <i class="fa-solid fa-chevron-down text-[10px]"></i>
                </div>
            </div>
        </div>

        <div class="flex-1 min-w-[150px]">
            <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2 block ml-1">Select Date</label>

            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-calendar-day text-blue-500 text-[12px]"></i>
                </div>

                <input type="date"
                       name="date"
                       value="{{ request('date') }}"
                       class="w-full bg-white/5 border border-transparent rounded-xl py-3 pl-10 pr-4 text-xs font-bold text-gray-300 focus:border-blue-500 outline-none transition-all cursor-pointer [color-scheme:dark]">
            </div>
        </div>

        <button type="submit"
                class="w-full lg:w-auto px-8 py-3.5 bg-white text-black rounded-xl font-bold hover:bg-blue-500 hover:text-white transition-all active:scale-95 shadow-lg">
            Search Events
        </button>
    </div>
</form>

<main class="p-8">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-black italic tracking-tighter">Popular Event</h2>

        <a href="{{ url()->current() }}"
           class="text-sm text-blue-400 hover:underline">
            See All
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        @if(!empty($events) && (is_array($events) || is_object($events)) && count($events) > 0)

            @foreach($events as $event)

                <x-event-card
                    image="{{ $event->banner ? asset('images/events/' . $event->banner) : asset('images/kmipn.jpeg') }}"
                    day="{{ \Illuminate\Support\Carbon::parse($event->date_start)->format('d') }}"
                    month="{{ \Illuminate\Support\Carbon::parse($event->date_start)->format('M') }}"
                    year="{{ \Illuminate\Support\Carbon::parse($event->date_start)->format('Y') }}"
                    category="{{ $event->category?->name }}"
                    title="{{ $event->name }}"
                    location="{{ $event->location }}"
                    startTime="{{ \Illuminate\Support\Carbon::parse($event->time_start)->format('H:i') }}"
                    endTime="{{ isset($event->time_end) ? \Illuminate\Support\Carbon::parse($event->time_end)->format('H:i') : \Illuminate\Support\Carbon::parse($event->time_start)->addHour()->format('H:i') }}"
                    price="IDR {{ $event->tickets->whereNull('order_id')->min('price') ? number_format($event->tickets->whereNull('order_id')->min('price'), 0, ',', '.') : 'Gratis' }}"
                >
                    @php
                        $viewRoute = auth()->check() && auth()->user()->hasRole('pembeli')
                            ? route('panitia.events.show', $event)
                            : route('panitia.events.show', $event->id);
                    @endphp
                    <a href="{{ $viewRoute }}"
                       class="rounded-full border border-white/10 px-3 py-1 text-[11px] uppercase tracking-[0.2em] font-bold hover:bg-white/5 transition">
                        View
                    </a>
                </x-event-card>

            @endforeach

        @else

            <div class="col-span-2 text-center py-12 bg-white/5 border border-dashed border-white/10 rounded-2xl">
                <i class="fa-solid fa-magnifying-glass text-3xl text-gray-600 mb-2"></i>
                <p class="text-gray-400 text-sm">
                    Belum ada event yang cocok dengan kriteria pencarian Anda.
                </p>
            </div>

        @endif

    </div>
</main>

<footer class="mt-auto bg-black/20 border-t border-white/5 p-8 text-center">
    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.3em]">
        &copy; 2026 Informatics Engineering - Polibatam
    </p>
</footer>
