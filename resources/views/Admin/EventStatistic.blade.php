@extends('layouts.admin')

@section('title', 'Statistik Event')

@section('content')

<div class="px-8 mt-6">

    @php $statuses = [['value' => 'all', 'label' => 'All', 'icon' => 'fa-chart-simple'], ['value' => 'published', 'label' => 'Active', 'icon' => 'fa-play'], ['value' => 'pending', 'label' => 'Pending', 'icon' => 'fa-clock'], ['value' => 'rejected', 'label' => 'Rejected', 'icon' => 'fa-ban']]; @endphp

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 mb-8 flex flex-col md:flex-row gap-4 items-center">
        <form method="GET" action="{{ route('admin.event-statistics') }}" class="w-full flex flex-col md:flex-row gap-4 items-center">
            <div class="relative flex-1 w-full">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" name="search" placeholder="Cari event atau lokasi..." value="{{ $search ?? '' }}"
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-xs focus:border-blue-500 outline-none transition-all text-gray-900">
                <input type="hidden" name="status" value="{{ $activeFilter ?? 'all' }}">
            </div>
            <div class="flex gap-2 flex-wrap">
                @foreach($statuses as $s)
                    <button type="submit" name="status" value="{{ $s['value'] }}"
                        class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 {{ ($activeFilter ?? 'all') === $s['value'] ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                        <i class="fa-solid {{ $s['icon'] }}"></i>
                        {{ $s['label'] }}
                    </button>
                @endforeach
            </div>
            @if(($search ?? '') || ($activeFilter ?? 'all') !== 'all')
                <a href="{{ route('admin.event-statistics') }}" class="text-[10px] text-gray-500 hover:text-blue-600 font-black uppercase tracking-widest transition-all">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </a>
            @endif
        </form>
    </div>

    <div class="space-y-4">
        @forelse($events as $item)
            @php
                $kuota = $item->tickets->sum('stock');
                $terjual = $item->tickets_sold ?? 0;
                $persentase = $kuota > 0 ? ($terjual / $kuota) * 100 : 0;
                $displayStatus = $item->getDisplayStatus();
                $statusConfig = [
                    'published' => ['bg' => 'bg-emerald-50 text-emerald-600 border-emerald-200', 'label' => 'Active'],
                    'completed' => ['bg' => 'bg-blue-50 text-blue-600 border-blue-200', 'label' => 'Completed'],
                    'pending' => ['bg' => 'bg-amber-50 text-amber-600 border-amber-200', 'label' => 'Pending'],
                    'rejected' => ['bg' => 'bg-red-50 text-red-600 border-red-200', 'label' => 'Rejected'],
                ];
                $config = $statusConfig[$displayStatus] ?? $statusConfig['published'];
            @endphp

            <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-5 transition-all duration-300 hover:shadow-md">
                <div class="flex flex-wrap md:flex-nowrap items-center gap-6">
                    <div class="w-full md:w-36 h-20 rounded-xl overflow-hidden border border-gray-200 flex-shrink-0">
                        @if($item->banner)
                            <img src="{{ asset('images/events/' . $item->banner) }}" class="w-full h-full object-cover" alt="Poster">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center">
                                <i class="fa-solid fa-chart-pie text-2xl text-white/50"></i>
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="inline-block px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-tight border {{ $config['bg'] }}">{{ $config['label'] }}</span>
                        </div>
                        <h3 class="text-base font-black tracking-tight text-gray-900 truncate">{{ $item->name }}</h3>
                        <p class="text-xs text-gray-500 mt-0.5">
                            <i class="fa-solid fa-calendar mr-1.5"></i>{{ $item->date_range }}, {{ substr($item->time_start, 0, 5) }} - {{ substr($item->time_end, 0, 5) }} WIB
                        </p>
                    </div>

                    <div class="flex-1 max-w-xs">
                        <div class="flex justify-between items-center mb-1.5">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Penjualan</span>
                            <span class="text-xs font-bold text-gray-900">{{ $terjual }} / {{ $kuota }}</span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-500 rounded-full transition-all duration-500" style="width: {{ $persentase }}%"></div>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.event-statistics.detail', $item->id) }}"
                           class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-xl text-[10px] uppercase transition-all shadow-sm inline-block text-center">
                            <i class="fa-solid fa-chart-simple mr-1"></i> Statistik
                        </a>
                        <a href="{{ route('admin.event-statistics.attendees', $item->id) }}"
                           class="px-5 py-2.5 bg-gray-100 hover:bg-purple-600 hover:text-white text-gray-700 font-black rounded-xl text-[10px] uppercase transition-all inline-block text-center">
                            <i class="fa-solid fa-users mr-1"></i> Peserta
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-16 text-center bg-white border border-gray-200 shadow-sm rounded-2xl">
                <i class="fa-solid fa-chart-pie text-5xl text-gray-200 mb-4"></i>
                <p class="text-gray-500 font-semibold mb-2">Belum ada event</p>
                <p class="text-gray-400 text-sm">Event yang dipublikasikan akan muncul di sini beserta statistiknya.</p>
            </div>
        @endforelse
    </div>
</div>

@endsection
