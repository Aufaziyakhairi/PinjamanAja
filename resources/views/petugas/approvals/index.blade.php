<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 leading-tight">Menyetujui Peminjaman</h2>
            <div class="text-sm text-slate-500 dark:text-slate-400">Saat approve, petugas wajib mengisi tenggat (jatuh tempo).</div>
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
                                    <th class="ss-th">Peminjam</th>
                                    <th class="ss-th">Item</th>
                                    <th class="ss-th">Tenggat</th>
                                    <th class="ss-th">Aksi</th>
                                </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/70 dark:divide-slate-800/70">
                                @forelse($loans as $loan)
                                    <tr class="ss-tr">
                                        <td class="ss-td">#{{ $loan->id }}</td>
                                        <td class="ss-td font-medium text-slate-900 dark:text-slate-100">{{ $loan->borrower?->name }}</td>
                                        <td class="ss-td">
                                            @foreach($loan->items as $it)
                                                <div>{{ $it->tool?->name }} ({{ $it->qty }})</div>
                                            @endforeach
                                        </td>
                                        <td class="ss-td">
                                            <form class="flex items-center gap-2" method="POST" action="{{ route('petugas.approvals.approve', $loan) }}" onsubmit="return confirm('Approve peminjaman dan set tenggat?')">
                                                @csrf
                                                <input
                                                    type="datetime-local"
                                                    name="due_at"
                                                    value="{{ old('due_at', now()->addDay()->format('Y-m-d\\TH:i')) }}"
                                                    min="{{ now()->format('Y-m-d\\TH:i') }}"
                                                    class="ss-input text-sm py-1.5 w-40"
                                                    required
                                                />
                                                <button type="submit" class="text-emerald-600 hover:underline">Approve</button>
                                            </form>
                                        </td>
                                        <td class="ss-td">
                                            <form class="inline" method="POST" action="{{ route('petugas.approvals.reject', $loan) }}" onsubmit="return confirm('Tolak peminjaman?')">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:underline ml-3">Reject</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-10 text-center text-slate-500">Tidak ada peminjaman yang menunggu persetujuan.</td>
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
