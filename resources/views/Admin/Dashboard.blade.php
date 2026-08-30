@extends('layouts.admin')

@section('content')

<style>
    .card-hover {
        transition: all .3s ease;
    }
    .card-hover:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 25px rgba(59,130,246,.15);
    }
    .list-item:hover {
        background: rgba(0,0,0,0.02);
    }
</style>

<div class="p-8 space-y-8">

    <header class="mb-2">
        <h1 class="text-3xl font-black tracking-tight">Dashboard</h1>
        <p class="text-gray-500 text-sm mt-1">Overview of your platform</p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white border border-gray-200 rounded-2xl p-6 card-hover shadow-sm">
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-500">Published Events</p>
                <i class="fa-solid fa-arrow-trend-up text-blue-500"></i>
            </div>
            <h3 class="text-4xl font-black text-gray-900 mt-4">{{ $publishedEventsCount }}</h3>
            <p class="text-xs text-gray-500 mt-2">Updated this month</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6 card-hover shadow-sm">
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-500">Pending Events</p>
                <i class="fa-solid fa-hourglass-half text-yellow-500"></i>
            </div>
            <h3 class="text-4xl font-black text-gray-900 mt-4">{{ $pendingEventsCount }}</h3>
            <p class="text-xs text-gray-500 mt-2">Waiting approval</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6 card-hover shadow-sm">
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-500">Users</p>
                <i class="fa-solid fa-user-group text-purple-500"></i>
            </div>
            <h3 class="text-4xl font-black text-gray-900 mt-4">{{ $usersCount }}</h3>
            <p class="text-xs text-gray-500 mt-2">Active users</p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        <div class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-black text-lg text-gray-900">Events by Category</h3>
                <i class="fa-solid fa-chart-column text-blue-500"></i>
            </div>

            @php
                $maxCount = max($categoryCounts ?: [0]);
                if ($maxCount <= 0) $maxCount = 1;
                $colors = ['bg-blue-500', 'bg-purple-500', 'bg-yellow-500', 'bg-gray-400', 'bg-green-500'];
            @endphp

            <div class="flex items-end gap-8 h-56">
                @forelse($categoryCounts as $index => $count)
                    @php $height = ($count / $maxCount) * 200; @endphp
                    <div class="flex flex-col items-center flex-1 min-w-0">
                        <span class="text-xs text-gray-700 font-semibold mb-1">{{ $count }}</span>
                        <div class="w-full rounded-t-xl {{ $colors[$index % count($colors)] }} transition-all duration-300"
                             style="height: {{ $height }}px">
                        </div>
                        <span class="mt-2 text-[10px] text-gray-500 truncate w-full text-center">{{ $categories[$index] ?? 'N/A' }}</span>
                    </div>
                @empty
                    <div class="w-full text-center text-gray-400 py-10">No category data available</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-8 flex flex-col items-center shadow-sm">
            <div class="flex justify-between w-full items-center mb-6">
                <h3 class="font-black text-lg text-gray-900">Event Ratio</h3>
                <i class="fa-solid fa-chart-pie text-blue-500"></i>
            </div>

            <div class="w-44 h-44 rounded-full" style="background: conic-gradient(#3b82f6 {{ $publishedPercentage }}%,#e5e7eb 0);">
            </div>

            <div class="flex gap-5 mt-6 text-xs">
                <span><i class="fa-solid fa-circle text-blue-500 mr-1"></i>Published</span>
                <span class="text-gray-500"><i class="fa-solid fa-circle text-gray-300 mr-1"></i>Pending</span>
            </div>

            <p class="text-xs text-gray-500 mt-4">Total Events: {{ $totalEvents }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        <div class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-black text-lg text-gray-900">Recent Pending Events</h3>
                <i class="fa-solid fa-clock text-yellow-500"></i>
            </div>

            <div class="space-y-1">
                @forelse($upcomingPendingEvents as $e)
                    <div class="flex items-center gap-4 py-3 px-4 rounded-xl list-item transition">
                        <div class="w-2 h-2 rounded-full bg-yellow-500 flex-shrink-0"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $e->name }}</p>
                            <p class="text-[10px] text-gray-500">{{ $e->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <span class="text-[9px] font-black px-2 py-0.5 rounded bg-yellow-50 text-yellow-600 uppercase">Pending</span>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm py-4 text-center">No pending events</p>
                @endforelse
            </div>

            <a href="{{ route('admin.PendingEvent') }}" class="block text-center text-[10px] font-bold text-blue-600 mt-4 hover:text-blue-700 transition">
                View All Pending Events →
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-black text-lg text-gray-900">Recent Published Events</h3>
                <i class="fa-solid fa-check-circle text-green-500"></i>
            </div>

            <div class="space-y-1">
                @forelse($recentPublishedEvents as $e)
                    <div class="flex items-center gap-4 py-3 px-4 rounded-xl list-item transition">
                        <div class="w-2 h-2 rounded-full bg-green-500 flex-shrink-0"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $e->name }}</p>
                            <p class="text-[10px] text-gray-500">{{ $e->date_start ? $e->date_range : 'TBA' }}</p>
                        </div>
                        <span class="text-[9px] font-black px-2 py-0.5 rounded bg-green-50 text-green-600 uppercase">Published</span>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm py-4 text-center">No published events</p>
                @endforelse
            </div>

            <a href="{{ route('admin.PublishedEvent') }}" class="block text-center text-[10px] font-bold text-blue-600 mt-4 hover:text-blue-700 transition">
                View All Published Events →
            </a>
        </div>
    </div>

</div>

@endsection
