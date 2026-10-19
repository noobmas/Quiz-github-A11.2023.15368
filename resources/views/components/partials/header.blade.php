<header class="flex min-h-20 items-center justify-between gap-4 border-b border-slate-200 bg-white px-5 py-4 shadow-sm sm:px-8">
    <div class="flex items-center gap-3">
        <button
            type="button"
            class="btn btn-ghost btn-circle lg:hidden"
            aria-label="Buka menu navigasi"
            onclick="toggleSidebar()"
        >
            <i class="fas fa-bars"></i>
        </button>
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Poliklinik</p>
            <h1 class="text-lg font-bold text-slate-800">
                {{ auth()->user()->role === 'pasien' ? 'Panel Pasien' : (auth()->user()->role === 'dokter' ? 'Panel Dokter' : 'Panel Admin') }}
            </h1>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <div class="hidden text-right sm:block">
            <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->nama }}</p>
            <p class="text-xs capitalize text-slate-500">{{ auth()->user()->role }}</p>
        </div>
        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-100 font-bold text-indigo-800">
            {{ mb_strtoupper(mb_substr(auth()->user()->nama, 0, 1)) }}
        </div>
    </div>
</header>
