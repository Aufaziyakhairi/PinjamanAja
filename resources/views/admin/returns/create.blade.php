<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 leading-tight">Tambah Pengembalian</h2>
            <div class="text-sm text-slate-500 dark:text-slate-400">Tambahkan data pengembalian untuk loan tertentu.</div>
        </div>
    </x-slot>

    <div class="ss-container">
        <div class="max-w-3xl mx-auto">
            <x-flash />
            <div class="ss-card">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.returns.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="loan_id" value="Loan" />
                            <select id="loan_id" name="loan_id" class="ss-input mt-1" required>
                                <option value="">-- pilih --</option>
                                @foreach($loans as $l)
                                    <option value="{{ $l->id }}" @selected(old('loan_id') == $l->id)>#{{ $l->id }} - {{ $l->borrower?->name }} ({{ $l->status->value ?? $l->status }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="status" value="Status" />
                            <select id="status" name="status" class="ss-input mt-1" required>
                                <option value="requested" @selected(old('status')==='requested')>requested</option>
                                <option value="received" @selected(old('status')==='received')>received</option>
                                <option value="rejected" @selected(old('status')==='rejected')>rejected</option>
                            </select>
                            <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">Jika status <span class="font-medium">received</span>, denda akan dihitung sesuai keterlambatan (jika ada).</div>
                        </div>
                        <div>
                            <x-input-label for="notes" value="Catatan" />
                            <textarea id="notes" name="notes" class="ss-input mt-1" rows="3">{{ old('notes') }}</textarea>
                        </div>
                        <div class="flex gap-3">
                            <x-primary-button>Simpan</x-primary-button>
                            <a href="{{ route('admin.returns.index') }}" class="ss-link text-sm">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
