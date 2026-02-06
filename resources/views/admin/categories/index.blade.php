<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Kategori</h2>
            <a href="{{ route('admin.categories.create') }}" class="text-sm text-indigo-600 hover:underline">Tambah</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash />

            <form class="mb-4" method="GET">
                <x-text-input name="q" value="{{ request('q') }}" placeholder="Cari kategori..." class="w-full sm:w-80" />
            </form>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-gray-500">
                                <tr>
                                    <th class="py-2">Nama</th>
                                    <th class="py-2">Deskripsi</th>
                                    <th class="py-2 w-40">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach ($categories as $category)
                                    <tr>
                                        <td class="py-2 font-medium text-gray-900 dark:text-gray-100">{{ $category->name }}</td>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">{{ \Illuminate\Support\Str::limit($category->description, 60) }}</td>
                                        <td class="py-2">
                                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-indigo-600 hover:underline">Edit</a>
                                            <form class="inline" method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-red-600 hover:underline ml-3" type="submit">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">{{ $categories->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
