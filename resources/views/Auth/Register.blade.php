<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticketify | Daftar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @include('components.fonts')
</head>
<body class="bg-[#F8FAFC] text-gray-900 antialiased overflow-x-hidden">
    <div class="flex w-full min-h-screen">
        <div class="hidden lg:flex w-[40%] relative items-center justify-center overflow-hidden bg-white">
            <div class="absolute inset-0 bg-grid opacity-30"></div>
            <div class="absolute top-20 left-20 w-72 h-72 bg-blue-500/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-20 w-96 h-96 bg-purple-500/5 rounded-full blur-3xl"></div>

            <div class="relative z-10 text-center px-12">
                <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br bg-green-600 rounded-2xl flex items-center justify-center shadow-2xl shadow-blue-500/20 animate-float">
                    <i class="fa-solid fa-ticket text-3xl text-white"></i>
                </div>
                <h1 class="text-4xl font-black tracking-tight mb-3">
                    <span class="brand-gradient">Ticketify</span>
                </h1>
                <p class="text-gray-500 text-sm max-w-md leading-relaxed">
                    Bergabung dan nikmati kemudahan manajemen event & tiket digital.
                </p>
                <div class="mt-10 flex justify-center gap-6 text-gray-400 text-xs">
                    <span class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-blue-500"></i> Gratis</span>
                    <span class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-blue-500"></i> Mudah</span>
                    <span class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-blue-500"></i> Cepat</span>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-[60%] flex items-center justify-center p-6 bg-[#F8FAFC] relative min-h-screen">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_rgba(59,130,246,0.03)_0%,_transparent_70%)]"></div>

            <div class="w-full max-w-lg relative z-10 animate-form py-6">
                <div class="flex lg:hidden items-center gap-3 mb-8">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-400 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fa-solid fa-ticket text-white text-sm"></i>
                    </div>
                    <span class="font-extrabold text-lg tracking-tight bg-gradient-to-r from-blue-500 to-cyan-400 bg-clip-text text-transparent">Ticketify</span>
                </div>

                <div class="bg-white border border-gray-200 rounded-3xl p-8 md:p-10 shadow-sm glow-card">
                    <div class="mb-8">
                        <h2 class="text-2xl font-black tracking-tight mb-1 text-gray-900">Buat Akun</h2>
                        <p class="text-sm text-gray-500">Daftar untuk mulai menggunakan Ticketify</p>
                    </div>

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl">
                            <p class="text-red-700 text-sm font-bold mb-2"><i class="fa-solid fa-circle-exclamation mr-1"></i> Gagal mendaftar:</p>
                            <ul class="space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li class="text-red-500 text-xs flex items-start gap-2">
                                        <i class="fa-solid fa-circle text-[4px] mt-1.5 flex-shrink-0"></i>
                                        <span>{{ $error }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('register.store') }}" method="POST" class="space-y-5">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1 mb-2 block">Nama Lengkap</label>
                                <div class="flex items-center gap-3 bg-white border border-gray-200 rounded-xl px-4 py-3 transition-all duration-300 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500">
                                    <i class="fa-solid fa-user text-blue-500 text-sm w-5 text-center"></i>
                                    <input type="text" name="name" placeholder="Nama Lengkap" required value="{{ old('name') }}"
                                        class="bg-transparent w-full outline-none text-sm text-gray-900 placeholder:text-gray-400">
                                </div>
                            </div>

                            <div>
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1 mb-2 block">NIM</label>
                                <div class="flex items-center gap-3 bg-white border border-gray-200 rounded-xl px-4 py-3 transition-all duration-300 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500">
                                    <i class="fa-solid fa-id-card text-blue-500 text-sm w-5 text-center"></i>
                                    <input type="text" name="nim" placeholder="NIM" required value="{{ old('nim') }}"
                                        class="bg-transparent w-full outline-none text-sm text-gray-900 placeholder:text-gray-400">
                                </div>
                            </div>

                            <div>
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1 mb-2 block">No. Telepon</label>
                                <div class="flex items-center gap-3 bg-white border border-gray-200 rounded-xl px-4 py-3 transition-all duration-300 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500">
                                    <i class="fa-solid fa-phone text-blue-500 text-sm w-5 text-center"></i>
                                    <input type="tel" name="phone_number" placeholder="Nomor Telepon" required value="{{ old('phone_number') }}"
                                        class="bg-transparent w-full outline-none text-sm text-gray-900 placeholder:text-gray-400">
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1 mb-2 block">Email</label>
                                <div class="flex items-center gap-3 bg-white border border-gray-200 rounded-xl px-4 py-3 transition-all duration-300 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500">
                                    <i class="fa-solid fa-envelope text-blue-500 text-sm w-5 text-center"></i>
                                    <input type="email" name="email" placeholder="Alamat Email" required value="{{ old('email') }}"
                                        class="bg-transparent w-full outline-none text-sm text-gray-900 placeholder:text-gray-400">
                                </div>
                            </div>

                            <div>
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1 mb-2 block">Password</label>
                                <div class="flex items-center gap-3 bg-white border border-gray-200 rounded-xl px-4 py-3 transition-all duration-300 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500">
                                    <i class="fa-solid fa-lock text-blue-500 text-sm w-5 text-center"></i>
                                    <input type="password" id="password" name="password" placeholder="Min. 8 karakter" required
                                        class="bg-transparent w-full outline-none text-sm text-gray-900 placeholder:text-gray-400">
                                    <button type="button" onclick="togglePassword('password', 'eye1')" class="text-gray-400 hover:text-gray-600 transition">
                                        <i id="eye1" class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1 mb-2 block">Konfirmasi Password</label>
                                <div class="flex items-center gap-3 bg-white border border-gray-200 rounded-xl px-4 py-3 transition-all duration-300 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500">
                                    <i class="fa-solid fa-lock text-blue-500 text-sm w-5 text-center"></i>
                                    <input type="password" id="confirmPassword" name="password_confirmation" placeholder="Ulangi password" required
                                        class="bg-transparent w-full outline-none text-sm text-gray-900 placeholder:text-gray-400">
                                    <button type="button" onclick="togglePassword('confirmPassword', 'eye2')" class="text-gray-400 hover:text-gray-600 transition">
                                        <i id="eye2" class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-blue-500 to-cyan-600 text-white rounded-xl font-black uppercase text-xs tracking-widest hover:from-blue-600 hover:to-cyan-700 transition-all duration-300 shadow-lg shadow-blue-500/20 active:scale-[0.98]">
                            <i class="fa-solid fa-user-plus mr-2"></i> Register
                        </button>
                    </form>

                    <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                        <p class="text-xs text-gray-500">Sudah punya akun?</p>
                        <a href="{{ route('login') }}" class="inline-block mt-2 text-sm font-bold text-blue-600 hover:text-blue-700 transition">
                            Login <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                        </a>
                    </div>
                </div>

                <p class="text-center mt-6 text-[10px] text-gray-400 font-bold uppercase tracking-[0.3em]">
                    &copy; 2026 Informatics Engineering - Polibatam
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>
