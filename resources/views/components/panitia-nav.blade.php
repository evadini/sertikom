<nav x-data="{ mobileOpen: false }" class="sticky top-0 z-50 bg-white border-b border-gray-100 shadow-sm">
    <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-6">
            <a href="{{ route('panitia.event') }}" class="flex items-center gap-2 flex-shrink-0">
                <div class="w-7 h-7 bg-green-600 rounded-lg flex items-center justify-center font-extrabold text-white text-xs">T</div>
                <span class="font-extrabold text-lg tracking-tight text-gray-900">Ticketify</span>
            </a>

            <div class="hidden md:flex items-center gap-1">
                <a href="{{ route('panitia.event') }}"
                   class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('panitia.event') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-list-ul mr-1.5"></i>Event
                </a>
                <a href="{{ route('panitia.create') }}"
                   class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('panitia.create') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-plus mr-1.5"></i>Create Event
                </a>
                <a href="{{ route('panitia.myevent') }}"
                   class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('panitia.myevent') || request()->routeIs('panitia.customerdata') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-ticket mr-1.5"></i>My Event
                </a>
                <a href="{{ route('panitia.attendance') }}"
                   class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('panitia.attendance') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-user-tie mr-1.5"></i>Attandance
                </a>

            </div>

            <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 text-gray-500 hover:text-blue-600 rounded-lg transition-colors" aria-label="Toggle navigation">
                <i class="fa-solid fa-bars text-lg" x-show="!mobileOpen"></i>
                <i class="fa-solid fa-xmark text-lg" x-show="mobileOpen"></i>
            </button>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('panitia.settings') }}"
               class="p-2 text-gray-500 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-colors"
               title="Settings">
                <i class="fa-solid fa-gear text-lg"></i>
            </a>

            <div class="flex items-center gap-3 pl-3 border-l border-gray-200">
                <div class="text-right leading-tight hidden sm:block">
                    <p class="text-sm font-bold text-gray-900">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] font-semibold text-green-600 uppercase tracking-wider">Organiser</p>
                </div>
                <a href="{{ route('panitia.settings') }}" class="flex-shrink-0">
                    <div class="w-9 h-9 rounded-full overflow-hidden border-2 border-gray-200 hover:border-blue-400 transition-colors">
                        @if(auth()->user()->profile_picture)
                            <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" class="w-full h-full object-cover" alt="Profile">
                        @else
                            <div class="w-full h-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs uppercase">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                </a>
                <a href="#"
                   onclick="event.preventDefault(); document.getElementById('logout-form-nav').submit();"
                   class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                   title="Logout">
                    <i class="fa-solid fa-power-off text-lg"></i>
                </a>
            </div>
            <form action="{{ route('logout') }}" method="POST" id="logout-form-nav" class="hidden">@csrf</form>
        </div>
    </div>

    <div x-show="mobileOpen" x-cloak class="md:hidden border-t border-gray-100 bg-white">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('panitia.event') }}"
               class="block px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('panitia.event') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                <i class="fa-solid fa-list-ul mr-1.5"></i>Event
            </a>
            <a href="{{ route('panitia.create') }}"
               class="block px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('panitia.create') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                <i class="fa-solid fa-plus mr-1.5"></i>Create Event
            </a>
            <a href="{{ route('panitia.myevent') }}"
               class="block px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('panitia.myevent') || request()->routeIs('panitia.customerdata') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                <i class="fa-solid fa-ticket mr-1.5"></i>My Event
            </a>
            <a href="{{ route('panitia.attendance') }}"
               class="block px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('panitia.attendance') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                <i class="fa-solid fa-user-tie mr-1.5"></i>Attandance
            </a>
        </div>
    </div>
</nav>
