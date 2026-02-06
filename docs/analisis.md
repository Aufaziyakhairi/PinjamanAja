# Analisis & Diagram — Aplikasi Peminjaman Alat

Dokumen ini menjelaskan alur proses dan pemetaan ERD ke implementasi (Laravel).

## Aktor & Hak Akses

- **Admin**
  - CRUD User
  - CRUD Alat
  - CRUD Kategori
  - CRUD Data Peminjaman
  - CRUD Pengembalian
  - Melihat Log Aktivitas
- **Petugas**
  - Menyetujui / menolak peminjaman
  - Memantau pengembalian (menerima / menolak)
  - Mencetak laporan
- **Peminjam**
  - Melihat daftar alat
  - Mengajukan peminjaman
  - Mengajukan pengembalian

Semua aktor: **Login** dan **Logout**.

## Alur Proses (sesuai flowchart)

### 1) Login & Validasi
1. Mulai → Login
2. Sistem melakukan validasi kredensial
3. Jika berhasil → Dashboard sesuai role (Admin/Petugas/Peminjam)
4. Jika gagal → kembali ke halaman login

### 2) Peminjaman (Peminjam + Persetujuan Petugas)
1. Peminjam login → Dashboard
2. Buka **Menu Peminjaman**
3. Peminjam dapat **melihat data alat** lalu **mengajukan peminjaman**
4. Status awal peminjaman: **pending**
5. Petugas login → Menu persetujuan → memilih peminjaman pending
6. Petugas **menyetujui** atau **menolak**
  - Disetujui: status menjadi **approved** dan alat menjadi **tidak tersedia** (sedang dipinjam)
  - Ditolak: status menjadi **rejected**

### 3) Pengembalian + Denda (Peminjam + Petugas)
1. Peminjam login → Dashboard
2. Buka **Menu Pengembalian**
3. Peminjam **mengajukan pengembalian** untuk peminjaman yang sudah disetujui
4. Petugas memantau pengembalian berstatus **requested**
5. Petugas memilih:
   - **Terima pengembalian**
     - Status pengembalian menjadi **received**
     - Status peminjaman menjadi **returned**
  - Alat kembali **tersedia**
     - Jika melewati jatuh tempo → **denda dihitung**
   - **Tolak pengembalian** → status pengembalian menjadi **rejected**

#### Aturan Denda
- Denda dihitung saat pengembalian **diterima**.
- Rumus:
  - `hari_telat = max(0, tanggal_diterima - due_at)` (dalam hari)
  - `denda = hari_telat × fine_per_day`
- Nilai `fine_per_day` ada di `config/loan.php` dan dapat diubah via env `LOAN_FINE_PER_DAY`.

### 4) Laporan
- Petugas dapat memfilter data peminjaman (tanggal & status), lalu membuka tampilan **print**.
- Laporan menampilkan kolom denda dan **total denda**.

## Pemetaan ERD → Tabel/Model

Berikut pemetaan umum dari ERD ke implementasi database:

- **User** (`users`)
  - Menyimpan akun dan role: `admin`, `petugas`, `peminjam`.
- **Kategori** (`categories`) ↔ **Alat** (`tools`)
  - 1 kategori memiliki banyak alat.
- **Peminjaman** (`loans`)
  - Relasi ke peminjam (`user_id`) dan petugas approver (`approved_by`).
  - Memiliki `status` (pending/approved/rejected/returned) dan `due_at` (jatuh tempo).
- **Detail Peminjaman** (`loan_items`)
  - Relasi many-to-one ke `loans` dan `tools` + `qty`.
- **Pengembalian** (`loan_returns`)
  - 1:1 dengan `loans` (unik per loan).
  - Menyimpan `requested_by`, `received_by`, `requested_at`, `received_at`.
  - Menyimpan denda: `fine_days`, `fine_amount`.
- **Log Aktivitas** (`activity_logs`)
  - Audit trail tindakan utama (create/update/approve/receive, dll).

## Catatan Implementasi
- Skema mendukung banyak item per peminjaman melalui `loan_items`.
- Data alat bersifat **per unit**: 1 baris di `tools` = 1 alat fisik, tanpa kolom stok dan tanpa kode.
- Ketersediaan alat ditentukan dari status peminjaman (`pending/approved` = tidak tersedia, `rejected/returned` = tersedia).
