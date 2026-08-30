<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticketify - Account Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @include('components.fonts')
</head>
<body class="bg-[#F8FAFC] text-gray-900 flex flex-col min-h-screen">

    @include('components.panitia-nav')

    <main class="flex-1 p-6 lg:p-10 overflow-y-auto" x-data="{ activeTab: 'profile' }">
        <header class="mb-10">
            <h1 class="text-3xl font-black tracking-tight">Account Settings</h1>
            <p class="text-gray-500 text-sm mt-2">Kelola informasi profil dan keamanan akun kamu.</p>
        </header>

        <div class="flex gap-6 mb-6 border-b border-gray-100 text-sm font-medium">
            <button @click="activeTab = 'profile'"
                    :class="activeTab === 'profile' ? 'text-blue-500 border-b-2 border-blue-500 pb-3 font-bold' : 'text-gray-500 hover:text-gray-900 pb-3'"
                    class="transition-all duration-200">
                Profile Details
            </button>
            <button @click="activeTab = 'security'"
                    :class="activeTab === 'security' ? 'text-blue-500 border-b-2 border-blue-500 pb-3 font-bold' : 'text-gray-500 hover:text-gray-900 pb-3'"
                    class="transition-all duration-200">
                Security
            </button>
        </div>
                                                
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-2xl text-sm flex items-center gap-3 max-w-4xl">
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

        <div class="bg-white p-8 rounded-[2.5rem] border border-gray-200 max-w-4xl shadow-2xl">

            <div x-show="activeTab === 'profile'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="flex items-center gap-6 mb-8">
                        <div class="relative w-24 h-24 group">
                            <img id="avatar-preview"
                                 src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}"
                                 class="w-full h-full object-cover rounded-full border-2 border-gray-200 group-hover:border-blue-500/50 transition-all duration-300"
                                 alt="Profile Picture">

                            <label for="profile_picture" class="absolute bottom-0 right-0 bg-blue-500 hover:bg-blue-600 p-2 rounded-full text-white text-xs cursor-pointer shadow-lg shadow-blue-500/20 transition-all duration-200">
                                <i class="fa-solid fa-pen"></i>
                            </label>
                            <input type="file" id="profile_picture" name="profile_picture" class="hidden" accept="image/*" onchange="previewImage(event)">
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-700">Profile Picture</h4>
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Max 2MB.</p>
                            @error('profile_picture')
                                <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-[10px] text-gray-500 font-black uppercase tracking-widest mb-2">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                   placeholder="Enter your full name"
                                   class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:outline-none focus:border-blue-500 transition duration-200">
                            @error('name') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] text-gray-400 font-black uppercase tracking-widest mb-2">NIM</label>
                            <input type="text" name="nim" value="{{ old('nim', $user->nim) }}"
                                   placeholder="Enter your NIM"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-400 opacity-60" readonly>
                            @error('nim') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-[10px] text-gray-400 font-black uppercase tracking-widest mb-2">Asal UKM (Locked)</label>
                            <input type="text" value="{{ $user->latestApplication->ukm->nama_ukm ?? 'Tidak Terdaftar' }}" disabled
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-400 cursor-not-allowed opacity-60">
                        </div>

                        <div>
                            <label class="block text-[10px] text-gray-500 font-black uppercase tracking-widest mb-2">Nomor Rekening</label>
                            <input type="text" name="nomor_rekening" value="{{ session('updated_nomor_rekening', old('nomor_rekening', $user->latestApplication->nomor_rekening ?? '')) }}"
                                   placeholder="Contoh: 1234567890 (Bank Mandiri)"
                                   class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:outline-none focus:border-blue-500 transition duration-200">
                            @error('nomor_rekening') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-[10px] text-gray-500 font-black uppercase tracking-widest mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                   placeholder="contoh@email.com"
                                   class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:outline-none focus:border-blue-500 transition duration-200">
                            @error('email') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] text-gray-500 font-black uppercase tracking-widest mb-2">Phone Number</label>
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

            <div x-show="activeTab === 'security'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" style="display: none;">
                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                   <div class="mb-6">
    <label class="block text-[10px] text-gray-500 font-black uppercase tracking-widest mb-2">Old Password</label>
    <div class="relative">
        <input type="password" name="current_password" id="current_password" placeholder="Enter your current password"
               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:outline-none focus:border-blue-500 transition duration-200">
        <button type="button" onclick="togglePassword('current_password')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
        </button>
    </div>
    @error('current_password') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
</div>

<div class="mb-6">
    <label class="block text-[10px] text-gray-500 font-black uppercase tracking-widest mb-2">New Password</label>
    <div class="relative">
        <input type="password" name="password" id="new_password" placeholder="Minimal 8 characters"
               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:outline-none focus:border-blue-500 transition duration-200">
        <button type="button" onclick="togglePassword('new_password')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
        </button>
    </div>
    @error('password') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
</div>

<div class="mb-8">
    <label class="block text-[10px] text-gray-500 font-black uppercase tracking-widest mb-2">Confirm New Password</label>
    <div class="relative">
        <input type="password" name="password_confirmation" id="confirm_password" placeholder="Repeat new password"
               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:outline-none focus:border-blue-500 transition duration-200">
        <button type="button" onclick="togglePassword('confirm_password')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
        </button>
    </div>
</div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-widest px-6 py-3.5 rounded-xl shadow-lg shadow-blue-600/10 transition duration-200">
                        Update Password
                    </button>
                </form>
            </div>

        </div>
    </main>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('avatar-preview');
                output.src = reader.result;
            }
            if(event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }

            function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    if (input.type === "password") {
        input.type = "text";
    } else {
        input.type = "password";
    }
}
    </script>
</body>
</html>
