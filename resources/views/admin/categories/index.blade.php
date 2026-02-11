<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 leading-tight">Kategori</h2>
                <div class="text-sm text-slate-500 dark:text-slate-400">Kelompokkan alat berdasarkan jenis agar mudah dicari.</div>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="ss-link text-sm">Tambah</a>
        </div>
    </x-slot>

    <div class="ss-container">
        <x-flash />

        <div class="ss-card p-4 mb-4">
            <form class="grid grid-cols-1 sm:grid-cols-3 gap-3" method="GET">
                <div class="sm:col-span-2">
                    <x-input-label for="q" value="Cari" />
                    <x-text-input id="q" name="q" value="{{ request('q') }}" placeholder="Cari kategori..." class="mt-1 block w-full" />
                </div>
                <div class="flex items-end gap-2">
                    <x-primary-button type="submit">Cari</x-primary-button>
                    <a href="{{ route('admin.categories.index') }}" class="ss-link text-sm">Reset</a>
                </div>
            </form>
            <div class="mt-3 text-xs text-slate-500 dark:text-slate-400">Menampilkan {{ $categories->count() }} dari {{ $categories->total() }} data.</div>
        </div>

        <div class="ss-table-wrap">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="ss-table">
                        <thead>
                            <tr>
                                <th class="ss-th">Nama</th>
                                <th class="ss-th">Deskripsi</th>
                                <th class="ss-th w-44">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/70 dark:divide-slate-800/70">
                            @forelse ($categories as $category)
                                <tr class="ss-tr">
                                    <td class="ss-td font-medium text-slate-900 dark:text-slate-100">{{ $category->name }}</td>
                                    <td class="ss-td">{{ \Illuminate\Support\Str::limit($category->description, 80) }}</td>
                                    <td class="ss-td">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="ss-link">Edit</a>
                                        <form class="inline" method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="ml-3 text-rose-600 hover:underline" type="submit">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">
                                        <x-empty-state title="Tidak ada kategori" description="Buat kategori agar alat lebih terorganisir.">
                                            <a href="{{ route('admin.categories.create') }}" class="ss-link text-sm">Tambah kategori</a>
                                        </x-empty-state>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $categories->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
