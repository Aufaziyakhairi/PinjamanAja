<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:create-admin', function () {
    $name = trim((string) $this->ask('Nama admin'));
    while ($name === '') {
        $this->error('Nama wajib diisi.');
        $name = trim((string) $this->ask('Nama admin'));
    }

    $email = trim(strtolower((string) $this->ask('Email admin')));
    while (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $this->error('Email tidak valid.');
        $email = trim(strtolower((string) $this->ask('Email admin')));
    }

    $existing = User::query()->where('email', $email)->first();
    if ($existing) {
        $this->warn("User dengan email {$email} sudah ada (role saat ini: ".($existing->role->value ?? $existing->role).')');

        if (!$this->confirm('Promosikan user ini menjadi admin?', true)) {
            $this->info('Batal.');
            return;
        }

        $existing->name = $name ?: $existing->name;
        $existing->role = UserRole::Admin;
        $existing->email_verified_at ??= now();
        $existing->save();

        $this->info('Berhasil: user dipromosikan menjadi admin.');
        return;
    }

    $password = (string) $this->secret('Password admin');
    $passwordConfirmation = (string) $this->secret('Konfirmasi password');

    while ($password === '' || $password !== $passwordConfirmation) {
        $this->error($password === '' ? 'Password wajib diisi.' : 'Konfirmasi password tidak sama.');
        $password = (string) $this->secret('Password admin');
        $passwordConfirmation = (string) $this->secret('Konfirmasi password');
    }

    User::query()->create([
        'name' => $name,
        'email' => $email,
        'password' => Hash::make($password),
        'role' => UserRole::Admin,
        'email_verified_at' => now(),
    ]);

    $this->info('Berhasil: admin dibuat.');
    $this->line("Login: {$email}");
})->purpose('Buat atau promosikan user menjadi Admin');
