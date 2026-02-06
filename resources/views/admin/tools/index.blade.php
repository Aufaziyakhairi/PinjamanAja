<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Alat</h2>
            <a href="{{ route('admin.tools.create') }}" class="text-sm text-indigo-600 hover:underline">Tambah</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash />

            <form class="mb-4" method="GET">
                <x-text-input name="q" value="{{ request('q') }}" placeholder="Cari alat (nama)..." class="w-full sm:w-96" />
            </form>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-gray-500">
                                <tr>
                                    <th class="py-2">ID</th>
                                    <th class="py-2">Nama</th>
                                    <th class="py-2">Kategori</th>
                                    <th class="py-2">Status</th>
                                    <th class="py-2 w-40">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach ($tools as $tool)
                                    <tr>
                                        <td class="py-2 font-mono text-gray-900 dark:text-gray-100">#{{ $tool->id }}</td>
                                        <td class="py-2 font-medium text-gray-900 dark:text-gray-100">{{ $tool->name }}</td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">{{ $tool->category?->name }}</td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">
                                            @if(($tool->active_loan_items_count ?? 0) > 0)
                                                Dipinjam / Diproses
                                            @else
                                                Tersedia
                                            @endif
                                        </td>
                                        <td class="py-2">
                                            <a href="{{ route('admin.tools.edit', $tool) }}" class="text-indigo-600 hover:underline">Edit</a>
                                            <form class="inline" method="POST" action="{{ route('admin.tools.destroy', $tool) }}" onsubmit="return confirm('Hapus alat?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-red-600 hover:underline ml-3" type="submit">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">{{ $tools->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
