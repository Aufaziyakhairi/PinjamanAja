<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Tambah Pengembalian</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <x-flash />
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.returns.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="loan_id" value="Loan" />
                            <select id="loan_id" name="loan_id" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" required>
                                <option value="">-- pilih --</option>
                                @foreach($loans as $l)
                                    <option value="{{ $l->id }}" @selected(old('loan_id') == $l->id)>#{{ $l->id }} - {{ $l->borrower?->name }} ({{ $l->status->value ?? $l->status }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="status" value="Status" />
                            <select id="status" name="status" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" required>
                                <option value="requested" @selected(old('status')==='requested')>requested</option>
                                <option value="received" @selected(old('status')==='received')>received</option>
                                <option value="rejected" @selected(old('status')==='rejected')>rejected</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="notes" value="Catatan" />
                            <textarea id="notes" name="notes" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" rows="3">{{ old('notes') }}</textarea>
                        </div>
                        <div class="flex gap-3">
                            <x-primary-button>Simpan</x-primary-button>
                            <a href="{{ route('admin.returns.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
