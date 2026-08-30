@props(['categories'])
@php
    $route = 'guest.event';
    if (auth()->check()) {
        $userRole = auth()->user()->role;
        $route = match($userRole) {
            'pembeli' => 'pembeli.event',
            'panitia' => 'panitia.event',
            'admin'   => 'admin.PublishedEvent',
            default   => 'guest.event',
        };
    }
@endphp

<div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
    <form action="{{ route($route) }}" method="GET" class="flex flex-col lg:flex-row gap-3">
        <div class="flex-1 relative">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search events, categories..."
                   class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-700 placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
        </div>

        <div class="relative lg:w-44">
            <i class="fas fa-tag absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <select name="category_id"
                    class="w-full pl-10 pr-8 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-600 appearance-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all cursor-pointer">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-[10px]"></i>
        </div>

        <div class="relative lg:w-44">
            <i class="fas fa-calendar-alt absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="date" name="date" value="{{ request('date') }}"
                   class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
        </div>

        <button type="submit"
                class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl text-xs font-bold uppercase tracking-widest transition-all duration-300 shadow-lg shadow-blue-500/10 active:scale-[0.97] whitespace-nowrap">
            <i class="fa-solid fa-magnifying-glass mr-1.5"></i> Search
        </button>
    </form>
</div>
