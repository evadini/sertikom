<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticketify | {{ $ticket->ticket_type }} Ticket</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @include('components.fonts')
</head>
<body class="bg-[#F8FAFC] antialiased">

    <nav class="sticky top-0 z-50 bg-white border-b border-gray-100 shadow-sm px-8 py-4 flex justify-between items-center">
        <div class="flex gap-4">
            <a href="{{ route('pembeli.myticket') }}" class="text-sm font-semibold text-gray-600 hover:text-blue-600 transition self-center">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </nav>

    <div class="min-h-screen w-full flex items-center justify-center p-6 bg-[#F8FAFC]">

        <div class="relative w-full max-w-md bg-white border border-gray-200 rounded-[3rem] overflow-hidden shadow-lg">

            <div class="px-8 pt-10 pb-6 text-center">
                <div class="flex justify-center mb-4">
                    <div class="bg-blue-50 border border-blue-100 p-3 rounded-2xl">
                        <i class="fa-solid fa-star text-blue-600"></i>
                    </div>
                </div>
                <h2 class="text-4xl font-black italic uppercase tracking-tighter text-gray-900">Ticketify</h2>
                <div class="mt-4 inline-flex items-center gap-2 bg-green-50 border border-green-200 px-3 py-1 rounded-full">
                    <div class="w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                    <span class="text-green-700 text-[9px] font-black uppercase tracking-widest">{{ $ticket->status }}</span>
                </div>
            </div>

            <div class="px-8 pb-6 flex flex-col items-center">
                <div class="bg-white p-5 rounded-[2.5rem] shadow-lg border border-gray-100">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $ticket->qr_code ?? $ticket->order->order_code ?? $ticket->id }}" class="w-32 h-32">
                </div>
                <p class="mt-4 text-[9px] text-gray-400 font-medium uppercase tracking-[0.2em]">Purchased on {{ ($ticket->order->paid_at ?? $ticket->created_at)->format('F d, Y') }}, at {{ ($ticket->order->paid_at ?? $ticket->created_at)->format('H:i:s') }}</p>
            </div>

            <div class="relative border-t border-dashed border-gray-200 my-4">
                <div class="absolute -left-5 -top-4 w-8 h-8 bg-[#F8FAFC] rounded-full border-r border-gray-200"></div>
                <div class="absolute -right-5 -top-4 w-8 h-8 bg-[#F8FAFC] rounded-full border-l border-gray-200"></div>
            </div>

            <div class="px-10 py-6">
                <div class="flex items-center gap-3 mb-6">
                    <i class="fa-solid fa-ticket text-blue-600 text-xl -rotate-12"></i>
                    <h3 class="text-2xl font-black italic uppercase text-gray-900 tracking-tight">{{ $ticket->ticket_type }} Ticket</h3>
                </div>

                <div class="grid grid-cols-2 gap-y-6 mb-8">
                    <div>
                        <p class="text-[9px] text-gray-500 font-bold uppercase tracking-widest mb-1">Date</p>
                        <p class="text-sm font-bold text-gray-900 uppercase italic">{{ $ticket->event->date_start ? $ticket->event->date_range : 'TBA' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] text-gray-500 font-bold uppercase tracking-widest mb-1">Time</p>
                        <p class="text-sm font-bold text-gray-900 uppercase italic">{{ $ticket->event->time ? \Carbon\Carbon::parse($ticket->event->time)->format('H : i') : 'TBA' }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-[9px] text-gray-500 font-bold uppercase tracking-widest mb-1">Location</p>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-red-500 text-xs"></i>
                            <p class="text-sm font-bold text-gray-900 uppercase italic">{{ $ticket->event->location ?? 'TBA' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4 text-center mb-8">
                    <p class="text-[9px] text-gray-500 font-black uppercase tracking-[0.3em] mb-2">Unique Code</p>
                    <p class="text-2xl font-black text-gray-900 tracking-[0.4em]">{{ $ticket->qr_code ?? $ticket->order->order_code ?? $ticket->id }}</p>
                </div>

                <div class="border-t border-gray-100 pt-6 space-y-1">
                    <p class="text-[9px] text-gray-500 font-bold uppercase tracking-widest">On behalf of</p>
                    <p class="text-lg font-black text-gray-900 italic uppercase leading-none">{{ $ticket->user->name ?? Auth::user()->name }}</p>
                    <p class="text-[10px] text-gray-500 font-medium">{{ $ticket->user->email ?? Auth::user()->email }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 border-t border-gray-200 mt-4">
                <button onclick="window.print()" class="py-6 border-r border-gray-200 bg-gray-50/50 hover:bg-gray-100 transition flex items-center justify-center gap-2 group">
                    <i class="fa-solid fa-download text-gray-400 group-hover:text-blue-600 transition-colors"></i>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-700">Save</span>
                </button>
                <a href="{{ route('pembeli.myticket') }}" class="py-6 bg-gray-50/80 hover:bg-gray-100 transition flex items-center justify-center">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-700">Close</span>
                </a>
            </div>
        </div>

    </div>

</body>
</html>