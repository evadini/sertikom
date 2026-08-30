 <!-- ================= MODAL DETAIL FINAL ================= -->
<div id="detailModal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50 p-4">

    <div class="bg-[#18181b] w-full max-w-5xl max-h-[90vh] rounded-3xl border border-white/10 overflow-hidden shadow-2xl">

        <!-- HEADER -->
        <div class="bg-green-600 px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-eye text-white"></i>
                <h2 class="text-white font-bold text-lg">Detail Event</h2>
            </div>

            <button onclick="closeDetail()" class="text-white text-xl hover:opacity-70 transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- BODY -->
        <div class="p-6 space-y-6 overflow-y-auto max-h-[calc(90vh-140px)]">

            <!-- TOP SECTION -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">

                <!-- LEFT : POSTER -->
                <div>
                    <div class="rounded-2xl overflow-hidden border border-white/10">
                        <img
                            src="https://via.placeholder.com/600x750"
                            alt="Poster Event"
                            class="w-full h-[340px] object-cover">
                    </div>
                </div>

                <!-- RIGHT : DETAIL -->
                <div class="space-y-5">

                    <!-- Date -->
                    <div class="flex items-start gap-4">
                        <div class="bg-blue-500/20 text-blue-400 rounded-xl px-4 py-3 text-center">
                            <p class="text-xl font-bold">25</p>
                            <p class="text-xs uppercase">APR</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 uppercase">Waktu Pelaksanaan</p>
                            <p id="detailTime" class="font-bold text-xl">15:00 WIB</p>
                            <p id="detailDate" class="text-sm text-gray-400">25 April 2026</p>
                        </div>
                    </div>

                    <!-- Title -->
                    <div>
                        <p class="text-xs text-gray-400 uppercase">Nama Event</p>
                        <h3 id="detailTitle" class="text-blue-400 font-bold text-2xl">
                            Event Seminar KMIPN
                        </h3>
                    </div>

                    <!-- Location -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-400 uppercase">Lokasi</p>
                            <p id="detailLocation" class="text-sm">Politeknik Negeri Batam</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 uppercase">Organizer</p>
                            <p class="text-sm">Teknik Informatika</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <p class="text-xs text-gray-400 uppercase">Deskripsi</p>
                        <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-sm text-gray-300 leading-relaxed">
                            <span id="detailDesc">
                                Event seminar membahas perkembangan teknologi terbaru.
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- TICKET SECTION -->
            <div class="space-y-4">
                <h3 class="text-xl font-bold">Available Tickets</h3>

                <!-- Regular Ticket -->
                <div class="bg-gradient-to-r from-[#111827] to-[#18181b] border border-white/10 rounded-2xl px-5 py-4 flex justify-between items-center">
                    <div>
                        <p class="text-blue-400 font-bold text-lg">
                            <i class="fa-solid fa-ticket mr-2"></i>Regular Ticket
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Event Entry</p>
                    </div>

                    <div class="text-right">
                        <p class="text-xs text-gray-500">Stock: 100</p>
                        <p class="text-blue-400 text-xl font-bold">IDR 20.000</p>
                    </div>
                </div>

                <!-- VIP Ticket -->
                <div class="bg-gradient-to-r from-[#111827] to-[#18181b] border border-yellow-500/40 rounded-2xl px-5 py-4 flex justify-between items-center">
                    <div>
                        <p class="text-yellow-400 font-bold text-lg">
                            <i class="fa-solid fa-crown mr-2"></i>VIP Ticket
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Exclusive Front Row + F&B</p>
                    </div>

                    <div class="text-right">
                        <p class="text-xs text-gray-500">Stock: 50</p>
                        <p class="text-blue-400 text-xl font-bold">IDR 50.000</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- FOOTER -->
        <div class="px-6 py-4 border-t border-white/10 text-right">
            <button onclick="closeDetail()"
                class="px-6 py-2 bg-white/10 rounded-xl text-sm hover:bg-white/20 transition">
                Tutup
            </button>
        </div>

    </div>
</div>

<!-- APPROVE MODAL -->
<div id="approveModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
    <div class="bg-[#1e1e1e] rounded-2xl p-6 w-[400px] border border-white/10 text-center">
        <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-blue-500/20 flex items-center justify-center">
            <i class="fa-solid fa-check text-blue-400"></i>
        </div>

        <h2 class="text-lg font-bold mb-2">Approve Event</h2>
        <p class="text-sm text-gray-400 mb-6">
            Are you sure you want to approve this event?
        </p>

        <div class="flex gap-3">
            <button onclick="closeApprove()"
                class="flex-1 py-2 border border-white/10 rounded-xl">
                Cancel
            </button>

            <button onclick="closeApprove()"
                class="flex-1 py-2 bg-blue-500/20 text-blue-400 rounded-xl">
                Approve
            </button>
        </div>
    </div>
</div>

<!-- REJECT MODAL -->
<div id="rejectModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
    <div class="bg-[#1e1e1e] rounded-2xl p-6 w-[400px] border border-white/10 text-center">
        <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-red-500/20 flex items-center justify-center">
            <i class="fa-solid fa-xmark text-red-400"></i>
        </div>

        <h2 class="text-lg font-bold mb-2">Reject Event</h2>
        <p class="text-sm text-gray-400 mb-6">
            Are you sure you want to reject this event?
        </p>

        <div class="flex gap-3">
            <button onclick="closeReject()"
                class="flex-1 py-2 border border-white/10 rounded-xl">
                Cancel
            </button>

            <button onclick="closeReject()"
                class="flex-1 py-2 bg-red-500/20 text-red-400 rounded-xl">
                Reject
            </button>
        </div>
    </div>
</div>

</div>

@endsection
