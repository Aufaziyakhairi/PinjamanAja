<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 leading-tight">Peminjaman</h2>
                <div class="text-sm text-slate-500 dark:text-slate-400">Pantau pengajuan, status, dan jatuh tempo.</div>
            </div>
            <a href="{{ route('admin.loans.create') }}" class="ss-link text-sm">Tambah</a>
        </div>
    </x-slot>

    <div class="ss-container">
        <x-flash />

        <div class="ss-card p-4 mb-4">
            <form class="grid grid-cols-1 sm:grid-cols-3 gap-3" method="GET">
                <div>
                    <x-input-label for="status" value="Status" />
                    <select id="status" name="status" class="ss-input mt-1">
                        <option value="">-- semua status --</option>
                        @foreach($statuses as $st)
                            <option value="{{ $st->value }}" @selected(request('status') === $st->value)>{{ $st->value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2 sm:col-span-2">
                    <x-primary-button type="submit">Filter</x-primary-button>
                    <a href="{{ route('admin.loans.index') }}" class="ss-link text-sm">Reset</a>
                </div>
            </form>
            <div class="mt-3 text-xs text-slate-500 dark:text-slate-400">Menampilkan {{ $loans->count() }} dari {{ $loans->total() }} data.</div>
        </div>

        <div class="ss-table-wrap">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="ss-table">
                        <thead>
                            <tr>
                                <th class="ss-th">ID</th>
                                <th class="ss-th">Peminjam</th>
                                <th class="ss-th">Item</th>
                                <th class="ss-th">Status</th>
                                <th class="ss-th">Jatuh Tempo</th>
                                <th class="ss-th w-56">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/70 dark:divide-slate-800/70">
                            @forelse($loans as $loan)
                                @php
                                    $loanStatus = $loan->status->value ?? (string) $loan->status;
                                    $loanVariant = match ($loanStatus) {
                                        'pending' => 'warn',
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        'returned' => 'neutral',
                                        default => 'neutral',
                                    };

                                    $dueAt = $loan->due_at;
                                    $lateDays = 0;
                                    if ($dueAt && now()->greaterThan($dueAt)) {
                                        $minutesLate = $dueAt->diffInMinutes(now());
                                        $lateDays = (int) max(1, (int) ceil($minutesLate / 1440));
                                    }
                                @endphp
                                <tr class="ss-tr">
                                    <td class="ss-td">#{{ $loan->id }}</td>
                                    <td class="ss-td font-medium text-slate-900 dark:text-slate-100">{{ $loan->borrower?->name ?? '-' }}</td>
                                    <td class="ss-td">
                                        @foreach($loan->items as $it)
                                            <div>{{ $it->tool?->name }} ({{ $it->qty }})</div>
                                        @endforeach
                                    </td>
                                    <td class="ss-td"><x-badge :variant="$loanVariant">{{ $loanStatus }}</x-badge></td>
                                    <td class="ss-td">
                                        @if($dueAt)
                                            <div>{{ $dueAt->format('Y-m-d H:i') }}</div>
                                            @if($lateDays > 0)
                                                <div class="mt-1"><x-badge variant="danger">Terlambat {{ $lateDays }} hari</x-badge></div>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="ss-td">
                                        <a class="ss-link" href="{{ route('admin.loans.show', $loan) }}">Detail</a>
                                        <a class="ss-link ml-3" href="{{ route('admin.loans.edit', $loan) }}">Edit</a>
                                        <form class="inline" method="POST" action="{{ route('admin.loans.destroy', $loan) }}" onsubmit="return confirm('Hapus peminjaman?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="ml-3 text-rose-600 hover:underline" type="submit">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <x-empty-state title="Belum ada peminjaman" description="Buat transaksi peminjaman baru atau ubah filter status.">
                                            <a href="{{ route('admin.loans.create') }}" class="ss-link text-sm">Tambah peminjaman</a>
                                        </x-empty-state>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $loans->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
