# Aplikasi Peminjaman Alat

Laravel 12 + Breeze (Blade/Tailwind) untuk aplikasi peminjaman alat dengan 3 role:

- `admin`: kelola master data (user/kategori/alat), monitoring, activity log
- `petugas`: approval peminjaman, monitoring pengembalian, cetak laporan
- `peminjam`: lihat katalog alat, ajukan pinjam & ajukan pengembalian

## Setup cepat

1) Install dependency

- `composer install`
- `npm install`

2) Copy env dan generate key

- `cp .env.example .env`
- `php artisan key:generate`

3) Migrasi + seed akun default

- `php artisan migrate --seed`

4) Build asset

- `npm run dev` (atau `npm run build`)

## Akun default (dari seeder)

Seeder membuat/menjaga 3 akun ini (lihat `database/seeders/DatabaseSeeder.php`):

- Admin: `admin@example.com` / password: `password`
- Petugas: `petugas@example.com` / password: `password`
- Peminjam: `peminjam@example.com` / password: `password`

## Kenapa tidak bisa membuat admin dari Register?

Halaman Register memang **selalu membuat role `peminjam`** untuk keamanan.

Untuk membuat admin:

- Admin → Users hanya untuk membuat/mengelola `petugas` dan `peminjam`.
- Admin dibuat lewat seeder/CLI (akun default sudah ada), **atau**
- Jalankan perintah ini untuk membuat/promosikan admin lewat CLI:

`php artisan app:create-admin`
