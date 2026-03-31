# Refactor UI/UX Admin Dashboard dengan Template Metronic

## Ringkasan

Melakukan refactor menyeluruh UI/UX halaman admin menggunakan template Metronic 8 Demo 10. Perubahan mencakup sidebar navigasi, header, footer, dan komponen-komponen UI yang disesuaikan dengan kebutuhan aplikasi buku tamu.

## Pendekatan

**Approach B (Full Refactor)** — Refactor menyeluruh dengan penyesuaian kebutuhan aplikasi buku tamu. Mengadopsi struktur dan komponen Metronic, tetapi menghapus menu/fitur yang tidak relevan.

## Struktur Target

```
Metronic Layout
├── Sidebar (Aside)
│   ├── Logo / Brand
│   ├── Menu Navigasi (4 item)
│   └── User Footer Panel
├── Header
│   ├── Search Bar
│   ├── User Menu Dropdown
│   └── Toolbar
├── Content Area
│   └── Halaman: Dashboard / Tamu / Pengunjung / Laporan
└── Footer
    └── Copyright
```

## File yang Dibuat/Diubah

### 1. `app/Views/layouts/metronic.php` (Update)
- Tambahkan sidebar Metronic
- Tambahkan header lengkap
- Tambahkan footer
- Struktur: Aside → Wrapper → Header → Content → Footer

### 2. `app/Views/partials/sidebar_admin.php` (New)
- Menu: Dashboard, Tamu, Pengunjung, Laporan
- Icon Metronic (`ki-duotone`)
- Active state berdasarkan URL
- User avatar + info di footer

### 3. `app/Views/partials/header_admin.php` (Update)
- Logo "Buku Tamu"
- User menu dropdown dengan logout

### 4. `app/Views/partials/footer_admin.php` (New)
- Footer minimal dengan copyright "Buku Tamu"

### 5. `app/Views/admin/dashboard.php` (Update)
- Stat cards: Hari Ini, Bulan Ini, Tahun Ini
- Widget: Total Pengunjung, Total Tamu
- Card besar: Total Keseluruhan

### 6. `app/Views/admin/tamu_list.php` (Update)
- Card Metronic untuk tabel
- Modal form (sudah ada, tetap dipertahankan)
- Styling Metronic untuk buttons dan table

### 7. `app/Views/admin/pengunjung_list.php` (Update)
- Sama seperti tamu_list

### 8. `app/Views/admin/laporan.php` (Update)
- Chart dengan card Metronic
- Tabel dengan styling Metronic
- Filter form

## Menu Navigasi

| Label | Icon | Route |
|-------|------|-------|
| Dashboard | `ki-element-11` | /admin |
| Tamu | `ki-people` | /admin/tamu |
| Pengunjung | `ki-user` | /admin/pengunjung |
| Laporan | `ki-chart` | /admin/laporan |

## Elemen Metronic yang Diadopsi

| Elemen | Komponen Metronic |
|--------|-------------------|
| Sidebar | `kt_aside`, `aside-menu`, `menu menu-column` |
| Header | `kt_header`, `header-menu` |
| Stat Cards | `card card-xl-stretch`, `symbol symbol-circle` |
| Table | `table align-middle table-row-dashed fs-6 gy-5` |
| Buttons | `btn btn-primary`, `btn btn-light-danger` |
| Modal | `modal fade`, `modal-dialog modal-dialog-centered` |
| Badges | `badge badge-light-primary` |
| Toast | SweetAlert2 (sudah ada) |

## Elemen Template yang Dihapus/Sederhanakan

- Semua sub-menu yang tidak relevan (Projects, eCommerce, dll)
- Search bar (opsional, bisa ditambahkan nanti)
- Notification panel
- User menu items yang tidak perlu (billing, subscription)
- Demo avatars/images — menggunakan placeholder/icon

## Assets yang Digunakan

```
public/assets/plugins/global/plugins.bundle.css
public/assets/plugins/global/plugins.bundle.js
public/assets/css/style.bundle.css
public/assets/plugins/custom/datatables/datatables.bundle.css
public/assets/plugins/custom/datatables/datatables.bundle.js
```

(Semua assets sudah ada di project, dari template Metronic yang sudah di-copy)

## Urutan Implementasi

1. `sidebar_admin.php` (new)
2. `footer_admin.php` (new)
3. `header_admin.php` (update)
4. `metronic.php` (update)
5. `dashboard.php` (update)
6. `tamu_list.php` (update)
7. `pengunjung_list.php` (update)
8. `laporan.php` (update)

## Estimasi Effort

Medium — 8 file, perubahan主要集中在 layout dan styling. Tidak ada perubahan logic PHP.
