<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Memantau Pengembalian</h2>

            <form method="GET" class="flex items-center gap-2">
                <label for="status" class="text-sm text-gray-600 dark:text-gray-300">Status</label>
                <select id="status" name="status" class="ss-input text-sm py-1.5" onchange="this.form.submit()">
                    <option value="" @selected(($status ?? '') === '')>requested (default)</option>
                    <option value="all" @selected(($status ?? '') === 'all')>semua</option>
                    @foreach(($statuses ?? []) as $st)
                        <option value="{{ $st->value }}" @selected(($status ?? '') === $st->value)>{{ $st->value }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </x-slot>

    <div class="ss-container">
        <x-flash />

        <div class="ss-table-wrap">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="ss-table">
                        <thead>
                                <tr>
                                    <th class="ss-th">ID</th>
                                    <th class="ss-th">Loan</th>
                                    <th class="ss-th">Peminjam</th>
                                    <th class="ss-th">Item</th>
                                    <th class="ss-th">Jatuh Tempo</th>
                                    <th class="ss-th">Status</th>
                                    <th class="ss-th">Requested</th>
                                    <th class="ss-th">Received</th>
                                    <th class="ss-th">Denda</th>
                                    <th class="ss-th">Aksi</th>
                                </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/70 dark:divide-slate-800/70">
                                @forelse($returns as $ret)
                                    @php
                                        $loan = $ret->loan;
                                        $dueAt = $loan?->due_at;
                                        $lateDays = 0;
                                        if ($dueAt && now()->greaterThan($dueAt)) {
                                            $minutesLate = $dueAt->diffInMinutes(now());
                                            $lateDays = (int) max(1, (int) ceil($minutesLate / 1440));
                                        }
                                        $finePerDay = (int) config('loan.fine_per_day', 0);
                                        $predFine = max(0, $lateDays * $finePerDay);
                                        $fine = (int) ($ret->fine_amount ?? 0);

                                        $retStatus = $ret->status->value ?? (string) $ret->status;
                                        $retVariant = match ($retStatus) {
                                            'requested' => 'info',
                                            'received' => 'success',
                                            'rejected' => 'danger',
                                            default => 'neutral',
                                        };
                                    @endphp
                                    <tr class="ss-tr">
                                        <td class="ss-td">#{{ $ret->id }}</td>
                                        <td class="ss-td">#{{ $ret->loan_id }}</td>
                                        <td class="ss-td font-medium text-slate-900 dark:text-slate-100">{{ $loan?->borrower?->name ?? '-' }}</td>
                                        <td class="ss-td">
                                            @foreach($loan?->items ?? [] as $it)
                                                <div>{{ $it->tool?->name }} ({{ $it->qty }})</div>
                                            @endforeach
                                        </td>
                                        <td class="ss-td">
                                            @if($dueAt)
                                                <div>{{ $dueAt->format('Y-m-d H:i') }}</div>
                                                @if($lateDays > 0)
                                                    <div class="mt-1"><x-badge variant="danger">Terlambat {{ $lateDays }} hari</x-badge></div>
                                                @else
                                                    <div class="mt-1"><x-badge variant="neutral">Tepat waktu</x-badge></div>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="ss-td">
                                            <x-badge :variant="$retVariant">{{ $retStatus }}</x-badge>
                                        </td>
                                        <td class="ss-td">{{ $ret->requested_at?->format('Y-m-d H:i') ?? '-' }}</td>
                                        <td class="ss-td">
                                            @if($ret->received_at)
                                                <div>{{ $ret->received_at->format('Y-m-d H:i') }}</div>
                                                <div class="text-xs text-slate-500">oleh {{ $ret->receiver?->name ?? '-' }}</div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="ss-td">
                                            @if($fine > 0)
                                                Rp {{ number_format($fine, 0, ',', '.') }}
                                                <div class="text-xs text-slate-500">({{ (int) ($ret->fine_days ?? 0) }} hari)</div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="ss-td">
                                            @if(($ret->status->value ?? $ret->status) === 'requested')
                                                <form class="inline" method="POST" action="{{ route('petugas.returns.receive', $ret) }}" onsubmit="return confirm('Terima pengembalian?')">
                                                    @csrf
                                                    <button class="text-emerald-600 hover:underline" type="submit">Receive</button>
                                                </form>
                                                <form class="inline" method="POST" action="{{ route('petugas.returns.reject', $ret) }}" onsubmit="return confirm('Tolak pengembalian?')">
                                                    @csrf
                                                    <button class="text-red-600 hover:underline ml-3" type="submit">Reject</button>
                                                </form>

                                                <div class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                                    @if($lateDays > 0)
                                                        Perkiraan denda saat <span class="font-medium">Receive</span>:
                                                        <span class="font-medium">Rp {{ number_format($predFine, 0, ',', '.') }}</span>
                                                        ({{ $lateDays }} hari × Rp {{ number_format($finePerDay, 0, ',', '.') }}).
                                                    @else
                                                        Tidak ada denda jika diterima hari ini.
                                                    @endif
                                                </div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="py-10 text-center text-slate-500">
                                            Belum ada data pengembalian.
                                            <div class="mt-2 text-xs">Default menampilkan status <span class="font-medium">requested</span>.</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                <div class="mt-4">{{ $returns->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
