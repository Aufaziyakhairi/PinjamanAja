<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Detail Peminjaman #{{ $loan->id }}</h2>
            <a href="{{ route('admin.loans.edit', $loan) }}" class="text-sm text-indigo-600 hover:underline">Edit</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <x-flash />
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6 space-y-3 text-sm text-gray-700 dark:text-gray-300">
                    <div><span class="font-semibold">Peminjam:</span> {{ $loan->borrower?->name }} ({{ $loan->borrower?->email }})</div>
                    <div><span class="font-semibold">Status:</span> {{ $loan->status->value ?? $loan->status }}</div>
                    <div><span class="font-semibold">Jatuh Tempo:</span> {{ $loan->due_at?->format('Y-m-d') ?? '-' }}</div>
                    <div><span class="font-semibold">Item:</span>
                        @foreach($loan->items as $it)
                            <div>- #{{ $it->tool_id }} / {{ $it->tool?->name }} ({{ $it->qty }})</div>
                        @endforeach
                    </div>
                    <div><span class="font-semibold">Catatan:</span> {{ $loan->notes ?? '-' }}</div>
                    <div><span class="font-semibold">Return:</span> {{ $loan->loanReturn?->status->value ?? ($loan->loanReturn?->status ?? '-') }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
