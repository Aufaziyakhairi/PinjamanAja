<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 leading-tight">Dashboard Petugas</h2>
            <div class="text-sm text-slate-500 dark:text-slate-400">Proses persetujuan, pengembalian, dan laporan.</div>
        </div>
    </x-slot>

    <div class="ss-container">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('petugas.approvals.index') }}" class="ss-card ss-card-hover p-5 block">
                <div class="text-sm text-slate-500 dark:text-slate-400">Tugas</div>
                <div class="mt-1 text-lg font-semibold">Persetujuan Peminjaman</div>
                <div class="mt-1 text-sm text-slate-600 dark:text-slate-300">Approve / reject pengajuan.</div>
            </a>
            <a href="{{ route('petugas.returns.index') }}" class="ss-card ss-card-hover p-5 block">
                <div class="text-sm text-slate-500 dark:text-slate-400">Tugas</div>
                <div class="mt-1 text-lg font-semibold">Memantau Pengembalian</div>
                <div class="mt-1 text-sm text-slate-600 dark:text-slate-300">Terima / tolak pengembalian + denda.</div>
            </a>
            <a href="{{ route('petugas.reports.index') }}" class="ss-card ss-card-hover p-5 block">
                <div class="text-sm text-slate-500 dark:text-slate-400">Output</div>
                <div class="mt-1 text-lg font-semibold">Laporan</div>
                <div class="mt-1 text-sm text-slate-600 dark:text-slate-300">Filter & print laporan.</div>
            </a>
        </div>
    </div>
</x-app-layout>
