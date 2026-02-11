<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Laporan</h2>
            <a href="{{ route('petugas.reports.print', request()->query()) }}" target="_blank" class="ss-link text-sm">Print</a>
        </div>
    </x-slot>

    <div class="ss-container">
        <x-flash />

        <div class="ss-card p-4 mb-4">
            <form class="grid grid-cols-1 sm:grid-cols-4 gap-3" method="GET">
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
                    <select id="status" name="status" class="ss-input mt-1">
                        <option value="">-- semua --</option>
                        <option value="pending" @selected(request('status')==='pending')>pending</option>
                        <option value="approved" @selected(request('status')==='approved')>approved</option>
                        <option value="rejected" @selected(request('status')==='rejected')>rejected</option>
                        <option value="returned" @selected(request('status')==='returned')>returned</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <x-primary-button type="submit">Terapkan</x-primary-button>
                    <a href="{{ route('petugas.reports.index') }}" class="ss-link text-sm">Reset</a>
                </div>
            </form>
        </div>

        <div class="ss-table-wrap">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="ss-table">
                        <thead>
                                <tr>
                                    <th class="ss-th">ID</th>
                                    <th class="ss-th">Tanggal</th>
                                    <th class="ss-th">Peminjam</th>
                                    <th class="ss-th">Item</th>
                                    <th class="ss-th">Status</th>
                                    <th class="ss-th">Denda</th>
                                </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/70 dark:divide-slate-800/70">
                                @forelse($loans as $loan)
                                    @php
                                        $status = $loan->status->value ?? (string) $loan->status;
                                        $variant = match ($status) {
                                            'pending' => 'warn',
                                            'approved' => 'success',
                                            'rejected' => 'danger',
                                            'returned' => 'neutral',
                                            default => 'neutral',
                                        };
                                    @endphp
                                    <tr class="ss-tr">
                                        <td class="ss-td">#{{ $loan->id }}</td>
                                        <td class="ss-td">{{ $loan->created_at->format('Y-m-d') }}</td>
                                        <td class="ss-td">{{ $loan->borrower?->name }}</td>
                                        <td class="ss-td">
                                            @foreach($loan->items as $it)
                                                <div>{{ $it->tool?->name }} ({{ $it->qty }})</div>
                                            @endforeach
                                        </td>
                                        <td class="ss-td"><x-badge :variant="$variant">{{ $status }}</x-badge></td>
                                        <td class="ss-td">
                                            @php $fine = $loan->loanReturn?->fine_amount ?? 0; @endphp
                                            @if($fine > 0)
                                                Rp {{ number_format($fine, 0, ',', '.') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-10 text-center text-slate-500">Tidak ada data untuk filter ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                <div class="mt-4">{{ $loans->links() }}</div>
            </div>
        </div>

        <div class="mt-3 text-xs text-slate-500">
            Tips: gunakan tombol <span class="font-medium">Print</span> untuk mencetak sesuai filter.
        </div>
    </div>
</x-app-layout>
