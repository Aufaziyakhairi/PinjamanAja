@props([
    'title' => 'Tidak ada data',
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'py-10 text-center']) }}>
    <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-slate-700 dark:bg-slate-800/60 dark:text-slate-200">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
            <path fill-rule="evenodd" d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm0 2h10a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1V7a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
    </div>
    <div class="mt-3 text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $title }}</div>
    @if($description)
        <div class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $description }}</div>
    @endif
    @if(trim($slot) !== '')
        <div class="mt-4 flex items-center justify-center gap-2">{{ $slot }}</div>
    @endif
</div>
