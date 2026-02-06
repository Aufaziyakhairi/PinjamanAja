<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Daftar Alat</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash />

            <form class="mb-4 grid grid-cols-1 sm:grid-cols-3 gap-3" method="GET">
                <div>
                    <x-input-label for="q" value="Cari" />
                    <x-text-input id="q" name="q" value="{{ request('q') }}" placeholder="nama" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="category_id" value="Kategori" />
                    <select id="category_id" name="category_id" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                        <option value="">-- semua --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected((string)request('category_id') === (string)$cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <x-primary-button type="submit">Filter</x-primary-button>
                </div>
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
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($tools as $tool)
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $tools->links() }}</div>
                    <div class="mt-4 text-sm text-gray-600 dark:text-gray-300">
                        Ajukan peminjaman di menu "Peminjaman Saya" → "Ajukan".
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
