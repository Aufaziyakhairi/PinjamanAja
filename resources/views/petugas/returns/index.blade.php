<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Memantau Pengembalian</h2>
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
                                    <th class="py-2">Loan</th>
                                    <th class="py-2">Peminjam</th>
                                    <th class="py-2">Item</th>
                                    <th class="py-2">Jatuh Tempo</th>
                                    <th class="py-2">Requested</th>
                                    <th class="py-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($returns as $ret)
                                    <tr>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">#{{ $ret->id }}</td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">#{{ $ret->loan_id }}</td>
                                        <td class="py-2 font-medium text-gray-900 dark:text-gray-100">{{ $ret->loan?->borrower?->name }}</td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">
                                            @foreach($ret->loan?->items ?? [] as $it)
                                                <div>{{ $it->tool?->name }} ({{ $it->qty }})</div>
                                            @endforeach
                                        </td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">
                                            @if($ret->loan?->due_at)
                                                <div>{{ $ret->loan->due_at->format('Y-m-d') }}</div>
                                                @php
                                                    $nowDay = now()->startOfDay();
                                                    $dueDay = $ret->loan->due_at->startOfDay();
                                                    $lateDays = $nowDay->greaterThan($dueDay) ? $dueDay->diffInDays($nowDay) : 0;
                                                @endphp
                                                @if($lateDays > 0)
                                                    <div class="text-xs text-red-600">Terlambat {{ $lateDays }} hari</div>
                                                @else
                                                    <div class="text-xs text-gray-500">Tepat waktu</div>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">{{ $ret->requested_at }}</td>
                                        <td class="py-2">
                                            <form class="inline" method="POST" action="{{ route('petugas.returns.receive', $ret) }}" onsubmit="return confirm('Terima pengembalian?')">
                                                @csrf
                                                <button class="text-green-600 hover:underline" type="submit">Receive</button>
                                            </form>
                                            <form class="inline" method="POST" action="{{ route('petugas.returns.reject', $ret) }}" onsubmit="return confirm('Tolak pengembalian?')">
                                                @csrf
                                                <button class="text-red-600 hover:underline ml-3" type="submit">Reject</button>
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
