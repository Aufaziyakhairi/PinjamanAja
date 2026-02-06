<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Menyetujui Peminjaman</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash />
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-gray-500">
                                <tr>
                                    <th class="py-2">ID</th>
                                    <th class="py-2">Peminjam</th>
                                    <th class="py-2">Item</th>
                                    <th class="py-2">Jatuh Tempo</th>
                                    <th class="py-2">Aksi</th>
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
                                        <td class="py-2 text-gray-700 dark:text-gray-300">{{ $loan->due_at?->format('Y-m-d') ?? '-' }}</td>
                                        <td class="py-2">
                                            <form class="inline" method="POST" action="{{ route('petugas.approvals.approve', $loan) }}" onsubmit="return confirm('Approve peminjaman?')">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:underline">Approve</button>
                                            </form>
                                            <form class="inline" method="POST" action="{{ route('petugas.approvals.reject', $loan) }}" onsubmit="return confirm('Tolak peminjaman?')">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:underline ml-3">Reject</button>
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
