<?= $this->extend('layouts/metronic') ?>

<?= $this->section('header') ?>
<?= view('partials/header_admin') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-xxl mt-5" id="kt_content_container">

    <!-- Stat Cards dengan Trend -->
    <div class="row g-5 g-xl-8 mb-5 mb-xl-8">
        <!-- Hari Ini -->
        <div class="col-xl-4">
            <div class="card card-xl-stretch mb-xl-8">
                <div class="card-body d-flex align-items-center pt-3 pb-0">
                    <div class="d-flex flex-column flex-grow-1 py-2 py-lg-13 me-2">
                        <span class="fw-semibold text-muted fs-5 mb-2">Hari Ini</span>
                        <span class="fw-bolder text-dark fs-1 mb-2"><?= number_format($stats['total_hari_ini']) ?></span>
                        <div class="d-flex align-items-center">
                            <span class="badge badge-light-success fs-7 fw-semibold me-2" id="trendHariIni">
                                <i class="ki-duotone ki-arrow-up fs-6"></i> 0%
                            </span>
                            <span class="text-muted fw-semibold fs-7">vs kemarin</span>
                        </div>
                    </div>
                    <div class="symbol symbol-70px symbol-circle">
                        <span class="symbol-label bg-primary">
                            <i class="ki-duotone ki-calendar-tick fs-2x text-white">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
                            </i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulan Ini -->
        <div class="col-xl-4">
            <div class="card card-xl-stretch mb-xl-8">
                <div class="card-body d-flex align-items-center pt-3 pb-0">
                    <div class="d-flex flex-column flex-grow-1 py-2 py-lg-13 me-2">
                        <span class="fw-semibold text-muted fs-5 mb-2">Bulan Ini</span>
                        <span class="fw-bolder text-dark fs-1 mb-2"><?= number_format($stats['total_bulan_ini']) ?></span>
                        <span class="text-muted fw-semibold fs-7">Pengunjung & Tamu</span>
                    </div>
                    <div class="symbol symbol-70px symbol-circle">
                        <span class="symbol-label bg-success">
                            <i class="ki-duotone ki-calendar fs-2x text-white">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                            </i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tahun Ini -->
        <div class="col-xl-4">
            <div class="card card-xl-stretch mb-5 mb-xl-8">
                <div class="card-body d-flex align-items-center pt-3 pb-0">
                    <div class="d-flex flex-column flex-grow-1 py-2 py-lg-13 me-2">
                        <span class="fw-semibold text-muted fs-5 mb-2">Tahun Ini</span>
                        <span class="fw-bolder text-dark fs-1 mb-2"><?= number_format($stats['total_tahun_ini']) ?></span>
                        <span class="text-muted fw-semibold fs-7">Pengunjung & Tamu</span>
                    </div>
                    <div class="symbol symbol-70px symbol-circle">
                        <span class="symbol-label bg-info">
                            <i class="ki-duotone ki-time fs-2x text-white">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart & Mini Widget Row -->
    <div class="row g-5 g-xl-8">
        <!-- ApexCharts Trend 7 Hari -->
        <div class="col-xl-8">
            <div class="card card-xl-stretch mb-xl-8">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">Tren Kunjungan 7 Hari</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Pengunjung (bar) vs Tamu (line)</span>
                    </h3>
                </div>
                <div class="card-body pt-0">
                    <div id="chartTrend" style="min-height: 350px;"></div>
                </div>
            </div>
        </div>

        <!-- Mini Widget: Kunjungan Terakhir -->
        <div class="col-xl-4">
            <div class="card card-xl-stretch mb-xl-8">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">Kunjungan Terakhir</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">5 kunjungan terbaru</span>
                    </h3>
                </div>
                <div class="card-body pt-0" id="kunjunganTerakhirContainer">
                    <div class="text-center text-muted py-10">
                        <span class="spinner-border spinner-border-sm me-2"></span>Memuat data...
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Summary -->
    <div class="row g-5 g-xl-8">
        <!-- Quick Actions -->
        <div class="col-xl-6">
            <div class="card card-xl-stretch mb-xl-8">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">Aksi Cepat</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Tombol aksi yang sering digunakan</span>
                    </h3>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex flex-column gap-3">
                        <a href="<?= base_url('admin/tamu') ?>" class="btn btn-light-primary btn-active-light-primary d-flex align-items-center py-4 px-6">
                            <i class="ki-duotone ki-user-plus fs-2x me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            <div class="text-start">
                                <div class="fw-bold text-gray-800">Tambah Tamu</div>
                                <div class="text-muted fs-7">Input data tamu baru</div>
                            </div>
                        </a>
                        <a href="<?= base_url('admin/pengunjung') ?>" class="btn btn-light-success btn-active-light-success d-flex align-items-center py-4 px-6">
                            <i class="ki-duotone ki-people-plus fs-2x me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            <div class="text-start">
                                <div class="fw-bold text-gray-800">Tambah Pengunjung</div>
                                <div class="text-muted fs-7">Input data pengunjung baru</div>
                            </div>
                        </a>
                        <a href="<?= base_url('admin/laporan') ?>" class="btn btn-light-info btn-active-light-info d-flex align-items-center py-4 px-6">
                            <i class="ki-duotone ki-file-up fs-2x me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            <div class="text-start">
                                <div class="fw-bold text-gray-800">Export Laporan</div>
                                <div class="text-muted fs-7">Laporan bulanan Excel/PDF</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Summary -->
        <div class="col-xl-6">
            <div class="card card-xl-stretch mb-xl-8">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">Ringkasan</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Data pengunjung dan tamu</span>
                    </h3>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-5">
                        <div class="col-6">
                            <div class="text-center p-5 bg-light-primary rounded">
                                <div class="fs-2x fw-bolder text-primary"><?= number_format($stats['total_pengunjung']) ?></div>
                                <div class="text-muted fs-7 fw-semibold">Total Pengunjung</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-5 bg-light-success rounded">
                                <div class="fs-2x fw-bolder text-success"><?= number_format($stats['total_tamu']) ?></div>
                                <div class="text-muted fs-7 fw-semibold">Total Tamu</div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 p-5 bg-light rounded text-center">
                        <div class="text-muted fs-7 fw-semibold mb-2">TOTAL KESELURUHAN</div>
                        <div class="fs-3x fw-bolder text-gray-800"><?= number_format($stats['total_semua']) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Ambil warna dari CSS variables Metronic
    var primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--kt-primary').trim();
    var successColor = getComputedStyle(document.documentElement).getPropertyValue('--kt-success').trim();

    // === FETCH TREND DATA ===
    fetch('<?= base_url('admin/api/trend') ?>')
        .then(function (r) { return r.json(); })
        .then(function (data) {
            // Update trend badge menggunakan DOM API
            var trendEl = document.getElementById('trendHariIni');
            if (data.trend) {
                var trendVal = data.trend.hari_ini_vs_kemarin;
                var isPositive = trendVal.startsWith('+');
                trendEl.className = 'badge fs-7 fw-semibold me-2 ' + (isPositive ? 'badge-light-success' : 'badge-light-danger');
                // Gunakan textContent untuk data dinamis
                trendEl.textContent = trendVal;
                var icon = document.createElement('i');
                icon.className = 'ki-duotone ki-arrow-' + (isPositive ? 'up' : 'down') + ' fs-6 me-1';
                trendEl.insertBefore(icon, trendEl.firstChild);
            }

            // Render ApexCharts (data dari API sudah sanitized di server-side)
            var options = {
                series: [
                    { name: 'Pengunjung', type: 'column', data: data.pengunjung },
                    { name: 'Tamu', type: 'line', data: data.tamu }
                ],
                chart: {
                    height: 350,
                    type: 'line',
                    toolbar: { show: false }
                },
                colors: [primaryColor, successColor],
                stroke: { width: [0, 4] },
                labels: data.labels,
                xaxis: { type: 'category' },
                yaxis: [
                    { title: { text: 'Pengunjung' } },
                    { opposite: true, title: { text: 'Tamu' } }
                ],
                fill: { opacity: 1 },
                tooltip: { shared: true, intersect: false },
                legend: { position: 'top' }
            };

            var chart = new ApexCharts(document.querySelector('#chartTrend'), options);
            chart.render();
        })
        .catch(function (err) { console.error('Gagal load trend:', err); });

    // === FETCH KUNJUNGAN TERAKHIR (menggunakan DOM API yang aman) ===
    fetch('<?= base_url('admin/api/kunjungan-terakhir') ?>')
        .then(function (r) { return r.json(); })
        .then(function (result) {
            var container = document.getElementById('kunjunganTerakhirContainer');
            container.textContent = '';

            if (!result.data || result.data.length === 0) {
                var emptyMsg = document.createElement('div');
                emptyMsg.className = 'text-center text-muted py-10';
                emptyMsg.textContent = 'Belum ada kunjungan hari ini';
                container.appendChild(emptyMsg);
                return;
            }

            var timeline = document.createElement('div');
            timeline.className = 'timeline timeline-item-transparent';

            result.data.forEach(function (item) {
                var row = document.createElement('div');
                row.className = 'timeline-item d-flex align-items-start mb-5';

                // Foto thumbnail
                var symbol = document.createElement('div');
                symbol.className = 'symbol symbol-40px symbol-circle me-4 flex-shrink-0';
                var img = document.createElement('img');
                img.src = item.foto ? '<?= base_url('uploads/tamu/') ?>' + item.foto : '<?= base_url('assets/media/avatars/blank.png') ?>';
                img.alt = '';
                img.className = 'symbol-label';
                img.onerror = function () { this.src = '<?= base_url('assets/media/avatars/blank.png') ?>'; };
                symbol.appendChild(img);

                // Info text
                var info = document.createElement('div');
                info.className = 'flex-grow-1';

                var header = document.createElement('div');
                header.className = 'd-flex align-items-center justify-content-between mb-1';

                var nama = document.createElement('span');
                nama.className = 'fw-bold text-gray-800 fs-6';
                nama.textContent = item.nama;

                var badge = document.createElement('span');
                badge.className = 'badge ' + (item.jenis_tamu === 'pengunjung' ? 'badge-light-primary' : 'badge-light-success') + ' fs-7';
                badge.textContent = item.jenis_tamu;

                header.appendChild(nama);
                header.appendChild(badge);

                var waktu = document.createElement('div');
                waktu.className = 'text-muted fs-7';
                waktu.textContent = item.waktu_relatif || '';

                info.appendChild(header);
                info.appendChild(waktu);
                row.appendChild(symbol);
                row.appendChild(info);
                timeline.appendChild(row);
            });

            container.appendChild(timeline);
        })
        .catch(function (err) {
            console.error('Gagal load kunjungan terakhir:', err);
            var container = document.getElementById('kunjunganTerakhirContainer');
            container.textContent = '';
            var errMsg = document.createElement('div');
            errMsg.className = 'text-center text-danger py-10';
            errMsg.textContent = 'Gagal memuat data';
            container.appendChild(errMsg);
        });
});
</script>
<?= $this->endSection() ?>
