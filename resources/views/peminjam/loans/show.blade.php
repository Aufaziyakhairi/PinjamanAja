<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 leading-tight">Histori Peminjaman #{{ $loan->id }}</h2>
                <div class="text-sm text-slate-500 dark:text-slate-400">Lihat detail pengajuan, tenggat, status, dan pengembalian.</div>
            </div>
            <a href="{{ route('peminjam.loans.index') }}" class="ss-link text-sm">Kembali</a>
        </div>
    </x-slot>

    <div class="ss-container">
        <x-flash />

        @php
            $return = $loan->loanReturn;
            $fine = (int) ($return?->fine_amount ?? 0);

            $loanStatus = $loan->status->value ?? (string) $loan->status;
            $loanVariant = match ($loanStatus) {
                'pending' => 'warn',
                'approved' => 'success',
                'rejected' => 'danger',
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2">
                <div class="ss-card p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="text-sm text-slate-500 dark:text-slate-400">Status</div>
                            <div class="mt-1"><x-badge :variant="$loanVariant">{{ $loanStatus }}</x-badge></div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-slate-500 dark:text-slate-400">Tenggat</div>
                            <div class="mt-1 text-sm font-semibold text-slate-900 dark:text-slate-100">
                                {{ $loan->due_at?->format('Y-m-d H:i') ?? '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <div class="text-slate-500 dark:text-slate-400">Diajukan</div>
                            <div class="mt-0.5 font-medium">{{ $loan->created_at?->format('Y-m-d H:i') ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-slate-500 dark:text-slate-400">Disetujui</div>
                            <div class="mt-0.5 font-medium">{{ $loan->approved_at?->format('Y-m-d H:i') ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-slate-500 dark:text-slate-400">Ditolak</div>
                            <div class="mt-0.5 font-medium">{{ $loan->rejected_at?->format('Y-m-d H:i') ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-slate-500 dark:text-slate-400">Dikembalikan</div>
                            <div class="mt-0.5 font-medium">{{ $loan->returned_at?->format('Y-m-d H:i') ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <div class="text-sm font-semibold text-slate-900 dark:text-slate-100">Item</div>
                        <div class="mt-2 space-y-2">
                            @foreach($loan->items as $it)
                                <div class="flex items-center justify-between rounded-xl border border-slate-200/70 bg-white px-4 py-3 text-sm dark:border-slate-800/70 dark:bg-slate-900/30">
                                    <div>
                                        <div class="font-medium text-slate-900 dark:text-slate-100">{{ $it->tool?->name ?? '-' }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ $it->tool?->category?->name ?? 'Tanpa kategori' }}</div>
                                    </div>
                                    <div class="text-slate-700 dark:text-slate-200">Qty: <span class="font-semibold">{{ $it->qty }}</span></div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-5">
                        <div class="text-sm font-semibold text-slate-900 dark:text-slate-100">Catatan</div>
                        <div class="mt-2 text-sm text-slate-700 dark:text-slate-200">
                            {{ $loan->notes ?: '-' }}
                        </div>
                    </div>

                    <div class="mt-6">
                        @if($loanStatus === 'approved' && !$return)
                            <form method="POST" action="{{ route('peminjam.returns.store', $loan) }}" onsubmit="return confirm('Ajukan pengembalian?')">
                                @csrf
                                <x-primary-button type="submit">Ajukan Return</x-primary-button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div>
                <div class="ss-card p-6">
                    <div class="text-sm font-semibold text-slate-900 dark:text-slate-100">Pengembalian</div>
                    <div class="mt-2">
                        <x-badge :variant="$returnVariant">{{ $returnStatus }}</x-badge>
                    </div>

                    <div class="mt-4 space-y-3 text-sm">
                        <div>
                            <div class="text-slate-500 dark:text-slate-400">Requested</div>
                            <div class="mt-0.5 font-medium">{{ $return?->requested_at?->format('Y-m-d H:i') ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-slate-500 dark:text-slate-400">Received</div>
                            <div class="mt-0.5 font-medium">{{ $return?->received_at?->format('Y-m-d H:i') ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-slate-500 dark:text-slate-400">Denda</div>
                            <div class="mt-0.5 font-medium">
                                @if($fine > 0)
                                    Rp {{ number_format($fine, 0, ',', '.') }}
                                    <div class="text-xs text-slate-500 dark:text-slate-400">({{ (int) ($return?->fine_days ?? 0) }} hari)</div>
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="text-slate-500 dark:text-slate-400">Catatan</div>
                            <div class="mt-0.5 font-medium">{{ $return?->notes ?: '-' }}</div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-xs text-slate-500 dark:text-slate-400">
                    Denda ditetapkan saat petugas menekan <span class="font-medium">Receive</span>.
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
