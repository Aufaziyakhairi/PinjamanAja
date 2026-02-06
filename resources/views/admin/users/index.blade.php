<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Users</h2>
            <a href="{{ route('admin.users.create') }}" class="text-sm text-indigo-600 hover:underline">Tambah</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash />

            <form class="mb-4" method="GET">
                <x-text-input name="q" value="{{ request('q') }}" placeholder="Cari user (nama/email)..." class="w-full sm:w-96" />
            </form>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-gray-500">
                                <tr>
                                    <th class="py-2">Nama</th>
                                    <th class="py-2">Email</th>
                                    <th class="py-2">Role</th>
                                    <th class="py-2 w-40">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="py-2 font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">{{ $user->email }}</td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">{{ $user->role->value ?? $user->role }}</td>
                                        <td class="py-2">
                                            @if(($user->role->value ?? $user->role) === 'admin')
                                                <span class="text-xs text-gray-500">Tidak bisa diubah</span>
                                            @else
                                                <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:underline">Edit</a>
                                                <form class="inline" method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus user?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="text-red-600 hover:underline ml-3" type="submit">Hapus</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">{{ $users->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
