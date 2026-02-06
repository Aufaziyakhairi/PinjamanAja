<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Tambah Peminjaman</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <x-flash />
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.loans.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="user_id" value="Peminjam" />
                            <select id="user_id" name="user_id" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" required>
                                <option value="">-- pilih --</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" @selected(old('user_id') == $u->id)>{{ $u->name }} ({{ $u->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="tool_id" value="Alat" />
                            <select id="tool_id" name="tool_id" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" required>
                                <option value="">-- pilih --</option>
                                @foreach($tools as $t)
                                    <option value="{{ $t->id }}" @selected(old('tool_id') == $t->id)>#{{ $t->id }} - {{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="due_at" value="Jatuh Tempo (opsional)" />
                            <x-text-input id="due_at" type="date" name="due_at" value="{{ old('due_at') }}" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <x-input-label for="notes" value="Catatan" />
                            <textarea id="notes" name="notes" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" rows="3">{{ old('notes') }}</textarea>
                        </div>
                        <div class="flex gap-3">
                            <x-primary-button>Simpan</x-primary-button>
                            <a href="{{ route('admin.loans.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
