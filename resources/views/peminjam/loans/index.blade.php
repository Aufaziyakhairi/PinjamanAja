<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 leading-tight">Histori Peminjaman</h2>
                <div class="text-sm text-slate-500 dark:text-slate-400">Pantau status pengajuan, tenggat, return, dan denda.</div>
            </div>
            <a href="{{ route('peminjam.loans.create') }}" class="ss-link text-sm">Ajukan</a>
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
                                    <th class="ss-th">Tanggal</th>
                                    <th class="ss-th">Item</th>
                                    <th class="ss-th">Jatuh Tempo</th>
                                    <th class="ss-th">Status</th>
                                    <th class="ss-th">Return</th>
                                    <th class="ss-th">Denda</th>
                                    <th class="ss-th">Aksi</th>
                                </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/70 dark:divide-slate-800/70">
                                @forelse($loans as $loan)
                                    @php
                                        $return = $loan->loanReturn;
                                        $fine = (int) ($return?->fine_amount ?? 0);

                                        $loanStatus = $loan->status->value ?? (string) $loan->status;
                                        $loanVariant = match ($loanStatus) {
                                            'pending' => 'warn',
                                            'approved' => 'success',
                                            'rejected' => 'danger',
                                            'cancelled' => 'neutral',
                                            'returned' => 'neutral',
                                            default => 'neutral',
                                        };

                                        $returnStatus = $return?->status->value ?? ($return?->status ? (string) $return->status : '-');
                                        $returnVariant = match ($returnStatus) {
                                            'requested' => 'info',
                                            'received' => 'success',
                                            'rejected' => 'danger',
                                            '-', '' => 'neutral',
                                            default => 'neutral',
                                        };
                                    @endphp
                                    <tr class="ss-tr">
                                        <td class="ss-td">#{{ $loan->id }}</td>
                                        <td class="ss-td">{{ $loan->created_at->format('Y-m-d') }}</td>
                                        <td class="ss-td">
                                            @foreach($loan->items as $it)
                                                <div>{{ $it->tool?->name }} ({{ $it->qty }})</div>
                                            @endforeach
                                        </td>
                                        <td class="ss-td">{{ $loan->due_at?->format('Y-m-d H:i') ?? '-' }}</td>
                                        <td class="ss-td"><x-badge :variant="$loanVariant">{{ $loanStatus }}</x-badge></td>
                                        <td class="ss-td">
                                            @if($return)
                                                <x-badge :variant="$returnVariant">{{ $returnStatus }}</x-badge>
                                            @else
                                                <x-badge variant="neutral">-</x-badge>
                                            @endif
                                        </td>
                                        <td class="ss-td">
                                            @if($fine > 0)
                                                Rp {{ number_format($fine, 0, ',', '.') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="ss-td">
                                            <a class="ss-link" href="{{ route('peminjam.loans.show', $loan) }}">Detail</a>
                                            @if($loanStatus === 'approved' && !$return)
                                                <form class="inline" method="POST" action="{{ route('peminjam.returns.store', $loan) }}" onsubmit="return confirm('Ajukan pengembalian?')">
                                                    @csrf
                                                    <button class="ml-3 ss-link" type="submit">Ajukan Return</button>
                                                </form>
                                            @elseif($loanStatus === 'pending')
                                                <form class="inline" method="POST" action="{{ route('peminjam.loans.cancel', $loan) }}" onsubmit="return confirm('Batalkan pengajuan ini?')">
                                                    @csrf
                                                    <button class="ml-3 ss-link text-rose-600" type="submit">Batalkan</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8">
                                            <x-empty-state title="Belum ada histori" description="Klik Ajukan untuk membuat pengajuan peminjaman pertama.">
                                                <a href="{{ route('peminjam.loans.create') }}" class="ss-link text-sm">Ajukan peminjaman</a>
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
