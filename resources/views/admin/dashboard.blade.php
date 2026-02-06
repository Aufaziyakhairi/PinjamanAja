<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
                    <div class="font-semibold">Menu (akan diisi CRUD sesuai tugas):</div>
                    <ul class="list-disc pl-5 text-sm text-gray-700 dark:text-gray-300">
                        <li>CRUD User</li>
                        <li>CRUD Alat</li>
                        <li>CRUD Kategori</li>
                        <li>CRUD Data Peminjaman</li>
                        <li>CRUD Pengembalian</li>
                        <li>Log Aktivitas</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
