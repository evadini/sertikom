<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticketify | About Us</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @include('components.fonts')
</head>
<body class="bg-[#0f0f0f] text-white antialiased">

<div class="flex w-full min-h-screen border-x border-gray-800 bg-[#121212] shadow-2xl">
        @include('layouts.sidebar-pembeli')

        <div class="flex-1 flex flex-col min-w-0 border-r border-white/5 spotify-gradient">
            <nav class="sticky top-0 z-50 glass border-b border-white/5 px-8 py-4 flex justify-between items-center">

                  <!-- Sisi Kiri: Hamburger (Mobile) & Welcome Text (Desktop) -->
        <div class="flex items-center gap-4">
            <!-- TOMBOL HAMBURGER: Sekarang muncul di mobile (lg:hidden) -->
            <button id="open-sidebar" class="lg:hidden text-gray-400 hover:text-blue-500 transition-colors">
                <i class="fa-solid fa-bars-staggered text-2xl"></i>
            </button>


                <img src="{{ asset('images/polibatam.png') }}" alt="Polibatam" class="h-10 opacity-80 hover:opacity-100 transition duration-500">
                <span class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em]">Technical Informatics Project</span>
            </nav>

            <main class="p-8 lg:p-16">
                <section class="max-w-4xl mx-auto mb-32">
                    <h1 class="text-5xl lg:text-8xl font-black italic tracking-tighter mb-8 uppercase text-glow">
                        More Than <br><span class="text-[#1DB954]">Just Code.</span>
                    </h1>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mt-12">
                        <div>
                            <h2 class="text-[#1DB954] font-bold uppercase tracking-widest text-xs mb-4">// The Genesis</h2>
                            <p class="text-gray-400 text-sm leading-relaxed mb-6">
                                Berawal dari keresahan kami terhadap sistem manajemen event kampus yang masih konvensional, <span class="text-white font-semibold">Ticketify</span> lahir sebagai jawaban digital. Kami melihat celah antara antusiasme mahasiswa dan hambatan birokrasi tiket fisik yang seringkali menghambat jalannya sebuah kreativitas organisasi.
                            </p>
                        </div>
                        <div>
                            <h2 class="text-[#1DB954] font-bold uppercase tracking-widest text-xs mb-4">// The Vision</h2>
                            <p class="text-gray-400 text-sm leading-relaxed">
                                Melalui skema <span class="text-blue-400 font-bold text-glow">Project Based Learning (PBL)</span> di Politeknik Negeri Batam, kami mengintegrasikan keahlian teknis dengan solusi nyata. Fokus kami bukan sekadar membuat platform, tapi membangun ekosistem di mana setiap momen kampus bisa diakses hanya dengan satu sentuhan jari.
                            </p>
                        </div>
                    </div>
                </section>

                <section class="mb-32">
                    <div class="flex items-end justify-between mb-12 border-b border-white/10 pb-4">
                        <div>
                            <h2 class="text-3xl font-black uppercase italic">Meet The Crew</h2>
                            <p class="text-gray-500 text-xs mt-2 uppercase tracking-widest">Collaborators of Ticketify System</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div class="team-card bg-[#181818] border border-white/5 rounded-3xl p-8 text-center transition-all duration-500 group">
                            <div class="w-32 h-32 mx-auto mb-6 relative">
                                <div class="absolute inset-0 bg-[#1DB954] rounded-full blur-2xl opacity-0 group-hover:opacity-20 transition-opacity"></div>
                                <img src="{{ asset('images/ari.jpeg') }}" class="w-full h-full rounded-full object-cover border-2 border-white/10 relative z-10">
                            </div>
                            <h3 class="font-black italic text-xl tracking-tight mb-1">M. Fauzi Azhari</h3>
                            <p class="text-[10px] font-bold text-[#1DB954] uppercase tracking-widest mb-4">Lead Fullstack Developer</p>
                            <p class="text-[11px] text-gray-500 mb-6 italic">"Turning complex logic into seamless experiences."</p>
                            <div class="flex justify-center gap-4">
                                <a href="#" class="text-gray-500 hover:text-white transition text-lg"><i class="fa-brands fa-github"></i></a>
                                <a href="#" class="text-gray-500 hover:text-white transition text-lg"><i class="fa-brands fa-linkedin"></i></a>
                            </div>
                        </div>

                        <div class="team-card bg-[#181818] border border-white/5 rounded-3xl p-8 text-center transition-all duration-500 group">
                            <div class="w-32 h-32 mx-auto mb-6 relative">
                                <div class="absolute inset-0 bg-[#8b5cf6] rounded-full blur-2xl opacity-0 group-hover:opacity-20 transition-opacity"></div>
                                <img src="{{ asset('images/sarah.jpeg') }}" class="w-full h-full rounded-full object-cover border-2 border-white/10 relative z-10">
                            </div>
                            <h3 class="font-black italic text-xl tracking-tight mb-1">Syarifah B. S.</h3>
                            <p class="text-[10px] font-bold text-[#8b5cf6] uppercase tracking-widest mb-4">Fullstack Developer</p>
                            <p class="text-[11px] text-gray-500 mb-6 italic">"Structuring data with precision and passion."</p>
                            <div class="flex justify-center gap-4">
                                <a href="#" class="text-gray-500 hover:text-white transition text-lg"><i class="fa-brands fa-github"></i></a>
                                <a href="#" class="text-gray-500 hover:text-white transition text-lg"><i class="fa-brands fa-linkedin"></i></a>
                            </div>
                        </div>

                        <div class="team-card bg-[#181818] border border-white/5 rounded-3xl p-8 text-center transition-all duration-500 group">
                            <div class="w-32 h-32 mx-auto mb-6 relative">
                                <div class="absolute inset-0 bg-[#f59e0b] rounded-full blur-2xl opacity-0 group-hover:opacity-20 transition-opacity"></div>
                                <img src="{{ asset('images/apri.jpeg') }}" class="w-full h-full rounded-full object-cover border-2 border-white/10 relative z-10">
                            </div>
                            <h3 class="font-black italic text-xl tracking-tight mb-1">Apri Catur P.</h3>
                            <p class="text-[10px] font-bold text-[#f59e0b] uppercase tracking-widest mb-4">Fullstack Developer</p>
                            <p class="text-[11px] text-gray-500 mb-6 italic">"Crafting interfaces that resonate with users."</p>
                            <div class="flex justify-center gap-4">
                                <a href="#" class="text-gray-500 hover:text-white transition text-lg"><i class="fa-brands fa-github"></i></a>
                                <a href="#" class="text-gray-500 hover:text-white transition text-lg"><i class="fa-brands fa-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                   <section class="glass rounded-3xl p-10 border border-white/5 flex flex-col md:flex-row items-center gap-8 lg:col-span-1">
                    <div class="flex-shrink-0 group">
                        <div class="relative w-32 h-32 lg:w-40 lg:h-40">
                        <div class="absolute inset-0 bg-white rounded-full blur-2xl opacity-10 group-hover:opacity-20 transition-opacity"></div>
                        <div class="relative w-full h-full rounded-full border-2 border-white/20 p-1 bg-[#121212]">
                             <img src="https://ui-avatars.com/api/?name=Project+Manager&background=333&color=fff"
                                        alt="Project Manager"
                                        class="w-full h-full rounded-full object-cover grayscale hover:grayscale-0 transition-all duration-700">
                    </div>
                        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-white text-black text-[8px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-xl">
                            Mentor
                    </div>
                </div>
             </div>

                <div class="text-center md:text-left">
                    <h4 class="text-[#1DB954] text-[10px] font-black uppercase tracking-[0.4em] mb-2">The Mentorship</h4>
                    <h2 class="text-2xl font-black italic mb-4 leading-tight">GUIDED BY<br>EXCELLENCE</h2>
                    <p class="text-gray-400 text-xs leading-relaxed mb-4">
                        Keberhasilan pengembangan Ticketify tidak lepas dari arahan strategis <strong>Manajer Proyek</strong> kami. Beliau memastikan alur kerja kami tetap sejalan dengan standar industri, mulai dari arsitektur database hingga implementasi fungsionalitas Laravel.
                    </p>
                    <div class="flex items-center justify-center md:justify-start gap-3 text-gray-500">
                        <div class="h-[1px] w-8 bg-gray-700"></div>
                        <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-white/50">Tech-Informatics Expert</span>
                 </div>
            </div>
                 </section>

<section class="bg-[#181818] rounded-3xl p-10 border border-white/5">
    <h4 class="text-gray-500 text-[10px] font-black uppercase tracking-[0.4em] mb-8">Engineered With</h4>
    <div class="grid grid-cols-2 gap-8">
        <!-- Laravel -->
        <div class="flex items-center gap-4">
            <i class="fa-brands fa-laravel text-4xl text-[#FF2D20]"></i>
            <div>
                <p class="text-xs font-bold">Laravel</p>
                <p class="text-[9px] text-gray-500 italic">Robust Backend</p>
            </div>
        </div>

        <!-- PHP -->
        <div class="flex items-center gap-4">
            <i class="fa-brands fa-php text-4xl text-[#777BB4]"></i>
            <div>
                <p class="text-xs font-bold">PHP 8.x</p>
                <p class="text-[9px] text-gray-500 italic">Modern Logic</p>
            </div>
        </div>

        <!-- Tailwind / JS -->
        <div class="flex items-center gap-4">
            <i class="fa-brands fa-js text-4xl text-[#F7DF1E]"></i>
            <div>
                <p class="text-xs font-bold">Tailwind</p>
                <p class="text-[9px] text-gray-500 italic">Reactive UI</p>
            </div>
        </div>

        <!-- MySQL -->
        <div class="flex items-center gap-4">
            <i class="fa-solid fa-database text-3xl text-[#4479A1]"></i>
            <div>
                <p class="text-xs font-bold">MySQL</p>
                <p class="text-[9px] text-gray-500 italic">Solid Storage</p>
            </div>
        </div>
    </div>
</section>
                </div>
            </main>

            <footer class="mt-20 bg-black/40 border-t border-white/5 p-8 text-center">
                <p class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.3em]">&copy; 2026 Teknik Informatika - Politeknik Negeri Batam</p>
            </footer>
        </div>
    </div>

<script>
    const openBtn = document.getElementById('open-sidebar');
    const closeBtn = document.getElementById('close-sidebar');
    const sidebar = document.getElementById('main-sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    // Cek apakah elemen ada sebelum menjalankan fungsi
    if (openBtn && sidebar) {
        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            if (overlay) {
                overlay.classList.toggle('hidden');
            }
            document.body.classList.toggle('overflow-hidden', !sidebar.classList.contains('-translate-x-full'));
        }

        openBtn.addEventListener('click', toggleSidebar);

        if (closeBtn) closeBtn.addEventListener('click', toggleSidebar);
        if (overlay) overlay.addEventListener('click', toggleSidebar);
    }
</script>

</body>
</html>
