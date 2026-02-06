<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Peminjaman Saya</h2>
            <a href="{{ route('peminjam.loans.create') }}" class="text-sm text-indigo-600 hover:underline">Ajukan</a>
        </div>
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
                                    <th class="py-2">Tanggal</th>
                                    <th class="py-2">Item</th>
                                    <th class="py-2">Status</th>
                                    <th class="py-2">Return</th>
                                    <th class="py-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($loans as $loan)
                                    <tr>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">#{{ $loan->id }}</td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">{{ $loan->created_at->format('Y-m-d') }}</td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">
                                            @foreach($loan->items as $it)
                                                <div>{{ $it->tool?->name }} ({{ $it->qty }})</div>
                                            @endforeach
                                        </td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">{{ $loan->status->value ?? $loan->status }}</td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">{{ $loan->loanReturn?->status->value ?? ($loan->loanReturn?->status ?? '-') }}</td>
                                        <td class="py-2">
                                            @if(($loan->status->value ?? $loan->status) === 'approved' && !$loan->loanReturn)
                                                <form method="POST" action="{{ route('peminjam.returns.store', $loan) }}" onsubmit="return confirm('Ajukan pengembalian?')">
                                                    @csrf
                                                    <button class="text-indigo-600 hover:underline" type="submit">Ajukan Return</button>
                                                </form>
                                            @else
                                                -
                                            @endif
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
