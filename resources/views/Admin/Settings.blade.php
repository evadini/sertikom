@extends('layouts.admin')

@section('title', 'Settings')

@section('content')

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div x-data="{ activeTab: 'profile' }" class="px-8 mt-6">

    <div class="flex gap-6 mb-8 border-b border-gray-200">
        <button @click="activeTab = 'profile'"
            :class="activeTab === 'profile'
                ? 'border-b-2 border-blue-500 text-blue-600 font-bold'
                : 'text-gray-500 hover:text-gray-900'"
            class="pb-4 px-2 text-sm font-bold transition-all">
            Profile Details
        </button>

        <button @click="activeTab = 'security'"
            :class="activeTab === 'security'
                ? 'border-b-2 border-blue-500 text-blue-600 font-bold'
                : 'text-gray-500 hover:text-gray-900'"
            class="pb-4 px-2 text-sm font-bold transition-all">
            Security
        </button>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm flex items-center gap-3 max-w-4xl">
            <i class="fa-solid fa-circle-check text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-2xl text-sm max-w-4xl">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] p-8 border border-gray-200 shadow-sm max-w-4xl">

        <div x-show="activeTab === 'profile'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95">
            <form action="{{ route('admin.settings.update-profile') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="flex items-center gap-6 mb-8">
                    <div class="relative w-24 h-24 group">
                        <div class="w-24 h-24 rounded-full border-2 border-gray-200 overflow-hidden flex items-center justify-center bg-gray-50 group-hover:border-blue-500 transition-all duration-300">
                            <img id="admin-preview"
                                 src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=2563eb&color=fff' }}"
                                 class="w-full h-full object-cover" alt="Profile">
                        </div>
                        <label for="admin-profile-picture" class="absolute bottom-0 right-0 bg-blue-600 hover:bg-blue-700 p-2 rounded-full text-white text-xs cursor-pointer shadow-lg shadow-blue-500/20 transition-all duration-200">
                            <i class="fa-solid fa-pen"></i>
                        </label>
                        <input type="file" id="admin-profile-picture" name="profile_picture" class="hidden" accept="image/*" onchange="previewAdminImage(event)">
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-700">Profile Picture</h3>
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Max 2MB.</p>
                        @error('profile_picture')
                            <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               placeholder="Enter your full name"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:outline-none focus:border-blue-500 transition duration-200">
                        @error('name') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                               placeholder="contoh@email.com"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:outline-none focus:border-blue-500 transition duration-200">
                        @error('email') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6 mb-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Phone Number</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                               placeholder="0812345567"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:outline-none focus:border-blue-500 transition duration-200">
                        @error('phone_number') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-widest px-6 py-3.5 rounded-xl shadow-lg shadow-blue-600/10 transition duration-200">
                    Save Changes
                </button>

            </form>
        </div>

        <div x-show="activeTab === 'security'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" style="display:none;">
            <form action="{{ route('admin.settings.update-password') }}" method="POST">
                @csrf
                @method('PUT')

               <div class="max-w-lg space-y-6">
    <div class="space-y-2">
        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Old Password</label>
        <div class="relative">
            <input type="password" name="current_password" id="current_password" placeholder="Enter your current password"
                   class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:outline-none focus:border-blue-500 transition duration-200">
            <button type="button" onclick="toggleAdminPassword('current_password', this)" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </button>
        </div>
        @error('current_password') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
    </div>

    <div class="space-y-2">
        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">New Password</label>
        <div class="relative">
            <input type="password" name="password" id="new_password" placeholder="Minimal 8 characters"
                   class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:outline-none focus:border-blue-500 transition duration-200">
            <button type="button" onclick="toggleAdminPassword('new_password', this)" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </button>
        </div>
        @error('password') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
    </div>

    <div class="space-y-2">
        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Confirm New Password</label>
        <div class="relative">
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Repeat new password"
                   class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:outline-none focus:border-blue-500 transition duration-200">
            <button type="button" onclick="toggleAdminPassword('password_confirmation', this)" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </button>
        </div>
    </div>

    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-widest px-6 py-3.5 rounded-xl shadow-lg shadow-blue-600/10 transition duration-200">
        Update Password
    </button>
</div>
</form>
</div>
</div>

<script>
    function previewAdminImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('admin-preview');
            if (output) output.src = reader.result;
        }
        if(event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }

    function toggleAdminPassword(id, btn) {
        const input = document.getElementById(id);
        const icon = btn.querySelector('svg');

        if (input.type === "password") {
            input.type = "text";
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
        } else {
            input.type = "password";
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 012.358-3.325M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />';
        }
    }
</script>

@endsection
