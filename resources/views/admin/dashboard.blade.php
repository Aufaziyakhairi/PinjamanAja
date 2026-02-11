<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 leading-tight">Dashboard Admin</h2>
            <div class="text-sm text-slate-500 dark:text-slate-400">Kelola data master, transaksi, dan audit.</div>
        </div>
    </x-slot>

    <div class="ss-container">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('admin.users.index') }}" class="ss-card ss-card-hover p-5 block">
                <div class="text-sm text-slate-500 dark:text-slate-400">Master</div>
                <div class="mt-1 text-lg font-semibold">Users</div>
                <div class="mt-1 text-sm text-slate-600 dark:text-slate-300">Kelola akun & role.</div>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="ss-card ss-card-hover p-5 block">
                <div class="text-sm text-slate-500 dark:text-slate-400">Master</div>
                <div class="mt-1 text-lg font-semibold">Kategori</div>
                <div class="mt-1 text-sm text-slate-600 dark:text-slate-300">Kelompokkan alat per jenis.</div>
            </a>
            <a href="{{ route('admin.tools.index') }}" class="ss-card ss-card-hover p-5 block">
                <div class="text-sm text-slate-500 dark:text-slate-400">Master</div>
                <div class="mt-1 text-lg font-semibold">Alat</div>
                <div class="mt-1 text-sm text-slate-600 dark:text-slate-300">Data alat per unit.</div>
            </a>

            <a href="{{ route('admin.loans.index') }}" class="ss-card ss-card-hover p-5 block">
                <div class="text-sm text-slate-500 dark:text-slate-400">Transaksi</div>
                <div class="mt-1 text-lg font-semibold">Peminjaman</div>
                <div class="mt-1 text-sm text-slate-600 dark:text-slate-300">Pantau status peminjaman.</div>
            </a>
            <a href="{{ route('admin.returns.index') }}" class="ss-card ss-card-hover p-5 block">
                <div class="text-sm text-slate-500 dark:text-slate-400">Transaksi</div>
                <div class="mt-1 text-lg font-semibold">Pengembalian</div>
                <div class="mt-1 text-sm text-slate-600 dark:text-slate-300">Terima/kelola pengembalian + denda.</div>
            </a>
            <a href="{{ route('admin.activity-logs.index') }}" class="ss-card ss-card-hover p-5 block">
                <div class="text-sm text-slate-500 dark:text-slate-400">Audit</div>
                <div class="mt-1 text-lg font-semibold">Log Aktivitas</div>
                <div class="mt-1 text-sm text-slate-600 dark:text-slate-300">Jejak perubahan data.</div>
            </a>

            <a href="{{ route('admin.reports.index') }}" class="ss-card ss-card-hover p-5 block">
                <div class="text-sm text-slate-500 dark:text-slate-400">Laporan</div>
                <div class="mt-1 text-lg font-semibold">Cetak Laporan</div>
                <div class="mt-1 text-sm text-slate-600 dark:text-slate-300">Filter & cetak data peminjaman.</div>
            </a>
        </div>
    </div>
</x-app-layout>
