<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100">
            Lupa Password
        </h1>
        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
            Masukkan email kamu, lalu kami kirimkan tautan untuk reset password.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
