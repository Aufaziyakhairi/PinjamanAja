<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Pengembalian</h2>
            <a href="{{ route('admin.returns.create') }}" class="text-sm text-indigo-600 hover:underline">Tambah</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash />

            <form class="mb-4" method="GET">
                <select name="status" class="rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                    <option value="">-- semua status --</option>
                    @foreach($statuses as $st)
                        <option value="{{ $st->value }}" @selected(request('status') === $st->value)>{{ $st->value }}</option>
                    @endforeach
                </select>
                <x-primary-button type="submit" class="ml-2">Filter</x-primary-button>
            </form>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-gray-500">
                                <tr>
                                    <th class="py-2">ID</th>
                                    <th class="py-2">Loan</th>
                                    <th class="py-2">Peminjam</th>
                                    <th class="py-2">Status</th>
                                    <th class="py-2">Requested</th>
                                    <th class="py-2">Received</th>
                                    <th class="py-2">Denda</th>
                                    <th class="py-2 w-40">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($returns as $ret)
                                    <tr>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">#{{ $ret->id }}</td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">#{{ $ret->loan_id }}</td>
                                        <td class="py-2 font-medium text-gray-900 dark:text-gray-100">{{ $ret->loan?->borrower?->name }}</td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">{{ $ret->status->value ?? $ret->status }}</td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">{{ $ret->requested_at }}</td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">{{ $ret->received_at ?? '-' }}</td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">
                                            @if(($ret->fine_amount ?? 0) > 0)
                                                Rp {{ number_format($ret->fine_amount, 0, ',', '.') }}
                                                <span class="text-xs text-gray-500">({{ $ret->fine_days }} hari)</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="py-2">
                                            <a class="text-indigo-600 hover:underline" href="{{ route('admin.returns.edit', $ret) }}">Edit</a>
                                            <form class="inline" method="POST" action="{{ route('admin.returns.destroy', $ret) }}" onsubmit="return confirm('Hapus data pengembalian?')">
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

                    <div class="mt-4">{{ $returns->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
