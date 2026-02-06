<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 leading-tight">Dashboard Peminjam</h2>
            <div class="text-sm text-slate-500 dark:text-slate-400">Cari alat, ajukan peminjaman, dan ajukan pengembalian.</div>
        </div>
    </x-slot>

    <div class="ss-container">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('peminjam.tools.index') }}" class="ss-card ss-card-hover p-5 block">
                <div class="text-sm text-slate-500 dark:text-slate-400">Menu</div>
                <div class="mt-1 text-lg font-semibold">Daftar Alat</div>
                <div class="mt-1 text-sm text-slate-600 dark:text-slate-300">Lihat alat yang tersedia.</div>
            </a>
            <a href="{{ route('peminjam.loans.index') }}" class="ss-card ss-card-hover p-5 block">
                <div class="text-sm text-slate-500 dark:text-slate-400">Menu</div>
                <div class="mt-1 text-lg font-semibold">Peminjaman Saya</div>
                <div class="mt-1 text-sm text-slate-600 dark:text-slate-300">Ajukan & pantau status.</div>
            </a>
            <div class="ss-card p-5">
                <div class="text-sm text-slate-500 dark:text-slate-400">Tips</div>
                <div class="mt-1 text-lg font-semibold">Perhatikan Jatuh Tempo</div>
                <div class="mt-1 text-sm text-slate-600 dark:text-slate-300">Jika terlambat, denda dihitung saat pengembalian diterima.</div>
            </div>
        </div>
    </div>
</x-app-layout>
