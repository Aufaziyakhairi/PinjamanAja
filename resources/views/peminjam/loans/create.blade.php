<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 leading-tight">Ajukan Peminjaman</h2>
            <div class="text-sm text-slate-500 dark:text-slate-400">Pilih alat dan tulis catatan. Tenggat ditentukan petugas saat approve.</div>
        </div>
    </x-slot>

    <div class="ss-container">
        <div class="max-w-3xl mx-auto">
            <x-flash />
            <div class="ss-card">
                <div class="p-6">
                    <form method="POST" action="{{ route('peminjam.loans.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="tool_id" value="Alat" />
                            <select id="tool_id" name="tool_id" class="ss-input mt-1" required>
                                <option value="">-- pilih --</option>
                                @foreach($tools as $t)
                                    <option value="{{ $t->id }}" @selected(old('tool_id') == $t->id)>#{{ $t->id }} - {{ $t->name }}</option>
                                @endforeach
                            </select>
                            <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">Jika alat tidak muncul, berarti sedang dipinjam / diajukan oleh orang lain.</div>
                        </div>
                        <div>
                            <x-input-label for="notes" value="Catatan" />
                            <textarea id="notes" name="notes" class="ss-input mt-1" rows="3">{{ old('notes') }}</textarea>
                            <div class="mt-1 text-xs text-slate-500">Opsional. Misal: alasan peminjaman.</div>
                        </div>
                        <div class="flex items-center gap-3">
                            <x-primary-button>Ajukan</x-primary-button>
                            <a href="{{ route('peminjam.loans.index') }}" class="ss-link text-sm">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
