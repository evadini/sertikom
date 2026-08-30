{{-- Gganti gradien hijau spotify menjadi tosca/biru tua cerah agar serasi --}}
<div class="w-full border-t border-white/5 pt-16 bg-gradient-to-b from-transparent to-cyan-950/10">
    <main class="p-0">
        {{-- Section: More Than Just Code --}}
        <section class="text-center max-w-4xl mx-auto mb-24">
            <h1 class="text-4xl lg:text-7xl font-black italic tracking-tighter mb-8 uppercase text-shadow-[0_0_15px_rgba(6,182,212,0.3)]">
                More Than <br><span class="text-[#1DB954]">Just Code.</span>
            </h1>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 text-left mt-12">
                <div>
                    <h2 class="text-[#1DB954] font-bold uppercase tracking-widest text-xs mb-4">// The Genesis</h2>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Berawal dari keresahan kami terhadap sistem manajemen event kampus yang masih konvensional, <span class="text-white font-semibold">Ticketify</span> lahir sebagai jawaban digital. Kami melihat celah antara antusiasme mahasiswa dan hambatan birokrasi tiket fisik yang seringkali menghambat jalannya sebuah kreativitas organisasi.
                    </p>
                </div>
                <div>
                    <h2 class="text-[#1DB954] font-bold uppercase tracking-widest text-xs mb-4">// The Vision</h2>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Melalui skema <span class="text-[#1DB954] font-bold">Project Based Learning (PBL)</span> di Politeknik Negeri Batam, kami mengintegrasikan keahlian teknis dengan solusi nyata. Fokus kami bukan sekadar membuat platform, tapi membangun ekosistem di mana setiap momen kampus bisa diakses hanya dengan satu sentuhan jari.
                    </p>
                </div>
            </div>
        </section>

        {{-- Section: Meet The Crew --}}
        <section class="mb-24">
            <div class="flex items-end justify-between mb-12 border-b border-white/10 pb-4">
                <div>
                    <h2 class="text-2xl font-black italic uppercase">Meet The Crew</h2>
                    <p class="text-gray-500 text-[10px] mt-1 uppercase tracking-widest">Collaborators of Ticketify System</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="group bg-[#181818] border border-white/5 rounded-3xl p-6 text-center transition-all duration-300 hover:-translate-y-2 hover:border-cyan-500/50 hover:shadow-[0_20px_30px_rgba(6,182,212,0.1)]">
                    <div class="w-24 h-24 mx-auto mb-4 relative">
                        <div class="absolute inset-0 bg-cyan-500 rounded-full blur-xl opacity-0 group-hover:opacity-20 transition-opacity"></div>
                        <img src="{{ asset('images/ari.jpeg') }}" class="w-full h-full rounded-full object-cover border-2 border-white/10 relative z-10">
                    </div>
                    <h3 class="font-black italic text-lg tracking-tight mb-1">M. Fauzi Azhari</h3>
                    <p class="text-[9px] font-bold text-[#1DB954] uppercase tracking-widest mb-3">Lead Fullstack Developer</p>
                    <p class="text-[11px] text-gray-500 mb-4 italic">"Turning complex logic into seamless experiences."</p>
                    <div class="flex justify-center gap-4 text-gray-500">
                        <a href="#" class="hover:text-white transition"><i class="fa-brands fa-github"></i></a>
                        <a href="#" class="hover:text-white transition"><i class="fa-brands fa-linkedin"></i></a>
                    </div>
                </div>

                <div class="group bg-[#181818] border border-white/5 rounded-3xl p-6 text-center transition-all duration-300 hover:-translate-y-2 hover:border-blue-500/50 hover:shadow-[0_20px_30px_rgba(59,130,246,0.1)]">
                    <div class="w-24 h-24 mx-auto mb-4 relative">
                        <div class="absolute inset-0 bg-blue-500 rounded-full blur-xl opacity-0 group-hover:opacity-20 transition-opacity"></div>
                        <img src="{{ asset('images/sarah.jpeg') }}" class="w-full h-full rounded-full object-cover border-2 border-white/10 relative z-10">
                    </div>
                    <h3 class="font-black italic text-lg tracking-tight mb-1">Syarifah B. S.</h3>
                    <p class="text-[9px] font-bold text-blue-400 uppercase tracking-widest mb-3">Fullstack Developer</p>
                    <p class="text-[11px] text-gray-500 mb-4 italic">"Structuring data with precision and passion."</p>
                    <div class="flex justify-center gap-4 text-gray-500">
                        <a href="#" class="hover:text-white transition"><i class="fa-brands fa-github"></i></a>
                        <a href="#" class="hover:text-white transition"><i class="fa-brands fa-linkedin"></i></a>
                    </div>
                </div>

                <div class="group bg-[#181818] border border-white/5 rounded-3xl p-6 text-center transition-all duration-300 hover:-translate-y-2 hover:border-cyan-500/50 hover:shadow-[0_20px_30px_rgba(6,182,212,0.1)]">
                    <div class="w-24 h-24 mx-auto mb-4 relative">
                        <div class="absolute inset-0 bg-cyan-500 rounded-full blur-xl opacity-0 group-hover:opacity-20 transition-opacity"></div>
                        <img src="{{ asset('images/apri.jpeg') }}" class="w-full h-full rounded-full object-cover border-2 border-white/10 relative z-10">
                    </div>
                    <h3 class="font-black italic text-lg tracking-tight mb-1">Apri Catur P.</h3>
                    <p class="text-[9px] font-bold text-yellow-400 uppercase tracking-widest mb-3">Fullstack Developer</p>
                    <p class="text-[11px] text-gray-500 mb-4 italic">"Crafting interfaces that resonate with users."</p>
                    <div class="flex justify-center gap-4 text-gray-500">
                        <a href="#" class="hover:text-white transition"><i class="fa-brands fa-github"></i></a>
                        <a href="#" class="hover:text-white transition"><i class="fa-brands fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
        </section>

        {{-- Section: Mentorship & Tech --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <section class="bg-white/[0.02] backdrop-blur-md rounded-3xl p-8 border border-white/5 flex flex-col sm:flex-row items-center gap-6">
                <div class="flex-shrink-0 group">
                    <div class="relative w-28 h-28">
                        <div class="absolute inset-0 bg-white rounded-full blur-xl opacity-5 group-hover:opacity-10 transition-opacity"></div>
                        <div class="relative w-full h-full rounded-full border border-white/10 p-1 bg-[#121212]">
                            <img src="https://ui-avatars.com/api/?name=Project+Manager&background=333&color=fff" alt="Project Manager" class="w-full h-full rounded-full object-cover grayscale hover:grayscale-0 transition-all duration-500">
                        </div>
                        <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 bg-white text-black text-[7px] font-black px-2 py-0.5 rounded-full uppercase tracking-widest shadow-xl">
                            Mentor
                        </div>
                    </div>
                </div>
                <div class="text-center sm:text-left">
                    <h4 class="text-[#1DB954] text-[9px] font-black uppercase tracking-[0.3em] mb-1">The Mentorship</h4>
                    <h2 class="text-xl font-black italic mb-2 leading-tight">GUIDED BY EXCELLENCE</h2>
                    <p class="text-gray-400 text-xs leading-relaxed">
                        Keberhasilan pengembangan Ticketify tidak lepas dari arahan strategis <strong>Manajer Proyek</strong> kami. Beliau memastikan alur kerja kami tetap sejalan dengan standar industri kampus.
                    </p>
                </div>
            </section>

            <section class="bg-[#181818] rounded-3xl p-8 border border-white/5">
                <h4 class="text-gray-500 text-[9px] font-black uppercase tracking-[0.3em] mb-6">Engineered With</h4>
                <div class="grid grid-cols-2 gap-6">
                    <div class="flex items-center gap-3">
                        <i class="fa-brands fa-laravel text-3xl text-[#FF2D20]"></i>
                        <div>
                            <p class="text-xs font-bold">Laravel</p>
                            <p class="text-[9px] text-gray-500 italic">Robust Backend</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fa-brands fa-php text-3xl text-[#777BB4]"></i>
                        <div>
                            <p class="text-xs font-bold">PHP 8.x</p>
                            <p class="text-[9px] text-gray-500 italic">Modern Logic</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fa-brands fa-js text-3xl text-[#F7DF1E]"></i>
                        <div>
                            <p class="text-xs font-bold">Tailwind</p>
                            <p class="text-[9px] text-gray-500 italic">Reactive UI</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-database text-2xl text-[#4479A1]"></i>
                        <div>
                            <p class="text-xs font-bold">MySQL</p>
                            <p class="text-[9px] text-gray-500 italic">Solid Storage</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <footer class="mt-16 border-t border-white/5 py-6 text-center">
        <p class="text-[9px] font-bold text-gray-600 uppercase tracking-[0.2em]">&copy; 2026 Teknik Informatika - Politeknik Negeri Batam</p>
    </footer>
</div>
