@props(['title' => 'Dashboard', 'subtitle' => null])

<nav class="glass border-b border-white/5 px-8 py-4 flex justify-between items-center">
    <button id="open-sidebar" class="lg:hidden text-gray-400 hover:text-blue-500 transition-colors">
        <i class="fa-solid fa-bars-staggered text-2xl"></i>
    </button>
    <div class="hidden lg:block">
        <h2 class="text-2xl font-black tracking-tight">{{ $title }}</h2>
        @if($subtitle)
            <p class="text-sm text-gray-500">{{ $subtitle }}</p>
        @endif
    </div>
</nav>