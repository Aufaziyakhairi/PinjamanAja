<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 leading-tight">Log Aktivitas</h2>
            <div class="text-sm text-slate-500 dark:text-slate-400">Audit trail perubahan data dan aksi user.</div>
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
                                <th class="ss-th">Waktu</th>
                                <th class="ss-th">User</th>
                                <th class="ss-th">Action</th>
                                <th class="ss-th">Subject</th>
                                <th class="ss-th">Meta</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/70 dark:divide-slate-800/70">
                            @forelse($logs as $log)
                                @php
                                    $action = (string) $log->action;
                                    $actionVariant = str_contains($action, 'delete') ? 'danger' : (str_contains($action, 'create') ? 'success' : 'info');
                                @endphp
                                <tr class="ss-tr">
                                    <td class="ss-td whitespace-nowrap">{{ $log->created_at?->format('Y-m-d H:i:s') ?? $log->created_at }}</td>
                                    <td class="ss-td">{{ $log->user?->name ?? '-' }}</td>
                                    <td class="ss-td"><x-badge :variant="$actionVariant">{{ $action }}</x-badge></td>
                                    <td class="ss-td">{{ class_basename($log->subject_type) }}#{{ $log->subject_id }}</td>
                                    <td class="ss-td">
                                        @if($log->meta)
                                            <details class="select-text">
                                                <summary class="cursor-pointer text-sm text-slate-600 hover:underline dark:text-slate-300">lihat</summary>
                                                <pre class="mt-2 whitespace-pre-wrap text-xs text-slate-600 dark:text-slate-300">{{ json_encode($log->meta, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                                            </details>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <x-empty-state title="Log masih kosong" description="Log akan terisi saat ada aksi create/update/delete pada data." />
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $logs->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
