<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 leading-tight">Users</h2>
                <div class="text-sm text-slate-500 dark:text-slate-400">Kelola akun dan role. Admin utama tidak bisa diubah.</div>
            </div>
            <a href="{{ route('admin.users.create') }}" class="ss-link text-sm">Tambah</a>
        </div>
    </x-slot>

    <div class="ss-container">
        <x-flash />

        <div class="ss-card p-4 mb-4">
            <form class="grid grid-cols-1 sm:grid-cols-3 gap-3" method="GET">
                <div class="sm:col-span-2">
                    <x-input-label for="q" value="Cari" />
                    <x-text-input id="q" name="q" value="{{ request('q') }}" placeholder="Cari user (nama/email)..." class="mt-1 block w-full" />
                </div>
                <div class="flex items-end gap-2">
                    <x-primary-button type="submit">Cari</x-primary-button>
                    <a href="{{ route('admin.users.index') }}" class="ss-link text-sm">Reset</a>
                </div>
            </form>
            <div class="mt-3 text-xs text-slate-500 dark:text-slate-400">Menampilkan {{ $users->count() }} dari {{ $users->total() }} data.</div>
        </div>

        <div class="ss-table-wrap">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="ss-table">
                        <thead>
                            <tr>
                                <th class="ss-th">Nama</th>
                                <th class="ss-th">Email</th>
                                <th class="ss-th">Role</th>
                                <th class="ss-th w-44">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/70 dark:divide-slate-800/70">
                            @forelse ($users as $user)
                                @php
                                    $role = $user->role->value ?? (string) $user->role;
                                    $roleVariant = match ($role) {
                                        'admin' => 'info',
                                        'petugas' => 'success',
                                        'peminjam' => 'neutral',
                                        default => 'neutral',
                                    };
                                @endphp
                                <tr class="ss-tr">
                                    <td class="ss-td font-medium text-slate-900 dark:text-slate-100">{{ $user->name }}</td>
                                    <td class="ss-td">{{ $user->email }}</td>
                                    <td class="ss-td"><x-badge :variant="$roleVariant">{{ $role }}</x-badge></td>
                                    <td class="ss-td">
                                        @if($role === 'admin')
                                            <span class="text-xs text-slate-500">Tidak bisa diubah</span>
                                        @else
                                            <a href="{{ route('admin.users.edit', $user) }}" class="ss-link">Edit</a>
                                            <form class="inline" method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus user?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="ml-3 text-rose-600 hover:underline" type="submit">Hapus</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <x-empty-state title="Tidak ada user" description="Coba ubah kata kunci pencarian atau buat user baru.">
                                            <a href="{{ route('admin.users.create') }}" class="ss-link text-sm">Tambah user</a>
                                        </x-empty-state>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $users->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
