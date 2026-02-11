<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 leading-tight">Tambah Alat</h2>
            <div class="text-sm text-slate-500 dark:text-slate-400">Isi data alat dan kategorinya.</div>
        </div>
    </x-slot>

    <div class="ss-container">
        <div class="max-w-3xl mx-auto">
            <x-flash />
            <div class="ss-card">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.tools.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="category_id" value="Kategori" />
                            <select id="category_id" name="category_id" class="ss-input mt-1" required>
                                <option value="">-- pilih --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="name" value="Nama" />
                            <x-text-input id="name" name="name" value="{{ old('name') }}" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="description" value="Deskripsi" />
                            <textarea id="description" name="description" class="ss-input mt-1" rows="4">{{ old('description') }}</textarea>
                            <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">Opsional. Jelaskan fungsi/kondisi alat.</div>
                        </div>
                        <div class="flex gap-3">
                            <x-primary-button>Simpan</x-primary-button>
                            <a href="{{ route('admin.tools.index') }}" class="ss-link text-sm">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
