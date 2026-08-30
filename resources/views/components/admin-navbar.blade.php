<nav x-data="{ mobileOpen: false }" class="sticky top-0 z-50 bg-white border-b border-gray-100 shadow-sm">
    <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-6">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 flex-shrink-0">
                <div class="w-7 h-7 bg-green-600 rounded-lg flex items-center justify-center font-extrabold text-white text-xs">T</div>
                <span class="font-extrabold text-lg tracking-tight text-gray-900">Ticketify</span>
            </a>

            <div class="hidden md:flex items-center gap-1">
                <a href="{{ route('admin.dashboard') }}"
                   class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-chart-line mr-1.5"></i>Dashboard
                </a>
                <a href="{{ route('admin.PublishedEvent') }}"
                   class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.PublishedEvent') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-calendar-check mr-1.5"></i>Published
                </a>
                <a href="{{ route('admin.PendingEvent') }}"
                   class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.PendingEvent') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-clock mr-1.5"></i>Pending
                </a>
                <a href="{{ route('admin.users') }}"
                   class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.users') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-users mr-1.5"></i>Users
                </a>
                <a href="{{ route('admin.role-applications') }}"
                   class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.role-applications') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-user-tie mr-1.5"></i>Roles
                </a>
                <a href="{{ route('admin.categories') }}"
                   class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.categories') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-layer-group mr-1.5"></i>Categories
                </a>
                <a href="{{ route('admin.event-statistics') }}"
                   class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.event-statistics*') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-chart-pie mr-1.5"></i>Statistik
                </a>
            </div>

            <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 text-gray-500 hover:text-blue-600 rounded-lg transition-colors" aria-label="Toggle navigation">
                <i class="fa-solid fa-bars text-lg" x-show="!mobileOpen"></i>
                <i class="fa-solid fa-xmark text-lg" x-show="mobileOpen"></i>
            </button>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.Settings') }}"
               class="p-2 text-gray-500 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-colors"
               title="Settings">
                <i class="fa-solid fa-gear text-lg"></i>
            </a>

            <div class="flex items-center gap-3 pl-3 border-l border-gray-200">
                <div class="text-right leading-tight hidden sm:block">
                    <p class="text-sm font-bold text-gray-900">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] font-semibold text-orange-600 uppercase tracking-wider">Admin</p>
                </div>
                <a href="{{ route('admin.Settings') }}" class="flex-shrink-0">
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
                   onclick="event.preventDefault(); document.getElementById('logout-form-admin-nav').submit();"
                   class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                   title="Logout">
                    <i class="fa-solid fa-power-off text-lg"></i>
                </a>
            </div>
            <form action="{{ route('logout') }}" method="POST" id="logout-form-admin-nav" class="hidden">@csrf</form>
        </div>
    </div>

    <div x-show="mobileOpen" x-cloak class="md:hidden border-t border-gray-100 bg-white">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('admin.dashboard') }}"
               class="block px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                <i class="fa-solid fa-chart-line mr-1.5"></i>Dashboard
            </a>
            <a href="{{ route('admin.PublishedEvent') }}"
               class="block px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.PublishedEvent') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                <i class="fa-solid fa-calendar-check mr-1.5"></i>Published
            </a>
            <a href="{{ route('admin.PendingEvent') }}"
               class="block px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.PendingEvent') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                <i class="fa-solid fa-clock mr-1.5"></i>Pending
            </a>
            <a href="{{ route('admin.users') }}"
               class="block px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.users') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                <i class="fa-solid fa-users mr-1.5"></i>Users
            </a>
            <a href="{{ route('admin.role-applications') }}"
               class="block px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.role-applications') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                <i class="fa-solid fa-user-tie mr-1.5"></i>Roles
            </a>
            <a href="{{ route('admin.categories') }}"
               class="block px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.categories') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                <i class="fa-solid fa-layer-group mr-1.5"></i>Categories
            </a>
            <a href="{{ route('admin.event-statistics') }}"
               class="block px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.event-statistics*') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                <i class="fa-solid fa-chart-pie mr-1.5"></i>Statistik
            </a>
        </div>
    </div>
</nav>
