<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ticketify — Temukan Event Seru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    @include('components.fonts')
</head>
<body class="bg-[#F8FAFC] text-gray-900 antialiased">

    {{-- NAVBAR --}}
    <nav class="sticky top-0 z-50 bg-white border-b border-gray-100 shadow-sm" x-data="{ searchOpen: false, catOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">

                {{-- Logo --}}
                <a href="{{ route('guest.event') }}" class="flex items-center gap-2 flex-shrink-0">
                    <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center font-extrabold text-white text-sm">T</div>
                    <span class="font-extrabold text-lg tracking-tight text-gray-900">Ticketify</span>
                </a>

                {{-- Kategori --}}
                <div class="relative hidden lg:block">
                    <button @click="catOpen = !catOpen" @click.outside="catOpen = false"
                            class="flex items-center gap-1.5 text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors px-3 py-2 rounded-lg hover:bg-gray-50">
                        <i class="fa-regular fa-rectangle-list"></i>
                        <span>Categories</span>
                        <i class="fa-solid fa-chevron-down text-[10px] transition-transform" :class="catOpen && 'rotate-180'"></i>
                    </button>
                    <div x-show="catOpen" x-cloak
                         class="absolute top-full left-0 mt-1 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50">
                        <a href="{{ route('guest.event') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium {{ !request('category_id') ? 'text-blue-600 bg-blue-50' : '' }}">Semua Event</a>
                        @foreach($categories as $category)
                            <a href="{{ route('guest.event', ['category_id' => $category->id]) }}"
                               class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 {{ request('category_id') == $category->id ? 'text-blue-600 bg-blue-50' : '' }}">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Search --}}
                <form action="{{ route('guest.event') }}" method="GET" class="hidden md:flex flex-1 max-w-lg relative">
                    <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search events, categories..."
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-700 placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </form>

                {{-- Mobile search toggle --}}
                <button @click="searchOpen = !searchOpen" class="md:hidden text-gray-500 hover:text-blue-600">
                    <i class="fa-solid fa-search text-lg"></i>
                </button>

                {{-- Auth --}}
                <div class="flex items-center gap-2 flex-shrink-0">
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex px-4 py-2 text-sm font-semibold text-gray-700 hover:text-blue-600 transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm font-semibold bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-all duration-200 shadow-sm shadow-blue-600/20 active:scale-[0.97] whitespace-nowrap">
                        Register
                    </a>
                </div>
            </div>

            {{-- Mobile search --}}
            <div x-show="searchOpen" x-cloak class="md:hidden pb-3">
                <form action="{{ route('guest.event') }}" method="GET" class="relative">
                    <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search events, categories..."
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </form>
            </div>
        </div>
    </nav>

    {{-- HERO CAROUSEL FULL WIDTH --}}
    <div class="w-full">
        @include('components.event-carousel')
    </div>

    {{-- EVENT GRID --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Discover your next experience.</h2>
            @if(request('search') || request('category_id'))
                <a href="{{ route('guest.event') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Semua Event
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @forelse($publicEvents as $event)
                @php
                    $minPrice = $event->tickets->whereNull('order_id')->min('price');
                    $priceLabel = $minPrice ? 'Rp ' . number_format($minPrice, 0, ',', '.') : 'Gratis';
                @endphp
                <x-event-card
                    href="{{ route('guest.event.detail', $event->id) }}"
                    :image="asset('images/events/' . $event->banner)"
                    :day="\Carbon\Carbon::parse($event->date_start)->format('d')"
                    :month="\Carbon\Carbon::parse($event->date_start)->translatedFormat('M')"
                    :year="\Carbon\Carbon::parse($event->date_start)->format('Y')"
                    :title="$event->name"
                    :location="$event->location"
                    :startTime="\Carbon\Carbon::parse($event->time_start)->format('H:i')"
                    :endTime="\Carbon\Carbon::parse($event->time_end)->format('H:i')"
                    :price="$priceLabel"
                />
            @empty
                <div class="col-span-full text-center py-16">
                    <i class="fa-regular fa-calendar-xmark text-4xl text-gray-300 mb-3 block"></i>
                    <p class="text-gray-500 text-sm font-medium">
                        @if(request('search') || request('category_id'))
                            Tidak ada event yang cocok dengan pencarianmu.
                        @else
                            Belum ada event yang dipublikasikan.
                        @endif
                    </p>
                </div>
            @endforelse
        </div>
    </section>

    @include('components.event-upcoming', ['events' => $upcomingEvents])

    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        new Swiper('.myHeroSwiper', {
            loop: true,
            autoplay: { delay: 5000, disableOnInteraction: false },
            pagination: { el: '.swiper-pagination', clickable: true },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            effect: 'fade',
            fadeEffect: { crossFade: true },
        });
    </script>
</body>
</html>
