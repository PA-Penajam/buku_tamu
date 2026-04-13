document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('kt_content_container');
    if (!container) return; // Guard clause

    const urlTrend = container.dataset.urlTrend;
    const urlTerakhir = container.dataset.urlTerakhir;
    const urlUploads = container.dataset.urlUploads;
    const urlBlank = container.dataset.urlBlank;

    // Ambil warna dari CSS variables Metronic
    var primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--kt-primary').trim();
    var successColor = getComputedStyle(document.documentElement).getPropertyValue('--kt-success').trim();

    // === FETCH TREND DATA ===
    fetch(urlTrend)
        .then(function (r) { return r.json(); })
        .then(function (data) {
            // Update trend badge menggunakan DOM API
            var trendEl = document.getElementById('trendHariIni');
            if (trendEl && data.trend) {
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
            var chartContainer = document.querySelector('#chartTrend');
            if (chartContainer) {
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

                var chart = new ApexCharts(chartContainer, options);
                chart.render();
            }
        })
        .catch(function (err) { console.error('Gagal load trend:', err); });

    // === FETCH KUNJUNGAN TERAKHIR ===
    fetch(urlTerakhir)
        .then(function (r) { return r.json(); })
        .then(function (result) {
            var latestContainer = document.getElementById('kunjunganTerakhirContainer');
            if (!latestContainer) return;
            
            latestContainer.textContent = '';

            if (!result.data || result.data.length === 0) {
                var emptyMsg = document.createElement('div');
                emptyMsg.className = 'text-center text-muted py-10';
                emptyMsg.textContent = 'Belum ada kunjungan hari ini';
                latestContainer.appendChild(emptyMsg);
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
                img.src = item.foto ? urlUploads + item.foto : urlBlank;
                img.alt = '';
                img.className = 'symbol-label';
                img.onerror = function () { this.src = urlBlank; };
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

            latestContainer.appendChild(timeline);
        })
        .catch(function (err) {
            console.error('Gagal load kunjungan terakhir:', err);
            var latestContainer = document.getElementById('kunjunganTerakhirContainer');
            if (latestContainer) {
                latestContainer.textContent = '';
                var errMsg = document.createElement('div');
                errMsg.className = 'text-center text-danger py-10';
                errMsg.textContent = 'Gagal memuat data';
                latestContainer.appendChild(errMsg);
            }
        });
});
