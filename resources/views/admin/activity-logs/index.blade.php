<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Log Aktivitas</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash />

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-gray-500">
                                <tr>
                                    <th class="py-2">Waktu</th>
                                    <th class="py-2">User</th>
                                    <th class="py-2">Action</th>
                                    <th class="py-2">Subject</th>
                                    <th class="py-2">Meta</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($logs as $log)
                                    <tr>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">{{ $log->created_at }}</td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">{{ $log->user?->name ?? '-' }}</td>
                                        <td class="py-2 font-medium text-gray-900 dark:text-gray-100">{{ $log->action }}</td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">{{ $log->subject_type }}#{{ $log->subject_id }}</td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">
                                            @if($log->meta)
                                                <pre class="whitespace-pre-wrap">{{ json_encode($log->meta, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">{{ $logs->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
