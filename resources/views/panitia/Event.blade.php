<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticketify — Discover Events</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    @include('components.fonts')
</head>
<body class="bg-[#F8FAFC] text-gray-900 antialiased">

    <div class="min-h-screen">
        @include('components.panitia-nav')

        <div class="flex flex-col">

            <div class="w-full">
                @include('components.event-carousel')
            </div>

            <div class="px-4 sm:px-6 lg:px-8 mt-6">
                <x-event-filter :categories="$categories" />
            </div>

<section class="px-4 sm:px-6 lg:px-8 py-8">
    

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @forelse($publicEvents as $event)
            @php
                $minPrice = $event->tickets->whereNull('order_id')->min('price');
                $priceLabel = $minPrice ? 'Rp ' . number_format($minPrice, 0, ',', '.') : 'Gratis';
            @endphp
            <x-event-card
                href="{{ route('panitia.events.show', $event->id) }}"
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
                <p class="text-gray-500 text-sm">Belum ada event yang dipublikasikan.</p>
            </div>
        @endforelse
    </div>
</section>

            @include('components.event-upcoming', ['events' => $upcomingEvents])

            @include('components.footer')
        </div>
    </div>

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
