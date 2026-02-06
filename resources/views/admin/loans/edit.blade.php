<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Edit Peminjaman #{{ $loan->id }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <x-flash />
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                    <div class="p-6 space-y-2 text-sm text-gray-700 dark:text-gray-300">
                        <div><span class="font-semibold">Peminjam:</span> {{ $loan->borrower?->name }} ({{ $loan->borrower?->email }})</div>
                        <div><span class="font-semibold">Item:</span>
                            @foreach($loan->items as $it)
                                <div>- {{ $it->tool?->name }} ({{ $it->qty }})</div>
                            @endforeach
                        </div>
                        <div><span class="font-semibold">Approver:</span> {{ $loan->approver?->name ?? '-' }}</div>
                        <div><span class="font-semibold">Created:</span> {{ $loan->created_at }}</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form method="POST" action="{{ route('admin.loans.update', $loan) }}" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div>
                                <x-input-label for="status" value="Status" />
                                <select id="status" name="status" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" required>
                                    @foreach($statuses as $st)
                                        <option value="{{ $st->value }}" @selected(old('status', $loan->status->value ?? $loan->status) === $st->value)>{{ $st->value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="due_at" value="Jatuh Tempo" />
                                <x-text-input id="due_at" type="date" name="due_at" value="{{ old('due_at', $loan->due_at?->format('Y-m-d')) }}" class="mt-1 block w-full" />
                            </div>
                            <div>
                                <x-input-label for="notes" value="Catatan" />
                                <textarea id="notes" name="notes" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" rows="3">{{ old('notes', $loan->notes) }}</textarea>
                            </div>
                            <div class="flex gap-3">
                                <x-primary-button>Update</x-primary-button>
                                <a href="{{ route('admin.loans.index') }}" class="text-sm text-gray-600 hover:underline">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
