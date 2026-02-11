<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 leading-tight">Pengembalian</h2>
                <div class="text-sm text-slate-500 dark:text-slate-400">Kelola permintaan pengembalian, penerimaan, dan denda.</div>
            </div>
            <a href="{{ route('admin.returns.create') }}" class="ss-link text-sm">Tambah</a>
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
                    <a href="{{ route('admin.returns.index') }}" class="ss-link text-sm">Reset</a>
                </div>
            </form>
            <div class="mt-3 text-xs text-slate-500 dark:text-slate-400">Menampilkan {{ $returns->count() }} dari {{ $returns->total() }} data.</div>
        </div>

        <div class="ss-table-wrap">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="ss-table">
                        <thead>
                            <tr>
                                <th class="ss-th">ID</th>
                                <th class="ss-th">Loan</th>
                                <th class="ss-th">Peminjam</th>
                                <th class="ss-th">Status</th>
                                <th class="ss-th">Requested</th>
                                <th class="ss-th">Received</th>
                                <th class="ss-th">Denda</th>
                                <th class="ss-th w-44">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/70 dark:divide-slate-800/70">
                            @forelse($returns as $ret)
                                @php
                                    $retStatus = $ret->status->value ?? (string) $ret->status;
                                    $retVariant = match ($retStatus) {
                                        'requested' => 'info',
                                        'received' => 'success',
                                        'rejected' => 'danger',
                                        default => 'neutral',
                                    };
                                    $fine = (int) ($ret->fine_amount ?? 0);
                                @endphp
                                <tr class="ss-tr">
                                    <td class="ss-td">#{{ $ret->id }}</td>
                                    <td class="ss-td">#{{ $ret->loan_id }}</td>
                                    <td class="ss-td font-medium text-slate-900 dark:text-slate-100">{{ $ret->loan?->borrower?->name ?? '-' }}</td>
                                    <td class="ss-td"><x-badge :variant="$retVariant">{{ $retStatus }}</x-badge></td>
                                    <td class="ss-td">{{ $ret->requested_at?->format('Y-m-d H:i') ?? '-' }}</td>
                                    <td class="ss-td">{{ $ret->received_at?->format('Y-m-d H:i') ?? '-' }}</td>
                                    <td class="ss-td">
                                        @if($fine > 0)
                                            Rp {{ number_format($fine, 0, ',', '.') }}
                                            <div class="text-xs text-slate-500">({{ (int) ($ret->fine_days ?? 0) }} hari)</div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="ss-td">
                                        <a class="ss-link" href="{{ route('admin.returns.edit', $ret) }}">Edit</a>
                                        <form class="inline" method="POST" action="{{ route('admin.returns.destroy', $ret) }}" onsubmit="return confirm('Hapus data pengembalian?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="ml-3 text-rose-600 hover:underline" type="submit">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">
                                        <x-empty-state title="Belum ada pengembalian" description="Data pengembalian akan muncul setelah peminjam mengajukan return.">
                                            <a href="{{ route('admin.returns.create') }}" class="ss-link text-sm">Tambah pengembalian</a>
                                        </x-empty-state>
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
