<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 leading-tight">Alat</h2>
                <div class="text-sm text-slate-500 dark:text-slate-400">Kelola data alat per unit dan cek ketersediaan.</div>
            </div>
            <a href="{{ route('admin.tools.create') }}" class="ss-link text-sm">Tambah</a>
        </div>
    </x-slot>

    <div class="ss-container">
        <x-flash />

        <div class="ss-card p-4 mb-4">
            <form class="grid grid-cols-1 sm:grid-cols-3 gap-3" method="GET">
                <div class="sm:col-span-2">
                    <x-input-label for="q" value="Cari" />
                    <x-text-input id="q" name="q" value="{{ request('q') }}" placeholder="Cari alat (nama)..." class="mt-1 block w-full" />
                </div>
                <div class="flex items-end gap-2">
                    <x-primary-button type="submit">Cari</x-primary-button>
                    <a href="{{ route('admin.tools.index') }}" class="ss-link text-sm">Reset</a>
                </div>
            </form>
            <div class="mt-3 text-xs text-slate-500 dark:text-slate-400">
                Menampilkan {{ $tools->count() }} dari {{ $tools->total() }} data.
            </div>
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
                                <th class="ss-th w-44">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/70 dark:divide-slate-800/70">
                            @forelse ($tools as $tool)
                                @php $busy = (($tool->active_loan_items_count ?? 0) > 0); @endphp
                                <tr class="ss-tr">
                                    <td class="ss-td font-mono text-slate-900 dark:text-slate-100">#{{ $tool->id }}</td>
                                    <td class="ss-td font-medium text-slate-900 dark:text-slate-100">{{ $tool->name }}</td>
                                    <td class="ss-td">{{ $tool->category?->name ?? '-' }}</td>
                                    <td class="ss-td">
                                        @if($busy)
                                            <x-badge variant="warn">Dipinjam / Diproses</x-badge>
                                        @else
                                            <x-badge variant="success">Tersedia</x-badge>
                                        @endif
                                    </td>
                                    <td class="ss-td">
                                        <a href="{{ route('admin.tools.edit', $tool) }}" class="ss-link">Edit</a>
                                        <form class="inline" method="POST" action="{{ route('admin.tools.destroy', $tool) }}" onsubmit="return confirm('Hapus alat?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="ml-3 text-rose-600 hover:underline" type="submit">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <x-empty-state title="Tidak ada alat" description="Coba ubah kata kunci pencarian atau reset filter.">
                                            <a href="{{ route('admin.tools.create') }}" class="ss-link text-sm">Tambah alat</a>
                                        </x-empty-state>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $tools->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
