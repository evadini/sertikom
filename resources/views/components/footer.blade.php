<footer class="bg-white border-t border-gray-100" x-data="{ aboutOpen: false, contactOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-10 lg:gap-8">

            <div class="lg:col-span-1 space-y-5">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-gradient-to-br bg-green-600 rounded-xl flex items-center justify-center font-extrabold text-white text-sm shadow-lg shadow-blue-500/20">T</div>
                    <span class="font-extrabold text-xl tracking-tight bg-gradient-to-r bg-green-600 bg-clip-text text-transparent">Ticketify</span>
                </div>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Platform tiket digital untuk seluruh kegiatan kemahasiswaan Politeknik Negeri Batam. Mudah, cepat, dan terpercaya.
                </p>
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/polibatam.png') }}" alt="Polibatam" class="h-14">
                </div>
            </div>

            <div>
                <h4 class="font-bold text-sm text-gray-900 mb-5">Tentang</h4>
                <ul class="space-y-3">
                    <li><a href="#" @click.prevent="aboutOpen = true" class="text-sm text-gray-500 hover:text-blue-600 transition-colors">Tentang Kami</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-sm text-gray-900 mb-5">Dukungan</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="text-sm text-gray-500 hover:text-blue-600 transition-colors">Manual Book</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-sm text-gray-900 mb-5">Layanan</h4>
                <ul class="space-y-3">
                    <li><a href="#" @click.prevent="contactOpen = true" class="text-sm text-gray-500 hover:text-blue-600 transition-colors">Kontak Developer</a></li>
                </ul>
            </div>

            <div class="space-y-6">
                <div>
                    <h4 class="font-bold text-sm text-gray-900 mb-4">Pembayaran</h4>
                    <div class="flex items-center">
                        <img src="{{ asset('images/midtranss.jpg') }}" alt="Midtrans" class="h-17">
                    </div>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-gray-900 mb-4">Ikuti Kami</h4>
                    <div class="flex gap-3">
                        <a href="#" class="w-9 h-9 bg-gray-100 hover:bg-blue-100 rounded-xl flex items-center justify-center text-gray-500 hover:text-red-600 transition-all">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        <a href="#" class="w-9 h-9 bg-gray-100 hover:bg-blue-100 rounded-xl flex items-center justify-center text-gray-500 hover:text-black transition-all">
                            <i class="fa-brands fa-tiktok"></i>
                        </a>
                        <a href="#" class="w-9 h-9 bg-gray-100 hover:bg-blue-100 rounded-xl flex items-center justify-center text-gray-500 hover:text-red-600 transition-all">
                            <i class="fa-brands fa-youtube"></i>
                        </a>
                        <a href="#" class="w-9 h-9 bg-gray-100 hover:bg-blue-100 rounded-xl flex items-center justify-center text-gray-500 hover:text-red-600 transition-all">
                            <i class="fa-regular fa-envelope"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-12 pt-6 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-xs text-gray-400">&copy; {{ date('Y') }} Ticketify. All rights reserved. Developed by Informatics Engineering — Politeknik Negeri Batam.</p>
        </div>
    </div>

{{-- Modal Nomor Kontak Developer --}}
<div x-show="contactOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
     @keydown.escape.window="contactOpen = false">

    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="contactOpen = false"></div>
    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[85vh] overflow-y-auto border border-gray-200"
         @click.outside="contactOpen = false">
        <button @click="contactOpen = false" class="absolute top-4 right-4 z-10 w-9 h-9 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:text-gray-900 hover:bg-gray-50 transition-all shadow-sm">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="p-8 text-center">
            <h2 class="text-2xl md:text-3xl font-black text-gray-900 mb-2">UNTUK INFORMASI LAINNYA</h2>
            <p class="text-gray-500 mb-10">SILAHKAN HUBUNGI TEAM WEB DEVELOPER</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    $developers = [
                        ['name' => 'M. Fauzi Azhari', 'phone' => '0878-4008-7034', 'ig' => '@muhammadfazhari.0', 'img' => 'ari.jpeg'],
                        ['name' => 'Syarifah B. S.', 'phone' => '0878-4008-7034', 'ig' => '@username', 'img' => 'sarah.jpeg'],
                        ['name' => 'Apri Catur P.', 'phone' => '0878-4008-7034', 'ig' => '@username', 'img' => 'apri.jpeg'],
                    ];
                @endphp
                @foreach($developers as $dev)
                    <div class="flex flex-col items-center gap-3 p-5 bg-gray-50 border border-gray-200 rounded-2xl hover:border-blue-400 hover:shadow-md transition-all">
                        <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-gray-200 shadow-sm">
                            <img src="{{ asset('images/' . $dev['img']) }}" class="w-full h-full object-cover" alt="{{ $dev['name'] }}">
                        </div>
                        <h3 class="font-bold text-sm text-gray-900">{{ $dev['name'] }}</h3>
                        <div class="flex flex-col gap-2 w-full text-xs">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $dev['phone']) }}" target="_blank"
                               class="flex items-center justify-center gap-2 bg-green-50 border border-green-200 text-green-700 hover:bg-green-100 py-2 rounded-xl transition-colors font-semibold">
                                <i class="fa-brands fa-whatsapp"></i>
                                <span>{{ $dev['phone'] }}</span>
                            </a>
                            <a href="https://instagram.com/{{ ltrim($dev['ig'], '@') }}" target="_blank"
                               class="flex items-center justify-center gap-2 bg-pink-50 border border-pink-200 text-pink-700 hover:bg-pink-100 py-2 rounded-xl transition-colors font-semibold">
                                <i class="fa-brands fa-instagram"></i>
                                <span>{{ $dev['ig'] }}</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

    {{-- Modal Tentang Kami --}}
    <div x-show="aboutOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
         @keydown.escape.window="aboutOpen = false">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="aboutOpen = false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-4xl max-h-[85vh] overflow-y-auto border border-gray-200"
             @click.outside="aboutOpen = false">
            <button @click="aboutOpen = false" class="absolute top-4 right-4 z-10 w-9 h-9 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:text-gray-900 hover:bg-gray-50 transition-all shadow-sm">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="p-8">
                <div class="text-center mb-10">
                    <h1 class="text-3xl lg:text-5xl font-black tracking-tight text-gray-900 mb-3">More Than <span class="text-green-600">Just Code.</span></h1>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left mt-10">
                        <div>
                            <h2 class="text-blue-600 font-bold uppercase tracking-widest text-xs mb-3">// The Genesis</h2>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                Berawal dari keresahan kami terhadap sistem manajemen event kampus yang masih konvensional, <span class="text-gray-900 font-semibold">Ticketify</span> lahir sebagai jawaban digital. Kami melihat celah antara antusiasme mahasiswa dan hambatan birokrasi tiket fisik yang seringkali menghambat jalannya sebuah kreativitas organisasi.
                            </p>
                        </div>
                        <div>
                            <h2 class="text-blue-600 font-bold uppercase tracking-widest text-xs mb-3">// The Vision</h2>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                Melalui skema <span class="text-blue-600 font-bold">Project Based Learning (PBL)</span> di Politeknik Negeri Batam, kami mengintegrasikan keahlian teknis dengan solusi nyata. Fokus kami bukan sekadar membuat platform, tapi membangun ekosistem di mana setiap momen kampus bisa diakses hanya dengan satu sentuhan jari.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mb-10">
                    <div class="flex items-end justify-between mb-6 border-b border-gray-200 pb-3">
                        <div>
                            <h2 class="text-xl font-black text-gray-900">Meet The Crew</h2>
                            <p class="text-gray-400 text-[10px] mt-0.5 uppercase tracking-widest">Collaborators of Ticketify System</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        @php
                            $crew = [
                                ['name' => 'M. Fauzi Azhari', 'role' => 'Lead Fullstack Developer', 'img' => 'ari.jpeg'],
                                ['name' => 'Syarifah B. S.', 'role' => 'Fullstack Developer', 'img' => 'sarah.jpeg'],
                                ['name' => 'Apri Catur P.', 'role' => 'Fullstack Developer', 'img' => 'apri.jpeg'],
                            ];
                        @endphp
                        @foreach($crew as $member)
                            <div class="group bg-gray-50 border border-gray-200 rounded-2xl p-5 text-center transition-all duration-300 hover:-translate-y-1 hover:border-blue-400 hover:shadow-lg">
                                <div class="w-20 h-20 mx-auto mb-3">
                                    <img src="{{ asset('images/' . $member['img']) }}" class="w-full h-full rounded-full object-cover border-2 border-gray-200" alt="{{ $member['name'] }}">
                                </div>
                                <h3 class="font-bold text-sm text-gray-900 mb-0.5">{{ $member['name'] }}</h3>
                                <p class="text-[10px] font-bold text-green-600 uppercase tracking-widest">{{ $member['role'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                    <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 flex flex-col sm:flex-row items-center gap-5">
                        <div class="flex-shrink-0">
                            <div class="w-24 h-24 rounded-full border-2 border-gray-200 p-1">
                                <img src="https://ui-avatars.com/api/?name=Project+Manager&background=333&color=fff" alt="PM" class="w-full h-full rounded-full object-cover">
                            </div>
                        </div>
                        <div class="text-center sm:text-left">
                            <h4 class="text-blue-600 text-[9px] font-black uppercase tracking-[0.3em] mb-1">The Mentorship</h4>
                            <h2 class="text-lg font-black text-gray-900 mb-2">GUIDED BY EXCELLENCE</h2>
                            <p class="text-gray-500 text-xs leading-relaxed">
                                Keberhasilan pengembangan Ticketify tidak lepas dari arahan strategis <strong>Manajer Proyek</strong> kami. Beliau memastikan alur kerja kami tetap sejalan dengan standar industri kampus.
                            </p>
                        </div>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6">
                        <h4 class="text-gray-400 text-[9px] font-black uppercase tracking-[0.3em] mb-5">Engineered With</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex items-center gap-3">
                                <i class="fa-brands fa-laravel text-2xl text-[#FF2D20]"></i>
                                <div><p class="text-xs font-bold text-gray-900">Laravel</p><p class="text-[9px] text-gray-500">Robust Backend</p></div>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fa-brands fa-php text-2xl text-[#777BB4]"></i>
                                <div><p class="text-xs font-bold text-gray-900">PHP 8.x</p><p class="text-[9px] text-gray-500">Modern Logic</p></div>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fa-brands fa-js text-2xl text-[#F7DF1E]"></i>
                                <div><p class="text-xs font-bold text-gray-900">Tailwind</p><p class="text-[9px] text-gray-500">Reactive UI</p></div>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-database text-xl text-[#4479A1]"></i>
                                <div><p class="text-xs font-bold text-gray-900">MySQL</p><p class="text-[9px] text-gray-500">Solid Storage</p></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
