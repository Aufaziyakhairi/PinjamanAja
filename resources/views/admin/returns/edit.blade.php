<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Edit Pengembalian #{{ $return->id }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <x-flash />

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4 text-sm text-gray-700 dark:text-gray-300">
                        <div><span class="font-semibold">Loan:</span> #{{ $return->loan_id }}</div>
                        <div><span class="font-semibold">Peminjam:</span> {{ $return->loan?->borrower?->name }}</div>
                        <div><span class="font-semibold">Item:</span>
                            @foreach($return->loan?->items ?? [] as $it)
                                <div>- {{ $it->tool?->name }} ({{ $it->qty }})</div>
                            @endforeach
                        </div>
                        <div class="mt-2">
                            <span class="font-semibold">Denda:</span>
                            @if(($return->fine_amount ?? 0) > 0)
                                Rp {{ number_format($return->fine_amount, 0, ',', '.') }} ({{ $return->fine_days }} hari)
                            @else
                                -
                            @endif
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.returns.update', $return) }}" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <x-input-label for="status" value="Status" />
                            <select id="status" name="status" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" required>
                                @foreach($statuses as $st)
                                    <option value="{{ $st->value }}" @selected(old('status', $return->status->value ?? $return->status) === $st->value)>{{ $st->value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="notes" value="Catatan" />
                            <textarea id="notes" name="notes" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" rows="3">{{ old('notes', $return->notes) }}</textarea>
                        </div>
                        <div class="flex gap-3">
                            <x-primary-button>Update</x-primary-button>
                            <a href="{{ route('admin.returns.index') }}" class="text-sm text-gray-600 hover:underline">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
