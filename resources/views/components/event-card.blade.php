@props([
    'image', 'day', 'month', 'year', 'category',
    'title', 'location', 'startTime', 'endTime', 'price',
    'dark' => false,
])

@php
    $bg = $dark ? 'bg-[#1e1e1e]' : 'bg-white';
    $border = $dark ? 'border-white/5' : 'border-gray-200';
    $text = $dark ? 'text-white' : 'text-gray-900';
    $muted = $dark ? 'text-gray-400' : 'text-gray-500';
    $hover = $dark ? 'hover:border-blue-500/50' : 'hover:shadow-md hover:-translate-y-0.5';
    $shadow = $dark ? '' : 'shadow-sm';
@endphp

<a href="{{ $attributes->get('href', '#') }}"
   class="group block {{ $bg }} {{ $shadow }} rounded-xl {{ $hover }} transition-all duration-300 overflow-hidden border {{ $border }}">

    <div class="relative aspect-[2/1] overflow-hidden bg-gray-100">
        <img src="{{ $image }}"
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
             alt="{{ $title }}"
             loading="lazy"
             onerror="this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center bg-gray-100\'><i class=\'fa-solid fa-image text-3xl text-gray-300\'></i></div>'">
    </div>

    <div class="p-4 space-y-2">
        <h3 class="font-bold text-base {{ $text }} leading-snug line-clamp-2 group-hover:text-blue-600 transition-colors">
            {{ $title }}
        </h3>

        <div class="flex items-center gap-1.5 text-xs {{ $muted }}">
            <i class="fa-regular fa-calendar text-[10px]"></i>
            <span>{{ $day }} {{ $month }} {{ $year }} &middot; {{ $startTime }} WIB</span>
        </div>

        <div class="flex items-center gap-1.5 text-xs {{ $muted }}">
            <i class="fa-solid fa-location-dot text-[10px]"></i>
            <span class="truncate">{{ $location }}</span>
        </div>

        <div class="pt-2 border-t {{ $dark ? 'border-white/5' : 'border-gray-100' }}">
            <div class="flex items-center justify-between gap-2">
                <span class="text-sm font-semibold text-blue-600">{{ $price }}</span>
                <div class="flex items-center gap-2 flex-shrink-0">
                    {{ $slot ?? '' }}
                </div>
            </div>
        </div>
    </div>
</a>
