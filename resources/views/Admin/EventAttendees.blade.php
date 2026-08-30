@extends('layouts.admin')

@section('title', 'Peserta ' . ($event->name ?? ''))

@section('content')

<nav class="sticky top-0 z-50 bg-white border-b border-gray-200 px-8 py-4 flex justify-between items-center">
    <div class="flex items-center gap-2 text-xs text-gray-500 font-bold tracking-widest">
        <a href="{{ route('admin.event-statistics') }}" class="hover:text-blue-600">STATISTIK EVENT</a>
        <i class="fa-solid fa-chevron-right text-[8px]"></i>
        <span class="text-gray-900">PESERTA</span>
    </div>
</nav>

<div class="px-8 mt-6">
    <header class="mb-10">
        <div class="flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-black tracking-tight uppercase italic text-blue-600">{{ $event->name }}</h1>
                <p class="text-gray-500 text-sm mt-2 font-medium">Daftar lengkap peserta yang telah membeli tiket.</p>
            </div>
        </div>
    </header>

    <div class="bg-white rounded-[2.5rem] border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50">
                    <th class="p-6 text-[10px] font-black text-gray-500 uppercase tracking-widest">No.</th>
                    <th class="p-6 text-[10px] font-black text-gray-500 uppercase tracking-widest">Peserta</th>
                    <th class="p-6 text-[10px] font-black text-gray-500 uppercase tracking-widest">Kontak</th>
                    <th class="p-6 text-[10px] font-black text-gray-500 uppercase tracking-widest">Kategori</th>
                    <th class="p-6 text-[10px] font-black text-gray-500 uppercase tracking-widest">Unique Code</th>
                    <th class="p-6 text-[10px] font-black text-gray-500 uppercase tracking-widest">Waktu Beli</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($attendees as $index => $ticket)
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="p-6 text-sm font-bold text-gray-500">
                            {{ sprintf('%02d', $attendees->firstItem() + $index) }}
                        </td>
                        <td class="p-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-[10px] uppercase">
                                    {{ substr($ticket->user->name ?? '??', 0, 2) }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-gray-900">{{ $ticket->user->name ?? 'User Terhapus' }}</p>
                                    <p class="text-[11px] text-gray-500">{{ $ticket->user->nim ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-6 text-xs text-gray-500 font-medium">
                            {{ $ticket->user->phone_number ?? '-' }}
                        </td>
                        <td class="p-6">
                            @if(strtoupper($ticket->ticket_type ?? '') === 'VIP')
                                <span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border border-purple-200">VIP</span>
                            @else
                                <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border border-blue-200">{{ $ticket->ticket_type ?? 'REGULER' }}</span>
                            @endif
                        </td>
                        <td class="p-6">
                            <code class="bg-gray-50 px-3 py-1 rounded-md text-blue-600 font-mono text-xs border border-gray-200 group-hover:border-blue-300">
                                {{ $ticket->qr_code ?? '-' }}
                            </code>
                        </td>
                        <td class="p-6 text-[11px] text-gray-500">
                            {{ $ticket->created_at ? $ticket->created_at->format('d M Y, H:i') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center text-sm font-medium text-gray-500 uppercase tracking-wider">
                            Belum ada peserta yang membeli tiket untuk event ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-6 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row gap-4 justify-between items-center">
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">
                Showing {{ $attendees->firstItem() ?? 0 }} to {{ $attendees->lastItem() ?? 0 }} of {{ $attendees->total() }} Attendees
            </p>
            <div class="flex items-center shadow-sm rounded-lg overflow-hidden border border-gray-200">
                {{ $attendees->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>

@endsection
