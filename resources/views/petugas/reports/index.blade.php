<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Laporan</h2>
            <a href="{{ route('petugas.reports.print', request()->query()) }}" target="_blank" class="text-sm text-indigo-600 hover:underline">Print</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash />

            <form class="mb-4 grid grid-cols-1 sm:grid-cols-4 gap-3" method="GET">
                <div>
                    <x-input-label for="from" value="Dari" />
                    <x-text-input id="from" type="date" name="from" value="{{ request('from') }}" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="to" value="Sampai" />
                    <x-text-input id="to" type="date" name="to" value="{{ request('to') }}" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="status" value="Status" />
                    <select id="status" name="status" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                        <option value="">-- semua --</option>
                        <option value="pending" @selected(request('status')==='pending')>pending</option>
                        <option value="approved" @selected(request('status')==='approved')>approved</option>
                        <option value="rejected" @selected(request('status')==='rejected')>rejected</option>
                        <option value="returned" @selected(request('status')==='returned')>returned</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <x-primary-button type="submit">Terapkan</x-primary-button>
                </div>
            </form>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-gray-500">
                                <tr>
                                    <th class="py-2">ID</th>
                                    <th class="py-2">Tanggal</th>
                                    <th class="py-2">Peminjam</th>
                                    <th class="py-2">Item</th>
                                    <th class="py-2">Status</th>
                                    <th class="py-2">Denda</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($loans as $loan)
                                    <tr>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">#{{ $loan->id }}</td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">{{ $loan->created_at->format('Y-m-d') }}</td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">{{ $loan->borrower?->name }}</td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">
                                            @foreach($loan->items as $it)
                                                <div>{{ $it->tool?->name }} ({{ $it->qty }})</div>
                                            @endforeach
                                        </td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">{{ $loan->status->value ?? $loan->status }}</td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">
                                            @php $fine = $loan->loanReturn?->fine_amount ?? 0; @endphp
                                            @if($fine > 0)
                                                Rp {{ number_format($fine, 0, ',', '.') }}
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
