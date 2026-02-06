<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Peminjaman</h2>
            <a href="{{ route('admin.loans.create') }}" class="text-sm text-indigo-600 hover:underline">Tambah</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash />

            <form class="mb-4 flex flex-col sm:flex-row gap-3" method="GET">
                <select name="status" class="rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                    <option value="">-- semua status --</option>
                    @foreach($statuses as $st)
                        <option value="{{ $st->value }}" @selected(request('status') === $st->value)>{{ $st->value }}</option>
                    @endforeach
                </select>
                <x-primary-button type="submit">Filter</x-primary-button>
            </form>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-gray-500">
                                <tr>
                                    <th class="py-2">ID</th>
                                    <th class="py-2">Peminjam</th>
                                    <th class="py-2">Item</th>
                                    <th class="py-2">Status</th>
                                    <th class="py-2">Jatuh Tempo</th>
                                    <th class="py-2 w-44">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($loans as $loan)
                                    <tr>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">#{{ $loan->id }}</td>
                                        <td class="py-2 font-medium text-gray-900 dark:text-gray-100">{{ $loan->borrower?->name }}</td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">
                                            @foreach($loan->items as $it)
                                                <div>{{ $it->tool?->name }} ({{ $it->qty }})</div>
                                            @endforeach
                                        </td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">{{ $loan->status->value ?? $loan->status }}</td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">{{ $loan->due_at?->format('Y-m-d') ?? '-' }}</td>
                                        <td class="py-2">
                                            <a class="text-indigo-600 hover:underline" href="{{ route('admin.loans.show', $loan) }}">Detail</a>
                                            <a class="text-indigo-600 hover:underline ml-3" href="{{ route('admin.loans.edit', $loan) }}">Edit</a>
                                            <form class="inline" method="POST" action="{{ route('admin.loans.destroy', $loan) }}" onsubmit="return confirm('Hapus peminjaman?')">
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

                    <div class="mt-4">{{ $loans->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
