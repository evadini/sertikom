@extends('layouts.admin')

@section('title', 'Statistik ' . ($event->name ?? ''))

@section('content')

<nav class="sticky top-0 z-50 bg-white border-b border-gray-200 px-8 py-4 flex justify-between items-center">
    <div class="flex items-center gap-2 text-xs text-gray-500 font-bold tracking-widest">
        <a href="{{ route('admin.event-statistics') }}" class="hover:text-blue-600">
            <i class="fa-solid fa-arrow-left mr-1"></i> KEMBALI
        </a>
    </div>
</nav>

<div class="px-8 mt-6">

    <header class="mb-10 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900">Statistik Penjualan</h1>
            <p class="text-gray-500 text-sm mt-2">Pantau pendapatan dan penjualan tiket event.</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl px-4 py-3 text-[10px] font-black uppercase tracking-widest text-blue-600 shadow-sm">
            {{ $event->name }}
        </div>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm">
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Total Pendapatan</p>
            <h2 class="text-2xl font-black text-blue-600">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
            <p class="text-[10px] text-emerald-600 mt-2"><i class="fa-solid fa-arrow-up"></i> Real-time</p>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm">
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Tiket Terjual</p>
            <h2 class="text-2xl font-black text-gray-900">{{ $tiketTerjual }} <span class="text-gray-400 text-lg">/ {{ $totalKuota }}</span></h2>
            <p class="text-[10px] text-gray-500 mt-2">Sisa stok: {{ max(0, $totalKuota - $tiketTerjual) }} tiket</p>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm">
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Kehadiran</p>
            <h2 class="text-2xl font-black text-purple-600">{{ $totalHadir }} <span class="text-gray-400 text-lg">Hadir</span></h2>
            <p class="text-[10px] text-gray-500 mt-2">
                {{ $tiketTerjual > 0 ? round(($totalHadir / $tiketTerjual) * 100) : 0 }}% tingkat kehadiran
            </p>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm">
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Penyelenggara</p>
            <h2 class="text-lg font-black text-amber-600 truncate">{{ $event->user?->name ?? '-' }}</h2>
            <p class="text-[10px] text-gray-500 mt-2">{{ $event->user?->role ?? '-' }}</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] border border-gray-200 shadow-sm">
            <h3 class="text-sm font-black uppercase tracking-widest text-gray-900 mb-6">Tren Penjualan (7 Hari Terakhir)</h3>
            <canvas id="salesChart" height="150"></canvas>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] border border-gray-200 shadow-sm">
            <h3 class="text-sm font-black uppercase tracking-widest text-gray-900 mb-6">Kategori Tiket</h3>
            <canvas id="categoryChart"></canvas>
        </div>
    </div>

    <div class="mt-8 bg-white p-8 rounded-[2.5rem] border border-gray-200 shadow-sm">
        <h3 class="text-sm font-black uppercase tracking-widest text-gray-900 mb-4">Informasi Event</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-sm">
            <div>
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Tanggal</p>
                <p class="text-gray-900 font-bold mt-1">{{ $event->date_range }}</p>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Waktu</p>
                <p class="text-gray-900 font-bold mt-1">{{ \Carbon\Carbon::parse($event->time_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->time_end)->format('H:i') }} WIB</p>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Lokasi</p>
                <p class="text-gray-900 font-bold mt-1 truncate">{{ $event->location }}</p>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Status</p>
                <p class="text-gray-900 font-bold mt-1 capitalize">{{ $event->status }}</p>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    (function () {
        const rawKategori = @json($kategoriTiket);
        let kategoriLabels = rawKategori.map(item => item.ticket_type);
        let kategoriData = rawKategori.map(item => item.total);
        if (kategoriLabels.length === 0) {
            kategoriLabels = ['Belum Ada Data'];
            kategoriData = [0];
        }

        const dailySales = @json($dailySales);

        const salesCanvas = document.getElementById('salesChart');
        if (salesCanvas) {
            new Chart(salesCanvas.getContext('2d'), {
                type: 'line',
                data: {
                    labels: dailySales.labels,
                    datasets: [{
                        label: 'Tiket Terjual',
                        data: dailySales.data,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59,130,246,0.05)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#3b82f6'
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            min: 0,
                            grid: { color: 'rgba(0,0,0,0.06)' },
                            ticks: { color: '#666', precision: 0 }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#666' }
                        }
                    }
                }
            });
        }

        const categoryCanvas = document.getElementById('categoryChart');
        if (categoryCanvas) {
            new Chart(categoryCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: kategoriLabels,
                    datasets: [{
                        data: kategoriData,
                        backgroundColor: ['#3b82f6', '#8b5cf6', '#34d399', '#f59e0b', '#ef4444'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: '#666', padding: 20, font: { size: 10, weight: 'bold' } }
                        }
                    }
                }
            });
        }
    })();
    </script>
@endpush
