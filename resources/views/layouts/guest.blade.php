<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        @php
            $maxWidthClass = match ($maxWidth ?? 'md') {
                'sm' => 'sm:max-w-sm',
                'md' => 'sm:max-w-md',
                'lg' => 'sm:max-w-lg',
                'xl' => 'sm:max-w-xl',
                '2xl' => 'sm:max-w-2xl',
                '3xl' => 'sm:max-w-3xl',
                '4xl' => 'sm:max-w-4xl',
                '5xl' => 'sm:max-w-5xl',
                '6xl' => 'sm:max-w-6xl',
                default => 'sm:max-w-md',
            };

            $shouldWrapInCard = ($card ?? true) === true;
        @endphp

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-10 sm:pt-0 bg-slate-50 dark:bg-slate-950">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute -top-32 left-1/2 -translate-x-1/2 h-72 w-72 rounded-full bg-sky-500/10 blur-3xl"></div>
                <div class="absolute top-32 left-1/3 h-72 w-72 rounded-full bg-indigo-500/10 blur-3xl"></div>
                <div class="absolute top-40 right-1/4 h-72 w-72 rounded-full bg-emerald-500/10 blur-3xl"></div>
            </div>

            <div class="relative z-10 flex flex-col items-center">
                <a href="/" class="flex items-center gap-3">
                    <x-application-logo class="w-12 h-12 fill-current text-indigo-600" />
                    <div class="leading-tight">
                        <div class="text-base font-semibold text-slate-900 dark:text-slate-100">SmartSchool</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">Peminjaman Alat</div>
                    </div>
                </a>
            </div>

            @if ($shouldWrapInCard)
                <div class="relative z-10 w-full {{ $maxWidthClass }} mt-6 px-6 py-5 ss-card">
                    {{ $slot }}
                </div>
            @else
                <div class="relative z-10 w-full {{ $maxWidthClass }} mt-8 px-4 sm:px-6">
                    {{ $slot }}
                </div>
            @endif
        </div>
    </body>
</html>
