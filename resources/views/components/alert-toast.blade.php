@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl text-sm flex items-center gap-3">
        <i class="fa-solid fa-circle-check text-base"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif
@if(session('error'))
    <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 text-red-400 rounded-2xl text-sm flex items-center gap-3">
        <i class="fa-solid fa-circle-xmark text-base"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif
@if($errors->any())
    <div class="mb-6 bg-red-500/10 border border-red-500/30 text-red-400 rounded-xl px-5 py-4 text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif