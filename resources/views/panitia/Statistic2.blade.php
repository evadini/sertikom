<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticketify - Statistik {{ $event->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @include('components.fonts')
</head>
<body class="bg-[#F8FAFC] text-gray-900 flex flex-col min-h-screen">

    @include('components.panitia-nav')

    <main class="flex-1 p-10 overflow-y-auto">

        <div class="mb-6">
            <a href="{{ route('panitia.myevent') }}"
                class="inline-flex items-center gap-2 text-gray-400 hover:text-blue-500 transition-all duration-200 group">
                <i class="fa-solid fa-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
                <span class="text-sm font-semibold">Back</span>
            </a>
        </div>

        <header class="mb-10 flex justify-between items-end flex-wrap">
            <div>
                <h1 class="text-3xl font-black tracking-tight">Statistik Penjualan</h1>
                <p class="text-gray-500 text-sm mt-2">Pantau pendapatan dan pertumbuhan peserta secara real-time.</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl px-4 py-3 text-[10px] font-black uppercase tracking-widest text-blue-600">
                {{ $event->name }}
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-3xl border border-gray-200">
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Total Pendapatan</p>
                <h2 class="text-2xl font-black text-blue-600">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
                <p class="text-[10px] text-green-500 mt-2"><i class="fa-solid fa-arrow-up"></i> Real-time</p>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-gray-200">
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Tiket Terjual</p>
                <h2 class="text-2xl font-black text-gray-900">{{ $tiketTerjual }} <span class="text-gray-600 text-lg">/ {{ $totalKuota }}</span></h2>
                <p class="text-[10px] text-gray-500 mt-2">Sisa stok: {{ max(0, $totalKuota - $tiketTerjual) }} tiket</p>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-gray-200">
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Kehadiran (Scan)</p>
                <h2 class="text-2xl font-black text-purple-500">{{ $totalHadir }} <span class="text-gray-600 text-lg">Hadir</span></h2>
                <p class="text-[10px] text-gray-500 mt-2">
                    {{ $tiketTerjual > 0 ? round(($totalHadir / $tiketTerjual) * 100) : 0 }}% tingkat kehadiran
                </p>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] border border-gray-200">
                <h3 class="text-sm font-black uppercase tracking-widest mb-6">Tren Penjualan (7 Hari Terakhir)</h3>
                <canvas id="salesChart" height="150"></canvas>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-200">
                <h3 class="text-sm font-black uppercase tracking-widest mb-6">Kategori Tiket</h3>
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </main>

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
        const salesCtx = salesCanvas.getContext('2d');

        new Chart(salesCtx, {
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
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        grid: {
                            color: 'rgba(0,0,0,0.06)'
                        },
                        ticks: {
                            color: '#666',
                            precision: 0
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#666'
                        }
                    }
                }
            }
        });
    }

    const categoryCanvas = document.getElementById('categoryChart');

    if (categoryCanvas) {
        const catCtx = categoryCanvas.getContext('2d');

        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: kategoriLabels,
                datasets: [{
                    data: kategoriData,
                    backgroundColor: [
                        '#3b82f6',
                        '#8b5cf6',
                        '#34d399',
                        '#f59e0b',
                        '#ef4444'
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#666',
                            padding: 20,
                            font: {
                                size: 10,
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });
    }

})();
</script>
</body>
</html>
