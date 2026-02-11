@if (session('status'))
    <div x-data="{ show: true }" x-show="show" x-transition class="mb-4 ss-card border-emerald-200/70 bg-emerald-50/70 p-4 dark:border-emerald-900/40 dark:bg-emerald-950/25">
        <div class="flex items-start gap-3">
            <div class="mt-0.5 text-emerald-700 dark:text-emerald-200">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.06l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="flex-1">
                <div class="text-sm font-semibold text-emerald-900 dark:text-emerald-100">Berhasil</div>
                <div class="mt-0.5 text-sm text-emerald-800 dark:text-emerald-200">{{ session('status') }}</div>
            </div>
            <button type="button" @click="show = false" class="rounded-lg p-1 text-emerald-700/70 hover:bg-emerald-100/60 hover:text-emerald-900 dark:text-emerald-200/70 dark:hover:bg-emerald-900/30 dark:hover:text-emerald-100" aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                </svg>
            </button>
        </div>
    </div>
@endif

@if ($errors->any())
    <div x-data="{ show: true }" x-show="show" x-transition class="mb-4 ss-card border-rose-200/70 bg-rose-50/70 p-4 dark:border-rose-900/40 dark:bg-rose-950/25">
        <div class="flex items-start gap-3">
            <div class="mt-0.5 text-rose-700 dark:text-rose-200">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9 7a1 1 0 112 0v4a1 1 0 11-2 0V7zm1 8a1.25 1.25 0 100-2.5A1.25 1.25 0 0010 15z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="flex-1">
                <div class="text-sm font-semibold text-rose-900 dark:text-rose-100">Periksa lagi</div>
                <ul class="mt-1 list-disc space-y-0.5 pl-5 text-sm text-rose-800 dark:text-rose-200">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" @click="show = false" class="rounded-lg p-1 text-rose-700/70 hover:bg-rose-100/60 hover:text-rose-900 dark:text-rose-200/70 dark:hover:bg-rose-900/30 dark:hover:text-rose-100" aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                </svg>
            </button>
        </div>
    </div>
@endif
