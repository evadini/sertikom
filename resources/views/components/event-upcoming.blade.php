@props(['events' => null])

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Up Coming Event</h2>
        <a href="#" class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors">
            Lebih Banyak Event <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
        </a>
    </div>

    <div class="relative">
        <div class="absolute left-[25px] top-2 bottom-0 w-0.5 bg-gray-200"></div>

        @if($events && $events->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach($events as $upEvent)
                    @php $eventDate = \Carbon\Carbon::parse($upEvent->date_start); @endphp

                    <div class="relative flex gap-4 items-start bg-white border border-gray-200 p-4 rounded-2xl shadow-sm hover:border-blue-300 hover:shadow-md transition-all duration-300">
                        <div class="relative z-10 flex-shrink-0 w-14 pt-1">
                            <div class="bg-blue-50 border border-blue-100 rounded-lg flex flex-col items-center py-1.5">
                                <span class="text-[9px] font-bold text-blue-600 uppercase">{{ $eventDate->translatedFormat('M') }}</span>
                                <span class="text-lg font-black text-gray-900 leading-none">{{ $eventDate->format('d') }}</span>
                            </div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-gray-900 truncate">{{ $upEvent->name }}</h4>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fa-regular fa-calendar mr-1 text-blue-500"></i>
                                {{ $eventDate->format('d M Y') }}
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5 truncate">
                                <i class="fa-solid fa-location-dot mr-1 text-red-400"></i>
                                {{ $upEvent->location ?? 'Lokasi' }}
                            </p>
                        </div>

                        <div class="w-24 h-16 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
                            <img src="{{ $upEvent->banner ? asset('images/events/' . $upEvent->banner) : asset('images/kmipn.jpeg') }}"
                                 alt="{{ $upEvent->name }}"
                                 class="w-full h-full object-cover">
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-10 border border-dashed border-gray-200 rounded-2xl bg-gray-50">
                <i class="fa-regular fa-calendar text-3xl text-gray-300 mb-3 block"></i>
                <p class="text-sm text-gray-500">Belum ada event terdekat</p>
            </div>
        @endif
    </div>
</section>
