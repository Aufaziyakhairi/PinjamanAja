<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Laporan Peminjaman</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body class="bg-white text-gray-900">
    <div class="max-w-5xl mx-auto p-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-semibold">Laporan Peminjaman</h1>
            <button class="no-print text-sm text-indigo-600" onclick="window.print()">Print</button>
        </div>

        <table class="w-full text-sm border border-gray-200">
            <thead class="bg-gray-50 text-left">
                <tr>
                    <th class="p-2 border">ID</th>
                    <th class="p-2 border">Tanggal</th>
                    <th class="p-2 border">Peminjam</th>
                    <th class="p-2 border">Item</th>
                    <th class="p-2 border">Status</th>
                    <th class="p-2 border">Denda</th>
                </tr>
            </thead>
            <tbody>
                @php $totalFine = 0; @endphp
                @foreach($loans as $loan)
                    <tr>
                        <td class="p-2 border">#{{ $loan->id }}</td>
                        <td class="p-2 border">{{ $loan->created_at->format('Y-m-d') }}</td>
                        <td class="p-2 border">{{ $loan->borrower?->name }}</td>
                        <td class="p-2 border">
                            @foreach($loan->items as $it)
                                <div>{{ $it->tool?->name }} ({{ $it->qty }})</div>
                            @endforeach
                        </td>
                        <td class="p-2 border">{{ $loan->status->value ?? $loan->status }}</td>
                        <td class="p-2 border">
                            @php $fine = $loan->loanReturn?->fine_amount ?? 0; $totalFine += (int) $fine; @endphp
                            @if($fine > 0)
                                Rp {{ number_format($fine, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="p-2 border font-semibold" colspan="5">Total Denda</td>
                    <td class="p-2 border font-semibold">Rp {{ number_format($totalFine, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
