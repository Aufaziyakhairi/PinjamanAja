<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Edit User</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <x-flash />
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <x-input-label for="name" value="Nama" />
                            <x-text-input id="name" name="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="email" value="Email" />
                            <x-text-input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="role" value="Role" />
                            <select id="role" name="role" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->value }}" @selected(old('role', $user->role->value ?? $user->role) === $role->value)>{{ $role->value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="password" value="Password (opsional)" />
                                <x-text-input id="password" type="password" name="password" class="mt-1 block w-full" />
                            </div>
                            <div>
                                <x-input-label for="password_confirmation" value="Konfirmasi Password" />
                                <x-text-input id="password_confirmation" type="password" name="password_confirmation" class="mt-1 block w-full" />
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <x-primary-button>Update</x-primary-button>
                            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 hover:underline">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
