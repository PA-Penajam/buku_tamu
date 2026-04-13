# Design Spec: Metronic 8 UI/UX Overhaul

**Tanggal:** 2026-04-13
**Status:** Approved
**Scope:** Semua area UI/UX — halaman publik dan admin

## Visi

Membuat UI/UX yang **interaktif dan informatif** untuk dua audiens utama:
- **Tamu/Pengunjung** — pengalaman pengisian form yang menyenangkan dan informatif
- **Petugas/Admin** — dashboard yang kaya data untuk pengambilan keputusan cepat

## Prinsip Desain

1. **Mobile-first** — tamu umumnya mengisi form via HP/tablet di front-desk
2. **Konsistensi Metronic 8** — semua icon menggunakan `ki-duotone ki-*`, tidak ada Bootstrap Icons
3. **ApexCharts dari bundle** — tidak ada chart library via CDN
4. **Real-time feedback** — validasi inline, toast notification, loading states

---

## 1. Cleanup

### 1.1 Hapus file yang tidak terpakai

| File | Alasan |
|---|---|
| `app/Views/layouts/main.php` | Bootstrap plain, sudah digantikan `layouts/metronic.php` |
| `app/Views/partials/navbar_admin.php` | Bootstrap Icons, sudah digantikan `partials/header_admin.php` |

### 1.2 Verifikasi tidak ada referensi

Pastikan tidak ada view yang masih `extend('layouts/main')` atau `view('partials/navbar_admin')` sebelum menghapus.

---

## 2. Homepage Publik

**File:** `app/Views/home/index.php`

### 2.1 Info Strip

Tambahkan info bar di atas card pilihan:
- Jam operasional kantor
- Status kantor (buka/tutup) berdasarkan jam server
- Live counter: jumlah pengunjung hari ini

### 2.2 Card Pilihan yang Lebih Hidup

- Hover animation: scale(1.03) + box-shadow transition
- Icon pulse animation pada hover
- Gradient background halus pada card

### 2.3 Endpoint API Baru

**Route:** `GET /api/stats/today` (publik, tidak perlu auth)
**Response:**
```json
{
  "total_hari_ini": 23,
  "status_kantor": "buka",
  "jam_operasional": "08:00 - 16:00"
}
```

---

## 3. Form Pendaftaran

**File:** `app/Views/tamu/form.php`

### 3.1 Stepper Indicator

Tambahkan Metronic stepper di atas form:
- Langkah 1: Ambil Foto Wajah
- Langkah 2: Data Diri (nama, alamat/instansi, HP)
- Langkah 3: Tujuan Kunjungan

Stepper bersifat visual indicator saja (bukan multi-page form) — form tetap satu halaman scroll, stepper menunjukkan posisi scroll saat ini.

### 3.2 Optimasi Kamera Mobile

- Overlay guide berbentuk oval di atas video feed
- Countdown timer 3-2-1 sebelum capture
- Fallback yang lebih baik jika kamera tidak tersedia (upload file)

### 3.3 Real-time Validation

- Inline feedback saat mengetik: centang hijau untuk valid, silang merah untuk invalid
- Telepon: format otomatis saat mengetik
- Nama: minimal 3 karakter

### 3.4 Success Page

Setelah submit berhasil, redirect ke halaman konfirmasi (`/tamu/sukses`) yang menampilkan:
- Nomor antrian (increment dari counter hari ini)
- Ringkasan data yang diisi
- Estimasi waktu tunggu
- Tombol "Kembali ke Beranda"

**Route baru:** `GET /tamu/sukses`
**Controller:** `TamuController::sukses()`

---

## 4. Admin Dashboard

**File:** `app/Views/admin/dashboard.php`

### 4.1 Stat Cards dengan Trend Indicator

Setiap stat card menampilkan:
- Angka utama (besar)
- Badge trend: panah naik/turun + persentase dibanding periode sebelumnya
- Icon dan warna sesuai jenis

### 4.2 ApexCharts Tren Kunjungan

**Chart type:** Mixed (bar + line)
- Bar: pengunjung per hari (7 hari terakhir)
- Line: tamu per hari (7 hari terakhir)
- Tooltip interaktif, responsive

**Konfigurasi:**
- Menggunakan CSS variables dari Metronic (`--kt-primary`, `--kt-success`, dll)
- `KTUtil.getCssVariableValue()` untuk warna
- Height: 350px, responsive

### 4.3 Mini Widget: Kunjungan Terakhir

Menampilkan 3-5 kunjungan terakhir di card kecil:
- Foto thumbnail (40x40px)
- Nama + jenis badge (pengunjung/tamu)
- Waktu kunjungan (relative: "5 menit yang lalu")
- Link "Lihat Semua" ke daftar tamu/pengunjung

### 4.4 Quick Action Buttons

Tombol akses cepat di dashboard:
- "Tambah Tamu" → modal form
- "Tambah Pengunjung" → modal form
- "Export Laporan" → link ke halaman laporan

### 4.5 Endpoint API Baru

**Route:** `GET /admin/api/trend` (dalam group admin, perlu auth)
**Response:**
```json
{
  "labels": ["07/04", "08/04", "09/04", "10/04", "11/04", "12/04", "13/04"],
  "pengunjung": [5, 8, 3, 12, 7, 9, 11],
  "tamu": [2, 4, 1, 6, 3, 5, 4],
  "trend": {
    "hari_ini_vs_kemarin": "+15%",
    "minggu_ini_vs_minggu_lalu": "+8%"
  }
}
```

**Route:** `GET /admin/api/kunjungan-terakhir`
**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "nama": "Budi Santoso",
      "jenis_tamu": "pengunjung",
      "foto": "budi_20260413.jpg",
      "created_at": "2026-04-13 09:30:00"
    }
  ]
}
```

---

## 5. Daftar Tamu & Pengunjung

**File:** `app/Views/admin/tamu_list.php`, `app/Views/admin/pengunjung_list.php`

### 5.1 Individual Column Search

Tambahkan input search per kolom di bawah header tabel menggunakan DataTables `columns.searchable` + footer input.

### 5.2 Bulk Actions

- Checkbox per row
- Dropdown "Aksi Massal": Hapus yang Dipilih
- Konfirmasi SweetAlert2 sebelum eksekusi

### 5.3 Slide-in Detail Panel

Klik baris → buka drawer/slide-in panel dari kanan yang menampilkan:
- Foto besar
- Semua data lengkap
- Tombol Edit dan Hapus

Menggunakan Metronic drawer component (`data-kt-drawer`).

---

## 6. Laporan

**File:** `app/Views/admin/laporan.php`

### 6.1 Migrasi ke ApexCharts

- Hapus `<script src="chart.js CDN">`
- Gunakan ApexCharts dari Metronic bundle (sudah termasuk di `plugins.bundle.js`)
- Chart type: Mixed bar + line (sama pola dengan dashboard tapi untuk periode yang dipilih)

### 6.2 Pie/Donut Chart

Tambahkan donut chart distribusi pengunjung vs tamu di samping bar chart.

### 6.3 Summary Cards

Di atas chart, tambahkan 3 mini cards:
- Total kunjungan periode ini
- Rata-rata per hari
- Hari puncak kunjungan

---

## 7. Layout & Asset

### 7.1 Layout Metronic

**File:** `app/Views/layouts/metronic.php`

Tambahkan ApexCharts JS bundle:
```php
<?php if ($is_admin ?? false): ?>
<script src="<?= base_url('assets/plugins/custom/datatables/datatables.bundle.js') ?>"></script>
<script src="<?= base_url('assets/plugins/custom/apexcharts/apexcharts.min.js') ?>"></script>
<?php endif; ?>
```

### 7.2 Breadcrumb

Tambahkan breadcrumb component di admin pages menggunakan Metronic breadcrumb class:
```html
<div class="d-flex flex-column flex-column-fluid">
    <div class="app-toolbar py-3 py-lg-6">
        <div class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center me-10">Dashboard</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted"><a href="/" class="text-muted text-hover-primary">Home</a></li>
                    <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-5px"></span></li>
                    <li class="breadcrumb-item text-muted">Admin</li>
                    <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-5px"></span></li>
                    <li class="breadcrumb-item text-gray-500">Dashboard</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="app-content flex-column-fluid">
        <!-- content -->
    </div>
</div>
```

---

## Ringkasan File yang Berubah

| Aksi | File |
|---|---|
| Hapus | `app/Views/layouts/main.php` |
| Hapus | `app/Views/partials/navbar_admin.php` |
| Edit | `app/Views/layouts/metronic.php` |
| Edit | `app/Views/home/index.php` |
| Edit | `app/Views/tamu/form.php` |
| Buat | `app/Views/tamu/sukses.php` |
| Edit | `app/Views/auth/login.php` (minor polish) |
| Edit | `app/Views/admin/dashboard.php` |
| Edit | `app/Views/admin/tamu_list.php` |
| Edit | `app/Views/admin/pengunjung_list.php` |
| Edit | `app/Views/admin/laporan.php` |
| Edit | `app/Controllers/AdminController.php` |
| Edit | `app/Controllers/TamuController.php` |
| Edit | `app/Config/Routes.php` |

## Yang TIDAK Berubah

- Database schema
- Model (TamuModel)
- AuthFilter
- Autentikasi flow
- Sidebar & header admin (sudah baik)
- Footer admin
