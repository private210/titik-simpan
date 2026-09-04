# Titik Simpan — Changelog & Improvement Tracker

> Dokumentasi perbaikan & fitur baru — September 2026

---

## Daftar Perbaikan yang Sudah Diimplementasi

### 1. Persistensi Gaji 30 Hari
**Status: ✅ Selesai**
- `Salary::scopeCurrentMonth()` diubah dari filter kalender bulan ke filter 30 hari dari `received_at`
- Gaji tetap aktif meskipun sudah berganti bulan selama masih dalam 30 hari
- Setelah 30 hari, sistem akan otomatis mencari gaji baru (reset period)
- Manual edit tetap dipertahankan
- **File:** `app/Models/Salary.php`

### 2. Dashboard — Total Pendapatan & Sisa Gaji
**Status: ✅ Selesai**
- Stat card menampilkan Total Pendapatan (gaji + pendapatan tambahan)
- Stat card menampilkan Sisa Gaji dari hasil pengeluaran & alokasi
- `additional_incomes` table baru ditambahkan
- **File:** `app/Http/Controllers/DashboardController.php`, `dashboard.blade.php`, migration

### 3. Greeting & Motivasi — Swap Ukuran + Warna Kondisi
**Status: ✅ Selesai**
- Greeting diganti lebih kecil (`text-xs/text-sm`, warna `text-white/70`)
- Motivasi diganti lebih besar (`text-sm/md:text-base`) dengan background highlight
- Warna motivasi berdasarkan rasio pengeluaran:
  - **>50% gaji**: Merah — motivasi kesadaran & kewaspadaan
  - **>25% gaji**: Kuning — motivasi hati-hati
  - **<25% gaji**: Hijau/default — motivasi positif normal
- **File:** `app/Http/Controllers/DashboardController.php`, `dashboard.blade.php`

### 4. Tagihan Jatuh Tempo — Perbaikan Teks Kategori
**Status: ✅ Selesai**
- Menghapus teks tambahan yang tidak wajar dari kategori
- Hanya menampilkan nama kategori dan jumlah tagihan
- **File:** `dashboard.blade.php` (section due bills)

### 5. Budget — Pendapatan Tambahan di Luar Gaji
**Status: ✅ Selesai**
- Tabel `additional_incomes` baru: `id`, `user_id`, `amount`, `description`, `received_at`
- Model `AdditionalIncome` dengan `ScopedByUser` trait
- Form tambah pendapatan tambahan di halaman budget
- Daftar pendapatan tambahan bulan ini ditampilkan
- Berpengaruh pada Total Pendapatan di dashboard
- **File:** `app/Models/AdditionalIncome.php`, `app/Http/Controllers/BudgetController.php`, `budget/index.blade.php`, migration

### 6. Pengeluaran Berulang — Edit + Frekuensi Harian
**Status: ✅ Selesai**
- Tombol "Edit" ditambahkan untuk setiap item di daftar berulang
- Halaman edit baru (`recurring/edit.blade.php`) — edit nama pengeluaran
- Frekuensi baru: `daily` (Harian) ditambahkan ke form dan display
- Route `recurring.edit` ditambahkan
- **File:** `app/Http/Controllers/RecurringExpenseController.php`, `resources/views/recurring/edit.blade.php`, `recurring/index.blade.php`, `recurring/create.blade.php`, `routes/web.php`

### 7. Laporan — Pengeluaran Tetap vs Lainnya
**Status: ✅ Selesai**
- Stat card laporan: 4 card (Gaji, Total Pengeluaran, Tetap/Berulang, Sisa)
- 2 komponen tabel terpisah:
  - **Pengeluaran Tetap/Berulang** — hanya `is_recurring = true`
  - **Pengeluaran Lainnya** — hanya `is_recurring = false`
- Keduanya berupa tabel scrollable dengan fixed height
- **File:** `app/Http/Controllers/ReportController.php`, `reports/index.blade.php`

### 8. Dashboard — Bento Layout
**Status: ✅ Selesai**
- Layout 3 grid: kiri atas (stat cards + alokasi), kiri bawah (tagihan jatuh tempo), kanan (riwayat pengeluaran)
- Desktop: 2 kolom kiri (2/3) + 1 kolom kanan (1/3)
- Mobile: stack vertikal
- Riwayat pengeluaran di kanan berupa tabel scrollable dengan `max-h-[360px]`
- **File:** `dashboard.blade.php`

### 9. Pengeluaran — 3 Total
**Status: ✅ Selesai**
- Ringkasan pengeluaran menampilkan 3 angka:
  - **Total Pengeluaran**
  - **Pengeluaran Tetap** (recurring)
  - **Pengeluaran Lainnya** (non-recurring)
- **File:** `app/Http/Controllers/ExpenseController.php`, `expenses/index.blade.php`

### 10. Multi-Bahasa (ID & EN)
**Status: ✅ Selesai**
- Default locale: `id` (Indonesia) sebagai bahasa utama
- `resources/lang/id/messages.php` — terjemahan lengkap Bahasa Indonesia
- `resources/lang/en/messages.php` — terjemahan lengkap Bahasa Inggris
- Language switcher di navbar (dropdown 🇮🇩/🇬🇧)
- Route `GET /lang/{locale}` untuk switch bahasa
- Locale disimpan di session
- **File:** `config/app.php`, `app/Providers/AppServiceProvider.php`, `resources/lang/`, `routes/web.php`, `layouts/app.blade.php`

### 11. Footer — Ikon Theme-Aware
**Status: ✅ Selesai**
- Light mode: `icon-light.svg`
- Dark mode: `icon-dark.svg`
- Ikon footer diupdate otomatis saat tema berubah
- **File:** `layouts/app.blade.php`

### 12. Favicon — icon-dark.svg
**Status: ✅ Selesai**
- Favicon menggunakan `icon-dark.svg`
- **File:** `layouts/app.blade.php`

### 13. Loading Screen Mobile
**Status: ✅ Selesai**
- Overlay loading screen hanya muncul di mobile (≤768px)
- Hanya ditampilkan sekali per sesi (`sessionStorage.ml`)
- Logo Titik Simpan + progress bar animasi
- Menghilang otomatis setelah page load
- **File:** `layouts/app.blade.php`

### 14. Halaman Demo
**Status: ✅ Selesai**
- Route `GET /demo` — halaman demo statis (tanpa auth)
- Menampilkan data contoh (gaji, pengeluaran, alokasi, sisa)
- Menampilkan daftar fitur yang tersedia
- Tombol "Daftar Sekarang" dan "Masuk" mengarah ke halaman auth
- **File:** `resources/views/demo.blade.php`, `routes/web.php`

---

## File yang Dimodifikasi

| File | Perubahan |
|------|-----------|
| `app/Models/Salary.php` | Scope 30 hari |
| `app/Models/AdditionalIncome.php` | **BARU** — model pendapatan tambahan |
| `app/Http/Controllers/DashboardController.php` | Total income, motivasi berwarna, bento data |
| `app/Http/Controllers/BudgetController.php` | Store additional income |
| `app/Http/Controllers/ExpenseController.php` | Split recurring vs non-recurring totals |
| `app/Http/Controllers/ReportController.php` | Total recurring, view data |
| `app/Http/Controllers/RecurringExpenseController.php` | Edit method, daily frequency |
| `app/Providers/AppServiceProvider.php` | Locale from session |
| `config/app.php` | Default locale `id` |
| `routes/web.php` | Language switch, demo page, budget additional, recurring edit |
| `database/migrations/*_create_additional_incomes_table.php` | **BARU** — migration |
| `resources/lang/id/messages.php` | **BARU** — terjemahan ID |
| `resources/lang/en/messages.php` | **BARU** — terjemahan EN |
| `resources/views/layouts/app.blade.php` | Favicon, footer icons, lang switcher, mobile loading |
| `resources/views/dashboard.blade.php` | Bento layout, motivasi, greeting swap, due bills fix |
| `resources/views/budget/index.blade.php` | Pendapatan tambahan |
| `resources/views/expenses/index.blade.php` | 3 total pengeluaran |
| `resources/views/recurring/index.blade.php` | Tombol edit, frekuensi harian |
| `resources/views/recurring/create.blade.php` | Frekuensi harian |
| `resources/views/recurring/edit.blade.php` | **BARU** — edit recurring |
| `resources/views/reports/index.blade.php` | Split recurring vs non-recurring |
| `resources/views/demo.blade.php` | **BARU** — halaman demo |

---

## Database Changes

### Tabel Baru: `additional_incomes`
```sql
- id (bigint, PK)
- user_id (bigint, FK → users.id)
- amount (decimal 15,2)
- description (varchar 255)
- received_at (date)
- timestamps
```

---

## Catatan Teknis

- **Salary 30 Hari**: `where('received_at', '<=', today)->where('received_at', '>=', today - 30 days)`
- **Motivasi Warna**: Rasio = totalMonthlyExpenses / totalIncome × 100
  - >50% → merah (danger quotes)
  - >25% → kuning (caution quotes)
  - ≤25% → default hijau (safe quotes)
- **Bento Layout**: `grid grid-cols-1 lg:grid-cols-3`, kiri `lg:col-span-2`
- **Multi-bahasa**: Session-based locale, switch via `/lang/{locale}`
- **Mobile Loading**: Hanya pertama kali per sesi (`sessionStorage.ml`)
- **Footer**: Theme-aware via `applyTheme()` JS — update `#footer-logo` src
