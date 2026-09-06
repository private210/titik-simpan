# Titik Simpan — Budget Tracker

Aplikasi pencatat keuangan pribadi berbasis web. Kelola gaji bulanan, alokasikan dana per kategori, catat pengeluaran, pantau sisa saldo, dan buat pengeluaran berulang — semua dalam satu dashboard bento dengan tema gelap/terang dan dukungan dua bahasa (Indonesia / English US).

Tautan produksi: **[https://titik-simpan.vercel.app](https://titik-simpan.vercel.app)**

---

## Halaman Demo

Belum punya akun? Langsung coba aplikasinya tanpa daftar:

1. Klik tombol **"Coba Demo Sekarang"** di halaman beranda (landing page) atau **logo** di navbar.
2. Sistem otomatis masuk ke akun demo (`demo@titik-simpan.app`) berisi data contoh bulan berjalan: gaji, alokasi per kategori, pengeluaran, dan tagihan berulang.
3. Mode demo **hanya bisa untuk melihat/mencoba** — semua aksi simpan/edit/hapus (POST/PATCH/DELETE) diblokir oleh middleware `EnsureNotDemo`. Di navbar, akun demo menampilkan tombol **Login** (bukan avatar) agar mudah berpindah ke akun sendiri.

> Data demo dibuat otomatis saat `/demo` pertama kali diakses via seeder `DemoAccountSeeder` (data mengikuti bulan berjalan, siap pakai).

---

## Daftar Isi

- [Spesifikasi Teknologi](#spesifikasi-teknologi)
- [Fitur](#fitur)
- [Struktur Proyek](#struktur-proyek)
- [Skema Database](#skema-database)
- [Halaman Beranda (Landing Page)](#halaman-beranda-landing-page)
- [Instalasi Lokal](#instalasi-lokal)
- [Menjalankan](#menjalankan)
- [Penggunaan](#penggunaan)
- [Testing & Code Style](#testing--code-style)
- [Deployment (Vercel + Neon)](#deployment-vercel--neon)
- [Keamanan](#keamanan)
- [Lisensi](#lisensi)

---

## Spesifikasi Teknologi

| Bagian | Teknologi |
|---|---|
| Backend | Laravel 11 (PHP ^8.2) |
| Database | SQLite (lokal), PostgreSQL (produksi — Neon) |
| Template | Blade (`resources/views`) |
| CSS | Tailwind CSS via CDN (tanpa build step untuk CSS) |
| JavaScript | Vanilla JS + anime.js (animasi) + Chart.js (grafik laporan) |
| Autentikasi | Password (session) + Google OAuth (laravel/socialite) |
| Export | barryvdh/laravel-dompdf (PDF) + phpoffice/phpspreadsheet (Excel) |
| Deployment | Vercel (serverless PHP, `vercel-php`) + Neon PostgreSQL, auto-deploy dari push `master` |
| Lokalisasi | Indonesia (`id`) + English (`en`, US English), via `resources/lang/*` + middleware `SetLocale` |
| Tema | Light / Dark / Auto (ikut sistem) — tombol cycle manual, disimpan di `localStorage` |
| Lainnya | Multi-user isolation via `ScopedByUser` trait + global scope `user_id` |

## Fitur

- **Landing page** — promosi fitur, statistik, "Cara Kerja", FAQ accordion, dan form kontak (belum aktif, tertuju ke `pname210@gmail.com`).
- **Akun demo langsung** — akses satu klik tanpa daftar, data contoh bulan berjalan, mode read-only (`EnsureNotDemo` memblokir semua penulisan).
- **Dashboard bento** — sapaan + jam real-time WIB, 6 kartu statistik (Total Pendapatan, Pengeluaran Bulan Ini, Total Alokasi, Sudah Terpakai, Sisa Alokasi, Sisa Gaji), alokasi budget dengan progress bar, tagihan jatuh tempo (list scroll), pengeluaran terakhir (list scroll).
- **Kartu sapaan adaptif** — warna latar card berubah (hijau/amber/merah) mengikuti rasio pengeluaran terhadap pendapatan, dengan kutipan motivasi acak per kategori.
- **Gaji & Pendapatan Tambahan** — catat gaji bulan berjalan (unik per bulan) + pendapatan tambahan.
- **Alokasi Budget** — bagi dana ke kategori; `spent` otomatis bertambah saat pengeluaran dicatat.
- **Pengeluaran** — catat dengan kategori & keterangan; hasil dibagi 3 kartu (Total / Tetap / Lainnya); filter rentang bulan (`from`/`to`, otomatis ditukar bila `to < from`).
- **Pengeluaran Berulang** — frekuensi harian/mingguan/bulanan/tahunan, toggle aktif/nonaktif, tombol "Bayar" untuk menandai jatuh tempo terpenuhi.
- **Kategori** — CRUD (nama, ikon emoji, warna hex); kategori tidak bisa dihapus bila masih memiliki pengeluaran.
- **Laporan** — kartu ringkasan (Total Pendapatan, Sisa, Pengeluaran, Berulang, Lainnya), grafik per kategori & pengeluaran harian, pengeluaran terbesar, ringkasan harian, tabel tetap/lainnya (kolom Kategori), **export PDF & Excel** per bulan.
- **Reset Data** — reset seluruh data keuangan dengan konfirmasi wajib ketik `HAPUS` (kategori tetap tersimpan).
- **Profil** — ubah nama/email, upload avatar (base64), ganti password (rule StrongPassword), sinkron Google.
- **Dua bahasa** — Indonesia & English (US English), disimpan di session.
- **Tema 3 mode** — Terang/Gelap/Sistem; tombol **cycle** di navbar otomatis berganti urut setiap klik.
- **Multi-user isolation** — semua tabel bisnis punya `user_id` global scope; tiap akun hanya melihat datanya sendiri.

## Struktur Proyek

```
├── app/
│   ├── Http/Controllers/     # Dashboard, Budget, Expense, RecurringExpense, Category, Report, Profile, Auth
│   ├── Http/Middleware/      # SecurityHeaders, SetLocale, EnsureNotDemo (block aksi tulis demo)
│   ├── Models/               # Salary, Expense, RecurringExpense, BudgetAllocation, Category, User, AdditionalIncome
│   └── Models/Concerns/ScopedByUser.php   # Global scope user_id + auto-assign on creating
├── config/                   # app, database, session, dompdf
├── database/
│   ├── migrations/           # categories, salaries, budget_allocations, expenses, recurring_expenses, additional_incomes, google (users)
│   └── seeders/              # CategorySeeder, DemoAccountSeeder, AugDemoSeeder
├── public/assets/            # Logo, ikon, favicon (dilayani via route khusus di Vercel)
├── resources/
│   ├── lang/{id,en}/messages.php   # Terjemahan Indonesia & English (US)
│   └── views/                # layouts/app, landing, dashboard, budget, expenses, recurring, categories, reports, profile, auth, demo
├── routes/web.php            # Semua route (auth group = semua halaman aplikasi)
└── vercel.json               # Konfigurasi serverless (env, DOMpdf paths, dll)
```

## Skema Database

- `categories` — name, icon, color, is_default, user_id
- `salaries` — amount, received_at (unique), note, user_id
- `budget_allocations` — salary_id, category_id (unique pair), amount, spent, user_id
- `expenses` — category_id, budget_allocation_id (nullable), amount, description, spent_at, is_recurring, user_id
- `recurring_expenses` — category_id, name, amount, frequency (daily/weekly/monthly/yearly), next_due_date, is_active, user_id
- `additional_incomes` — amount, description, received_at, user_id
- `users` — name, email, password, google_id, avatar

## Halaman Beranda (Landing Page)

Rute `/` untuk pengunjung belum login menampilkan `landing.blade.php` (layout app + navbar guest), berisi:

- **Hero** — tagline, 3 CTA (`Coba Demo Sekarang` → `/demo`, `Daftar Gratis`, `Masuk`), statistik singkat.
- **Fitur Unggulan** — 6 kartu fitur.
- **Cara Kerja** — 3 langkah (Input Gaji → Alokasikan Dana → Catat & Pantau).
- **FAQ** — accordion 6 pertanyaan umum.
- **Form Kontak** — Nama/Email/Pesan, menuju `pname210@gmail.com`. **Belum aktif**: submit hanya me-reset form + alert, tidak mengirim email apa pun.

## Instalasi Lokal

Prasyarat: PHP ^8.2, Composer, Node.js (opsional untuk build Vite).

```bash
# 1. Clone & masuk direktori
git clone https://github.com/private210/titik-simpan.git
cd titik-simpan

# 2. Install dependensi
composer install
npm install          # hanya untuk tooling Vite; UI memakai CDN

# 3. Siapkan environment
cp .env.example .env
php artisan key:generate

# 4. Siapkan database (SQLite default)
php artisan migrate
php artisan db:seed --class=CategorySeeder
```

## Menjalankan

```bash
php artisan serve        # Backend → http://localhost:8000
npm run dev              # Vite HMR (opsional)
npm run build            # Opsional: menghasilkan public/build (tidak dipakai view)
```

Buka `http://localhost:8000` — pengunjung melihat **landing page**. Klik **"Coba Demo Sekarang"** untuk masuk akun demo, atau daftar/login dengan akun sendiri.

## Penggunaan

1. **Coba demo** (opsional) — klik "Coba Demo Sekarang"; jelajahi fitur dengan data contoh (read-only).
2. **Login/Register** — buat akun; semua halaman aplikasi memerlukan autentikasi. Google login aktif bila `GOOGLE_CLIENT_ID/SECRET/REDIRECT_URI` diisi.
3. **Masukkan gaji** — halaman Budget, isi nominal gaji bulan berjalan lalu alokasikan ke kategori (mis. Makanan, Transport, Tabungan).
4. **Catat pengeluaran** — halaman Pengeluaran → Tambah; pilih kategori (yang sudah dialokasikan otomatis mengurangi sisa alokasi kategori).
5. **Pantau dashboard** — sisa saldo bulanan, alokasi per kategori, pengeluaran terakhir, dan tagihan berulang yang jatuh tempo.
6. **Laporan** — lihat grafik pengeluaran bulanan/per kategori; klik **PDF** atau **Excel** untuk mengunduh laporan pengeluaran bulan terpilih.
7. **Atur pengeluaran berulang** — untuk tagihan rutin; muncul di dashboard saat `next_due_date` tiba; tandai "Bayar" setelah terpenuhi.
8. **Ganti bahasa/tema** — tombol bahasa (ID/EN) & tombol tema cycle (Auto → Terang → Gelap) di navbar.
9. **Reset data** — tombol merah "Reset Data" di kanan atas Dashboard, ketik `HAPUS` untuk konfirmasi.

## Testing & Code Style

```bash
vendor/bin/phpunit                    # Semua test (SQLite default, tanpa RefreshDatabase)
vendor/bin/pint                       # Auto-fix style (Laravel preset)
vendor/bin/pint --test                # Dry run
```

## Deployment (Vercel + Neon)

Produksi berjalan di **Vercel + Neon PostgreSQL** (`https://titik-simpan.vercel.app`), auto-deploy dari push ke branch `master`. Tidak ada script deploy — push saja.

### Env vars di dashboard Vercel

| Variabel | Nilai |
|---|---|
| `DB_URL` | URL Neon **direct host** (mis. `postgresql://user:pass@ep-xxx.c-3.ap-southeast-1.aws.neon.tech:5432/neondb?sslmode=require`) — **jangan** gunakan host `-pooler` (gagal di transaksi multi-statement) |
| `APP_KEY` | Dari `php artisan key:generate` lokal |
| `APP_URL` | `https://titik-simpan.vercel.app` |
| `DEMO_SEED_TOKEN` | (opsional) token untuk route `/seed-demo` manual |

`vercel.json` sudah menyetel env serverless: `SESSION_DRIVER=cookie`, `SESSION_SECURE_COOKIE=true`, `CACHE_STORE=array`, `QUEUE_CONNECTION=sync`, `LOG_CHANNEL=stderr`, cache di `/tmp`, `DB_CONNECTION=pgsql`, `DB_SSLMODE=require`, path dompdf ke `/tmp`.

### Catatan deployment

- **Migrasi tidak berjalan otomatis di Vercel** — jalankan SQL migrasi baru secara manual di console Neon.
- **Akun demo** dibuat otomatis saat `/demo` pertama diakses (`DemoAccountSeeder`).
- **Asset statis** (`public/`) tidak diserve otomatis — setiap file baru harus didaftarkan di loop `$asset` pada `routes/web.php` (`logo.svg`, `favicon.ico`, `icon-*`, `logo-*`).
- Cold start serverless lambat di free plan — loading bar & splash mobile menutupinya.

## Keamanan

- Semua route aplikasi dilindungi middleware `auth`; login di-rate-limit (`throttle:login`, 10/menit/IP).
- `SecurityHeaders` middleware: `X-Frame-Options: DENY`, `nosniff`, `Referrer-Policy`, `Permissions-Policy`.
- `SetLocale` middleware membaca session untuk lokalisasi (setelah session di-start, bukan di boot).
- `EnsureNotDemo` memblokir semua aksi tulis akun demo (read-only).
- Semua form memakai `@csrf`; data chart laporan di-escape dengan `JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT` (anti stored XSS).
- Data terisolasi per pengguna lewat global scope `user_id` (`ScopedByUser` trait).
- File sensitif (`.env`, `.env.production`) ada di `.gitignore` — jangan commit nilai asli; gunakan `.env.production.example` sebagai template placeholder.

## Lisensi

Proyek pribadi — MIT.