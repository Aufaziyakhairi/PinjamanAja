<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 leading-tight">Tambah Peminjaman</h2>
            <div class="text-sm text-slate-500 dark:text-slate-400">Buat transaksi peminjaman untuk peminjam.</div>
        </div>
    </x-slot>

    <div class="ss-container">
        <div class="max-w-3xl mx-auto">
            <x-flash />
            <div class="ss-card">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.loans.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="user_id" value="Peminjam" />
                            <select id="user_id" name="user_id" class="ss-input mt-1" required>
                                <option value="">-- pilih --</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" @selected(old('user_id') == $u->id)>{{ $u->name }} ({{ $u->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="tool_id" value="Alat" />
                            <select id="tool_id" name="tool_id" class="ss-input mt-1" required>
                                <option value="">-- pilih --</option>
                                @foreach($tools as $t)
                                    <option value="{{ $t->id }}" @selected(old('tool_id') == $t->id)>#{{ $t->id }} - {{ $t->name }}</option>
                                @endforeach
                            </select>
                            <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">Tip: pilih alat yang sedang tidak dipinjam.</div>
                        </div>
                        <div>
                            <x-input-label for="due_at" value="Jatuh Tempo (opsional)" />
                            <x-text-input id="due_at" type="datetime-local" name="due_at" value="{{ old('due_at') }}" class="mt-1 block w-full" />
                            <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">Kosongkan jika tenggat akan ditentukan saat approve.</div>
                        </div>
                        <div>
                            <x-input-label for="notes" value="Catatan" />
                            <textarea id="notes" name="notes" class="ss-input mt-1" rows="3">{{ old('notes') }}</textarea>
                        </div>
                        <div class="flex gap-3">
                            <x-primary-button>Simpan</x-primary-button>
                            <a href="{{ route('admin.loans.index') }}" class="ss-link text-sm">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
