# Metronic 8 UI/UX Overhaul — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Membuat UI/UX yang interaktif dan informatif untuk tamu/pengunjung dan petugas admin menggunakan Metronic 8.

**Architecture:** Perbaikan menyeluruh di semua halaman — publik (homepage, form, login) dan admin (dashboard, lists, laporan). Backend hanya menambahkan API endpoint baru untuk data dinamis. Semua chart menggunakan ApexCharts dari Metronic bundle (sudah termasuk di `plugins.bundle.js`).

**Tech Stack:** PHP 8.2, CodeIgniter 4.7, Metronic 8 (HTML/JS/CSS), ApexCharts (via bundle), SweetAlert2, DataTables

**Design Spec:** `docs/superpowers/specs/2026-04-13-metronic-uiux-overhaul-design.md`

---

### Task 1: Cleanup — Hapus File Tidak Terpakai

**Files:**
- Delete: `app/Views/layouts/main.php`
- Delete: `app/Views/partials/navbar_admin.php`

- [ ] **Step 1: Verifikasi tidak ada referensi ke file yang akan dihapus**

```bash
grep -rn "layouts/main\|navbar_admin" app/Views/ app/Controllers/
```

Expected: Tidak ada output (tidak ada file yang mereferensikan kedua file tersebut)

- [ ] **Step 2: Hapus kedua file**

```bash
rm app/Views/layouts/main.php app/Views/partials/navbar_admin.php
```

- [ ] **Step 3: Commit**

```bash
git add -u
git commit -m "chore: remove unused layout and navbar files

Deleted layouts/main.php (Bootstrap plain, unused) and
partials/navbar_admin.php (Bootstrap Icons, replaced by header_admin.php)"
```

---

### Task 2: Layout Metronic — Tambahkan Breadcrumb Structure

**Files:**
- Modify: `app/Views/layouts/metronic.php`

- [ ] **Step 1: Update layout metronic untuk mendukung breadcrumb section**

Ganti konten `kt_content` div agar mendukung app-toolbar breadcrumb di admin pages. Tambahkan section `breadcrumb` baru.

Di file `app/Views/layouts/metronic.php`, ganti blok content:

```php
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <?= $this->renderSection('breadcrumb') ?>
    <?= $this->renderSection('content') ?>
</div>
<!--end::Content-->
```

- [ ] **Step 2: Verifikasi homepage masih render normal**

Run: `php spark serve` (di background), buka `http://localhost:8080`
Expected: Homepage tampil normal tanpa breadcrumb (karena tidak ada section breadcrumb)

- [ ] **Step 3: Commit**

```bash
git add app/Views/layouts/metronic.php
git commit -m "refactor(layout): add breadcrumb section to metronic layout"
```

---

### Task 3: Routes — Tambahkan Endpoint API Baru

**Files:**
- Modify: `app/Config/Routes.php`

- [ ] **Step 1: Tambahkan route API publik dan admin**

Di file `app/Config/Routes.php`, tambahkan di bagian routes publik (sebelum routes admin):

```php
// -------------------------------------------------------------------------
// API Publik
// -------------------------------------------------------------------------

$routes->get('/api/stats/today', 'HomeController::apiStatsToday');
```

Dan di dalam group admin, tambahkan:

```php
// API Chart & Stats
$routes->get('api/trend', 'AdminController::apiTrend');
$routes->get('api/kunjungan-terakhir', 'AdminController::apiKunjunganTerakhir');
$routes->post('api/bulk-delete', 'AdminController::bulkDelete');
```

- [ ] **Step 2: Tambahkan route success page**

```php
$routes->get('/tamu/sukses', 'TamuController::sukses');
```

- [ ] **Step 3: Verifikasi route terdaftar**

```bash
php spark routes | grep -E "api|sukses"
```

Expected: Route baru muncul di listing

- [ ] **Step 4: Commit**

```bash
git add app/Config/Routes.php
git commit -m "feat(routes): add API endpoints for stats, trend, and bulk delete"
```

---

### Task 4: TamuModel — Tambahkan Method Baru

**Files:**
- Modify: `app/Models/TamuModel.php`

- [ ] **Step 1: Tambahkan method tren7Hari(), kunjunganTerakhir(), totalHariIni(), totalKemarin()**

Di file `app/Models/TamuModel.php`, tambahkan setelah method `ringkasanDashboard()`:

```php
/**
 * Statistik kunjungan 7 hari terakhir per hari per jenis
 *
 * @return array
 */
public function tren7Hari()
{
    $results = [];

    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-{$i} days"));

        if ($this->db->DBDriver === 'SQLite3') {
            $pengunjung = $this->where('jenis_tamu', 'pengunjung')
                                  ->where("date(tanggal)", $date)
                                  ->countAllResults();
            $tamu = $this->where('jenis_tamu', 'tamu')
                        ->where("date(tanggal)", $date)
                        ->countAllResults();
        } else {
            $pengunjung = $this->where('jenis_tamu', 'pengunjung')
                                  ->where('DATE(tanggal)', $date)
                                  ->countAllResults();
            $tamu = $this->where('jenis_tamu', 'tamu')
                        ->where('DATE(tanggal)', $date)
                        ->countAllResults();
        }

        $results[] = [
            'label'      => date('d/m', strtotime($date)),
            'pengunjung' => $pengunjung,
            'tamu'       => $tamu,
        ];
    }

    return $results;
}

/**
 * Data kunjungan terakhir (5 terbaru)
 *
 * @return array
 */
public function kunjunganTerakhir(int $limit = 5)
{
    return $this->orderBy('tanggal', 'DESC')
                ->limit($limit)
                ->findAll();
}

/**
 * Hitung total hari ini
 *
 * @return int
 */
public function totalHariIni()
{
    $today = date('Y-m-d');

    if ($this->db->DBDriver === 'SQLite3') {
        return $this->where("date(tanggal)", $today)->countAllResults();
    }

    return $this->where('DATE(tanggal)', $today)->countAllResults();
}

/**
 * Hitung total kemarin untuk perbandingan trend
 *
 * @return int
 */
public function totalKemarin()
{
    $yesterday = date('Y-m-d', strtotime('-1 day'));

    if ($this->db->DBDriver === 'SQLite3') {
        return $this->where("date(tanggal)", $yesterday)->countAllResults();
    }

    return $this->where('DATE(tanggal)', $yesterday)->countAllResults();
}
```

- [ ] **Step 2: Commit**

```bash
git add app/Models/TamuModel.php
git commit -m "feat(model): add trend, recent visits, and daily count methods"
```

---

### Task 5: AdminController — Tambahkan API Endpoint Baru

**Files:**
- Modify: `app/Controllers/AdminController.php`

- [ ] **Step 1: Tambahkan method apiTrend(), apiKunjunganTerakhir(), bulkDelete(), dan waktuRelatif()**

Di file `app/Controllers/AdminController.php`, tambahkan sebelum method `getNamaBulan()`:

```php
/**
 * API endpoint data tren 7 hari terakhir untuk ApexCharts
 */
public function apiTrend()
{
    $tren = $this->tamuModel->tren7Hari();

    $labels = array_column($tren, 'label');
    $pengunjung = array_column($tren, 'pengunjung');
    $tamu = array_column($tren, 'tamu');

    // Hitung trend persentase
    $today = $this->tamuModel->totalHariIni();
    $yesterday = $this->tamuModel->totalKemarin();
    $trendHariIni = $yesterday > 0 ? round((($today - $yesterday) / $yesterday) * 100) : 0;

    return $this->response->setJSON([
        'labels'      => $labels,
        'pengunjung'  => $pengunjung,
        'tamu'        => $tamu,
        'trend'       => [
            'hari_ini_vs_kemarin' => ($trendHariIni >= 0 ? '+' : '') . $trendHariIni . '%',
        ],
    ]);
}

/**
 * API endpoint data kunjungan terakhir
 */
public function apiKunjunganTerakhir()
{
    $data = $this->tamuModel->kunjunganTerakhir(5);

    foreach ($data as &$item) {
        $item['waktu_relatif'] = $this->waktuRelatif($item['tanggal']);
    }

    return $this->response->setJSON([
        'data' => $data,
    ]);
}

/**
 * Hapus multiple data tamu/pengunjung via AJAX
 */
public function bulkDelete()
{
    $ids = $this->request->getPost('ids');

    if (empty($ids) || !is_array($ids)) {
        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Tidak ada data yang dipilih',
        ]);
    }

    // Validasi setiap ID adalah angka
    $ids = array_filter($ids, 'is_numeric');

    if ($this->tamuModel->whereIn('id', $ids)->delete()) {
        return $this->response->setJSON([
            'status'  => 'success',
            'message' => count($ids) . ' data berhasil dihapus',
        ]);
    }

    return $this->response->setJSON([
        'status'  => 'error',
        'message' => 'Gagal menghapus data',
    ]);
}

/**
 * Helper untuk format waktu relatif
 *
 * @param string $datetime
 * @return string
 */
private function waktuRelatif($datetime)
{
    $now = time();
    $time = strtotime($datetime);
    $diff = $now - $time;

    if ($diff < 60) return 'Baru saja';
    if ($diff < 3600) return floor($diff / 60) . ' menit yang lalu';
    if ($diff < 86400) return floor($diff / 3600) . ' jam yang lalu';
    return floor($diff / 86400) . ' hari yang lalu';
}
```

- [ ] **Step 2: Commit**

```bash
git add app/Controllers/AdminController.php
git commit -m "feat(controller): add trend, recent visits, and bulk delete API endpoints"
```

---

### Task 6: HomeController — Tambahkan Endpoint API Stats

**Files:**
- Modify: `app/Controllers/HomeController.php`

- [ ] **Step 1: Tambahkan method apiStatsToday()**

Di file `app/Controllers/HomeController.php`, tambahkan method baru:

```php
/**
 * API endpoint statistik hari ini untuk widget homepage
 *
 * @return \CodeIgniter\HTTP\ResponseInterface
 */
public function apiStatsToday()
{
    $tamuModel = new \App\Models\TamuModel();

    // Tentukan status kantor berdasarkan jam
    $jamSekarang = (int) date('H');
    $statusKantor = ($jamSekarang >= 8 && $jamSekarang < 16) ? 'buka' : 'tutup';

    return $this->response->setJSON([
        'total_hari_ini'   => $tamuModel->totalHariIni(),
        'status_kantor'    => $statusKantor,
        'jam_operasional'  => '08:00 - 16:00',
    ]);
}
```

- [ ] **Step 2: Commit**

```bash
git add app/Controllers/HomeController.php
git commit -m "feat(controller): add public stats API endpoint for homepage widget"
```

---

### Task 7: TamuController — Tambahkan Success Page

**Files:**
- Modify: `app/Controllers/TamuController.php`
- Create: `app/Views/tamu/sukses.php`

- [ ] **Step 1: Update redirect di method store() dan tambahkan method sukses()**

Di file `app/Controllers/TamuController.php`:

1. Update redirect di method `store()` (baris ~109), ganti:
```php
return redirect()->to('/')->with('success', 'Terima kasih! Data Anda berhasil disimpan.');
```
Menjadi:
```php
$insertId = $this->tamuModel->getInsertID();
return redirect()->to('/tamu/sukses')
    ->with('tamu_id', $insertId)
    ->with('success', 'Terima kasih! Data Anda berhasil disimpan.');
```

2. Tambahkan method baru setelah `store()`:
```php
/**
 * Halaman sukses setelah pendaftaran
 *
 * @return string|\CodeIgniter\HTTP\RedirectResponse
 */
public function sukses()
{
    $tamuId = session()->getFlashdata('tamu_id');

    if (!$tamuId) {
        return redirect()->to('/');
    }

    $tamu = $this->tamuModel->find($tamuId);
    if (!$tamu) {
        return redirect()->to('/');
    }

    // Hitung nomor antrian (urutan ke berapa hari ini)
    $today = date('Y-m-d');
    $antrian = $this->tamuModel
        ->where('DATE(tanggal)', $today)
        ->where('id <=', $tamuId)
        ->countAllResults();

    $data = [
        'title'   => 'Pendaftaran Berhasil',
        'tamu'    => $tamu,
        'antrian' => $antrian,
    ];

    return view('tamu/sukses', $data);
}
```

- [ ] **Step 2: Buat view sukses.php**

Create file `app/Views/tamu/sukses.php` dengan konten:

```php
<?= $this->extend('layouts/metronic') ?>

<?= $this->section('content') ?>
<div class="d-flex flex-column flex-root h-100" id="kt_app_root">
    <div class="d-flex flex-column flex-center flex-column-fluid p-10">
        <div class="card card-flush w-lg-650px py-5 shadow-sm">
            <div class="card-body py-10 px-10 text-center">
                <!-- Ikon sukses -->
                <div class="symbol symbol-100px symbol-circle bg-light-success mx-auto mb-8">
                    <span class="symbol-label">
                        <i class="ki-duotone ki-shield-check text-success fs-5x">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                            <span class="path5"></span>
                        </i>
                    </span>
                </div>

                <h2 class="fw-bolder text-gray-900 mb-3">Pendaftaran Berhasil!</h2>
                <p class="text-gray-500 fw-semibold fs-6 mb-8">
                    Terima kasih telah mengisi data kunjungan Anda.
                </p>

                <!-- Info antrian -->
                <div class="border border-dashed border-success rounded p-6 mb-8 bg-light-success">
                    <div class="row g-5">
                        <div class="col-6">
                            <div class="text-muted fw-semibold fs-7 mb-1">Nomor Antrian</div>
                            <div class="fw-bolder text-success fs-2x"><?= str_pad($antrian, 3, '0', STR_PAD_LEFT) ?></div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted fw-semibold fs-7 mb-1">Jenis Kunjungan</div>
                            <div class="fw-bolder text-gray-800 fs-4"><?= ucfirst($tamu['jenis_tamu']) ?></div>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan data -->
                <div class="text-start mb-8">
                    <h5 class="fw-bold text-gray-800 mb-4">Ringkasan Data</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted fw-semibold ps-0" style="width: 140px;">Nama</td>
                                    <td class="fw-bold text-gray-800"><?= esc($tamu['nama']) ?></td>
                                </tr>
                                <?php if ($tamu['jenis_tamu'] === 'tamu' && !empty($tamu['instansi'])): ?>
                                <tr>
                                    <td class="text-muted fw-semibold ps-0">Instansi</td>
                                    <td class="fw-bold text-gray-800"><?= esc($tamu['instansi']) ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if ($tamu['jenis_tamu'] === 'pengunjung' && !empty($tamu['alamat'])): ?>
                                <tr>
                                    <td class="text-muted fw-semibold ps-0">Alamat</td>
                                    <td class="fw-bold text-gray-800"><?= esc($tamu['alamat']) ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if (!empty($tamu['hp'])): ?>
                                <tr>
                                    <td class="text-muted fw-semibold ps-0">No. HP</td>
                                    <td class="fw-bold text-gray-800"><?= esc($tamu['hp']) ?></td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td class="text-muted fw-semibold ps-0">Tujuan</td>
                                    <td class="fw-bold text-gray-800"><?= esc($tamu['tujuan']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold ps-0">Waktu</td>
                                    <td class="fw-bold text-gray-800"><?= date('d/m/Y H:i', strtotime($tamu['tanggal'])) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tombol aksi -->
                <a href="/" class="btn btn-primary fw-bold px-8 py-3">
                    <i class="ki-duotone ki-home-2 fs-2 me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
```

- [ ] **Step 3: Commit**

```bash
git add app/Controllers/TamuController.php app/Views/tamu/sukses.php
git commit -m "feat(tamu): add success page with queue number and data summary"
```

---

### Task 8: Homepage — Info Strip, Live Counter, Hover Animation

**Files:**
- Modify: `app/Views/home/index.php`

- [ ] **Step 1: Rewrite homepage dengan info strip, live counter, dan animasi**

Rewrite file `app/Views/home/index.php` dengan komponen:
- Info strip: 3 kolom (Status Kantor, Jam Operasional, Kunjungan Hari Ini)
- Card pilihan: hover animation (scale + shadow), symbol-circle untuk icon
- AJAX fetch ke `/api/stats/today` untuk mengisi data dinamis
- CSS: `.card-choice` transition untuk hover effect
- JavaScript: fetch API stats, update status badge dan counter menggunakan DOM API (bukan innerHTML untuk data dinamis — gunakan textContent untuk counter, createElement untuk badge)

- [ ] **Step 2: Verifikasi di browser**

Buka `http://localhost:8080`, pastikan:
- Info strip menampilkan status kantor dan counter
- Card hover animation berfungsi (scale + shadow)
- Responsive di mobile (stack vertikal)

- [ ] **Step 3: Commit**

```bash
git add app/Views/home/index.php
git commit -m "feat(home): add info strip, live counter, and hover animations"
```

---

### Task 9: Form Pendaftaran — Stepper, Kamera Optimasi, Validasi Inline

**Files:**
- Modify: `app/Views/tamu/form.php`

- [ ] **Step 1: Update form dengan stepper indicator, overlay kamera, validasi inline, dan upload fallback**

Rewrite file `app/Views/tamu/form.php` dengan komponen:

1. **Stepper indicator** di atas form — 3 langkah (Foto Wajah, Data Diri, Tujuan Kunjungan), class `stepper stepper-pills`, update active state via IntersectionObserver pada section form
2. **Kamera**: oval overlay guide (CSS border-radius + absolute positioning), countdown 3-2-1 sebelum capture, upload fallback jika kamera gagal
3. **Validasi inline**: event listener `input` untuk nama (min 3 char) dan telepon (regex Indonesia), feedback via DOM manipulation (classList toggle)
4. **CSS**: stepper states, oval guide overlay, countdown styling

Key implementation points:
- Stepper: 3 langkah dengan `data-kt-stepper-element`, update active state via IntersectionObserver
- Kamera: overlay SVG oval + countdown canvas di atas video
- Countdown: setInterval 3 detik, tampilkan angka via textContent, lalu auto-capture
- Validasi: regex untuk nama (min 3), telepon (format Indonesia), feedback via classList
- Upload fallback: hidden `<input type="file" accept="image/*" capture="user">` yang muncul jika kamera gagal

- [ ] **Step 2: Verifikasi di browser mobile (devtools responsive)**

Buka `http://localhost:8080/tamu`, pastikan:
- Stepper menunjukkan langkah aktif saat scroll
- Kamera menampilkan oval guide
- Countdown berjalan saat klik "Ambil Foto"
- Validasi inline muncul saat mengetik
- Jika kamera ditolak, tombol upload muncul

- [ ] **Step 3: Commit**

```bash
git add app/Views/tamu/form.php
git commit -m "feat(form): add stepper, camera overlay, countdown, inline validation, upload fallback"
```

---

### Task 10: Admin Dashboard — ApexCharts, Trend, Mini Widget, Quick Actions

**Files:**
- Modify: `app/Views/admin/dashboard.php`

- [ ] **Step 1: Rewrite dashboard dengan stat cards trend, ApexCharts, mini widget, dan quick actions**

Rewrite file `app/Views/admin/dashboard.php` dengan komponen:

1. **Breadcrumb** section sebelum content
2. **Stat cards** (3 kolom): Hari Ini, Bulan Ini, Tahun Ini — masing-masing dengan trend badge (hijau naik / merah turun)
3. **ApexCharts mixed chart** (bar: pengunjung, line: tamu, 7 hari terakhir) — menggunakan `getComputedStyle(document.documentElement).getPropertyValue('--kt-primary')` untuk warna
4. **Mini widget** kunjungan terakhir: 5 data terbaru, foto thumbnail + nama + badge jenis + waktu relatif
5. **Quick action buttons**: Tambah Tamu, Tambah Pengunjung, Export Laporan

Fetch endpoints:
- `GET /admin/api/trend` untuk chart data + trend persentase
- `GET /admin/api/kunjungan-terakhir` untuk mini widget

ApexCharts config mengikuti pattern dari Context7 docs (bar + line mixed chart).

- [ ] **Step 2: Verifikasi di browser**

Buka `http://localhost:8080/admin` (setelah login), pastikan:
- Stat cards menampilkan angka dan trend badge
- ApexCharts menampilkan bar chart pengunjung + line chart tamu
- Mini widget menampilkan 5 kunjungan terakhir dengan foto
- Quick action buttons berfungsi

- [ ] **Step 3: Commit**

```bash
git add app/Views/admin/dashboard.php
git commit -m "feat(dashboard): add ApexCharts, trend indicators, recent visits widget, quick actions"
```

---

### Task 11: Admin Lists — Column Search, Bulk Actions, Slide-in Detail

**Files:**
- Modify: `app/Views/admin/tamu_list.php`
- Modify: `app/Views/admin/pengunjung_list.php`

- [ ] **Step 1: Update tamu_list.php dengan column search, bulk actions, dan slide-in detail**

Perubahan utama di `app/Views/admin/tamu_list.php`:

1. **Column search**: tambah `<tfoot>` dengan input search per kolom, inisialisasi di DataTables `initComplete` callback
2. **Bulk actions**: checkbox header + per row (via render callback), dropdown toolbar "Hapus yang Dipilih", fetch `POST /admin/api/bulk-delete` dengan SweetAlert2 konfirmasi
3. **Slide-in detail drawer**: Metronic drawer (`data-kt-drawer` attributes), muncul saat klik baris, menampilkan foto besar + semua data + tombol edit/hapus via DOM manipulation

- [ ] **Step 2: Update pengunjung_list.php dengan perubahan yang sama**

Duplikasi pola yang sama ke `app/Views/admin/pengunjung_list.php`:
- Column search di footer
- Bulk actions (checkbox + dropdown)
- Slide-in detail drawer
- Sesuaikan kolom: Alamat (bukan Instansi)

- [ ] **Step 3: Verifikasi di browser**

Buka `http://localhost:8080/admin/tamu` dan `http://localhost:8080/admin/pengunjung`, pastikan:
- Column search berfungsi per kolom
- Checkbox select all berfungsi
- Bulk delete menampilkan konfirmasi dan menghapus data
- Klik baris membuka drawer detail dari kanan

- [ ] **Step 4: Commit**

```bash
git add app/Views/admin/tamu_list.php app/Views/admin/pengunjung_list.php
git commit -m "feat(admin-lists): add column search, bulk actions, slide-in detail drawer"
```

---

### Task 12: Admin Laporan — Migrasi ke ApexCharts, Donut Chart, Summary Cards

**Files:**
- Modify: `app/Views/admin/laporan.php`
- Modify: `app/Controllers/AdminController.php` (method chartData)

- [ ] **Step 1: Update method chartData() untuk format ApexCharts dan distribusi**

Update method `chartData()` di `app/Controllers/AdminController.php` agar mengembalikan data distribusi (pengunjung vs tamu) selain chart data. Pertahankan backward compatibility.

- [ ] **Step 2: Rewrite laporan.php view dengan ApexCharts**

Perubahan utama di `app/Views/admin/laporan.php`:

1. **Hapus** `<script src="chart.js CDN">` — ApexCharts sudah di `plugins.bundle.js`
2. **Summary cards** di atas chart: total kunjungan periode, rata-rata per hari, puncak kunjungan
3. **Mixed chart ApexCharts** (bar: pengunjung, line: tamu) menggantikan Chart.js
4. **Donut chart** distribusi pengunjung vs tamu (baris baru, di samping bar chart)

ApexCharts config:
- Bar + line mixed chart mengikuti pattern Context7 docs
- Donut chart: `type: 'donut'`, `labels: ['Pengunjung', 'Tamu']`
- Warna: CSS variables dari Metronic

- [ ] **Step 3: Verifikasi di browser**

Buka `http://localhost:8080/admin/laporan`, pastikan:
- Summary cards menampilkan statistik periode
- Mixed ApexCharts menampilkan data kunjungan
- Donut chart menampilkan distribusi
- Filter bulan/tahun berfungsi
- Export Excel dan PDF berfungsi

- [ ] **Step 4: Commit**

```bash
git add app/Views/admin/laporan.php app/Controllers/AdminController.php
git commit -m "feat(laporan): migrate to ApexCharts, add donut chart and summary cards"
```

---

### Task 13: Admin Pages — Tambahkan Breadcrumb

**Files:**
- Modify: `app/Views/admin/dashboard.php`
- Modify: `app/Views/admin/tamu_list.php`
- Modify: `app/Views/admin/pengunjung_list.php`
- Modify: `app/Views/admin/laporan.php`

- [ ] **Step 1: Tambahkan section breadcrumb ke setiap admin view**

Di setiap file admin view, tambahkan sebelum `content` section. Pattern breadcrumb Metronic:

```php
<?= $this->section('breadcrumb') ?>
<div class="app-toolbar py-3 py-lg-6" id="kt_app_toolbar">
    <div class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center me-10">[PAGE_TITLE]</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="/" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-5px"></span></li>
                <li class="breadcrumb-item text-gray-500">[BREADCRUMB_LABEL]</li>
            </ul>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
```

Breadcrumb labels:
- **dashboard.php**: Home > Dashboard
- **tamu_list.php**: Home > Admin > Daftar Tamu
- **pengunjung_list.php**: Home > Admin > Daftar Pengunjung
- **laporan.php**: Home > Admin > Laporan

- [ ] **Step 2: Verifikasi breadcrumb tampil di semua admin pages**

Buka setiap halaman admin, pastikan breadcrumb tampil di atas content

- [ ] **Step 3: Commit**

```bash
git add app/Views/admin/dashboard.php app/Views/admin/tamu_list.php app/Views/admin/pengunjung_list.php app/Views/admin/laporan.php
git commit -m "feat(admin): add breadcrumb navigation to all admin pages"
```

---

### Task 14: Final Verification

- [ ] **Step 1: Jalankan semua halaman dan verifikasi tidak ada error**

```bash
php spark routes
```

Pastikan semua route terdaftar tanpa error.

- [ ] **Step 2: Test semua endpoint API**

```bash
curl http://localhost:8080/api/stats/today
```

Expected: JSON response dengan `total_hari_ini`, `status_kantor`, `jam_operasional`

- [ ] **Step 3: Visual verification di browser**

- Homepage: info strip, counter, hover animation
- Form: stepper, kamera, validasi
- Success page: nomor antrian, ringkasan
- Admin dashboard: ApexCharts, trend, mini widget
- Admin lists: column search, bulk actions, drawer
- Admin laporan: ApexCharts, donut, summary cards
- Breadcrumb: tampil di semua admin pages

- [ ] **Step 4: Commit final (jika ada fix)**

```bash
git add -A
git commit -m "fix: final verification and polish"
```
