<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Daftar Alat</h2>
    </x-slot>

    <div class="ss-container">
        <x-flash />

        <div class="ss-card p-4 mb-4">
            <form class="grid grid-cols-1 sm:grid-cols-3 gap-3" method="GET">
                <div>
                    <x-input-label for="q" value="Cari" />
                    <x-text-input id="q" name="q" value="{{ request('q') }}" placeholder="nama" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="category_id" value="Kategori" />
                    <select id="category_id" name="category_id" class="ss-input mt-1">
                        <option value="">-- semua --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected((string)request('category_id') === (string)$cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <x-primary-button type="submit">Filter</x-primary-button>
                    <a href="{{ route('peminjam.tools.index') }}" class="ss-link text-sm">Reset</a>
                </div>
            </form>
        </div>

        <div class="ss-table-wrap">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="ss-table">
                        <thead>
                                <tr>
                                    <th class="ss-th">ID</th>
                                    <th class="ss-th">Nama</th>
                                    <th class="ss-th">Kategori</th>
                                    <th class="ss-th">Status</th>
                                </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/70 dark:divide-slate-800/70">
                                @forelse($tools as $tool)
                                    @php $busy = (($tool->active_loan_items_count ?? 0) > 0); @endphp
                                    <tr class="ss-tr">
                                        <td class="ss-td font-mono text-slate-900 dark:text-slate-100">#{{ $tool->id }}</td>
                                        <td class="ss-td font-medium text-slate-900 dark:text-slate-100">{{ $tool->name }}</td>
                                        <td class="ss-td">{{ $tool->category?->name }}</td>
                                        <td class="ss-td">
                                            @if($busy)
                                                <x-badge variant="warn">Dipinjam / Diproses</x-badge>
                                            @else
                                                <x-badge variant="success">Tersedia</x-badge>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-10 text-center text-slate-500">Tidak ada alat untuk filter ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                <div class="mt-4">{{ $tools->links() }}</div>
            </div>
        </div>

        <div class="mt-3 text-sm text-slate-600 dark:text-slate-300">
            Ajukan peminjaman di menu <span class="font-medium">Peminjaman Saya</span> → <span class="font-medium">Ajukan</span>.
        </div>
    </div>
</x-app-layout>
