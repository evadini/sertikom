<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticketify — Ajukan Sebagai Panitia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @include('components.fonts')
</head>
<body class="bg-[#F8FAFC] text-gray-900 antialiased">

    <div class="flex flex-col min-h-screen">
        @include('components.pembeli-nav')

        <main class="flex-1 flex items-center justify-center p-4">
            <div class="bg-white border border-gray-200 shadow-sm w-full max-w-md p-8 rounded-2xl transition-all duration-300">

                <div class="flex mb-6">
                    <a href="{{ route('pembeli.event') }}" class="text-sm font-semibold text-gray-500 hover:text-blue-600 transition flex items-center gap-1">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
                    </a>
                </div>

                <div class="text-center mb-8">
                    <div class="bg-blue-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="shield-check" class="text-blue-600 w-8 h-8"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Ajukan Sebagai Panitia</h2>
                    <p class="text-gray-500 text-sm mt-2">Ubah akunmu menjadi <b>Organiser</b> dan mulai kelola event.</p>
                </div>

                <div class="space-y-3 mb-6">
                    <div class="flex items-start gap-3 bg-gray-50 border border-gray-200 p-3 rounded-xl">
                        <i data-lucide="user-plus" class="text-blue-600 w-5 h-5 mt-0.5 flex-shrink-0"></i>
                        <p class="text-sm text-gray-600">
                            Status akun Anda akan berubah dari <b>Customer</b> menjadi <b>Organiser</b> setelah disetujui Admin.
                        </p>
                    </div>

                    <div class="flex items-start gap-3 bg-gray-50 border border-gray-200 p-3 rounded-xl">
                        <i data-lucide="file-check" class="text-blue-600 w-5 h-5 mt-0.5 flex-shrink-0"></i>
                        <p class="text-sm text-gray-600">
                            Anda wajib membuat event yang <b>edukatif, kreatif, dan tidak melanggar aturan kampus</b>.
                        </p>
                    </div>

                    <div class="flex items-start gap-3 bg-gray-50 border border-gray-200 p-3 rounded-xl">
                        <i data-lucide="alert-triangle" class="text-amber-500 w-5 h-5 mt-0.5 flex-shrink-0"></i>
                        <p class="text-sm text-gray-600">
                            Pengajuan dapat <b>ditolak</b> jika tidak memenuhi syarat atau data tidak valid.
                        </p>
                    </div>

                    <div class="flex items-start gap-3 bg-gray-50 border border-gray-200 p-3 rounded-xl">
                        <i data-lucide="shield-off" class="text-red-500 w-5 h-5 mt-0.5 flex-shrink-0"></i>
                        <p class="text-sm text-gray-600">
                            Hak sebagai Organiser dapat <b>dicabut</b> jika melanggar kebijakan platform.
                        </p>
                    </div>
                </div>

                @if($application)
                    <div class="border-t border-gray-200 my-4"></div>

                    <div class="p-6 bg-blue-50 border border-blue-200 rounded-2xl text-center space-y-4">
                        <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto text-blue-600">
                            <i data-lucide="clock" class="w-6 h-6"></i>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-base font-semibold text-gray-900">Pengajuan Berhasil Dikirim!</h3>
                            <p class="text-xs text-gray-500 leading-relaxed">
                                Pengajuanmu sebagai <span class="text-blue-600 font-medium">Panitia (Organiser)</span> telah masuk ke sistem. Silakan menunggu Admin memvalidasi data kamu.
                            </p>
                        </div>
                        <div class="pt-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-600 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                Status: Menunggu Validasi
                            </span>
                        </div>
                    </div>
                @elseif($rejectedApplication)
                    <div class="border-t border-gray-200 my-4"></div>

                    <div class="p-6 bg-red-50 border border-red-200 rounded-2xl text-center space-y-4">
                        <div class="bg-red-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto text-red-500">
                            <i data-lucide="x-circle" class="w-6 h-6"></i>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-base font-semibold text-gray-900">Pengajuan Ditolak</h3>
                            <p class="text-xs text-gray-500 leading-relaxed">
                                Pengajuanmu sebagai <span class="text-red-600 font-medium">Panitia (Organiser)</span> ditolak oleh Admin.
                            </p>
                        </div>
                        @if($rejectedApplication->alasan_ditolak)
                        <div class="bg-white border border-red-200 rounded-xl p-3 text-left">
                            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Alasan Penolakan</p>
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $rejectedApplication->alasan_ditolak }}</p>
                        </div>
                        @endif
                        <div class="pt-1">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600 border border-red-200">
                                Status: Ditolak
                            </span>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 my-6"></div>
                    <p class="text-center text-xs text-gray-500 mb-4">Kamu bisa mengajukan ulang dengan data yang benar.</p>

                    @include('Pembeli._form-pengajuan-panitia')
                @else
                    @include('Pembeli._form-pengajuan-panitia')
                @endif

            </div>
        </main>
    </div>

    {{-- SUCCESS MODAL --}}
    <div id="success-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/30 backdrop-blur-sm">
        <div class="bg-white border border-gray-200 rounded-3xl p-10 max-w-md w-full mx-4 text-center shadow-2xl">
            <div class="w-20 h-20 rounded-full bg-green-100 border-2 border-green-500 flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-check text-green-600 text-3xl"></i>
            </div>

            <h2 class="text-2xl font-black text-gray-900 mb-2">Pengajuan Berhasil Dikirim!</h2>
            <p class="text-gray-500 text-sm mb-4 leading-relaxed">
                Pengajuanmu sebagai <span class="text-blue-600 font-semibold">Panitia (Organiser)</span> telah masuk ke sistem. Silakan menunggu Admin memvalidasi data kamu.
            </p>

            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-600 border border-amber-200 mb-6">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                Status: Menunggu Validasi
            </div>

            <button onclick="closeSuccessModal()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold text-sm transition">
                OK, Saya Mengerti
            </button>
        </div>
    </div>

    <script>
        lucide.createIcons();

        const checkbox = document.getElementById('agreeCheck');
        const button = document.getElementById('submitBtn');

        if (checkbox && button) {
            checkbox.addEventListener('change', () => {
                if (checkbox.checked) {
                    button.classList.remove('opacity-50', 'cursor-not-allowed');
                    button.classList.add('hover:bg-blue-700', 'active:scale-[0.98]');
                } else {
                    button.classList.add('opacity-50', 'cursor-not-allowed');
                    button.classList.remove('hover:bg-blue-700', 'active:scale-[0.98]');
                }
            });

            button.addEventListener('click', (e) => {
                if (!checkbox.checked) {
                    e.preventDefault();
                    alert("Kamu harus menyetujui aturan terlebih dahulu.");
                }
            });
        }

        @if(session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('success-modal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
        @endif

        function closeSuccessModal() {
            const modal = document.getElementById('success-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>

</body>
</html>