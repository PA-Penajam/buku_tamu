document.addEventListener('DOMContentLoaded', function () {
    var container = document.getElementById('kt_content_container');
    if (!container) return;

    var urlChart = container.dataset.urlChart;

    var primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--kt-primary').trim();
    var successColor = getComputedStyle(document.documentElement).getPropertyValue('--kt-success').trim();

    // Fetch chart data (ApexCharts sudah di plugins.bundle.js)
    if (urlChart) {
        fetch(urlChart)
            .then(function (r) { return r.json(); })
            .then(function (data) {
                // Update summary cards
                if (data.ringkasan) {
                    var sumTotal = document.getElementById('summaryTotal');
                    if (sumTotal) sumTotal.textContent = data.ringkasan.total_periode;
                    
                    var sumRata = document.getElementById('summaryRata');
                    if (sumRata) sumRata.textContent = data.ringkasan.rata_per_hari;

                    var sumPuncak = document.getElementById('summaryPuncak');
                    if (sumPuncak) sumPuncak.textContent = data.ringkasan.puncak;
                }

                // Mixed chart: bar (pengunjung) + line (tamu)
                var chartKunjunganEl = document.querySelector('#chartKunjungan');
                if (chartKunjunganEl) {
                    var mixedOptions = {
                        series: [
                            { name: 'Pengunjung', type: 'column', data: data.pengunjung },
                            { name: 'Tamu', type: 'line', data: data.tamu }
                        ],
                        chart: { height: 350, type: 'line', toolbar: { show: false } },
                        colors: [primaryColor, successColor],
                        stroke: { width: [0, 4] },
                        labels: data.labels,
                        xaxis: { type: 'category' },
                        yaxis: [
                            { title: { text: 'Pengunjung' }, beginAtZero: true }
                        ],
                        fill: { opacity: 1 },
                        tooltip: { shared: true, intersect: false },
                        legend: { position: 'top' }
                    };
                    new ApexCharts(chartKunjunganEl, mixedOptions).render();
                }

                // Donut chart: distribusi pengunjung vs tamu
                var chartDistribusiEl = document.querySelector('#chartDistribusi');
                if (data.distribusi && chartDistribusiEl) {
                    var donutOptions = {
                        series: [data.distribusi.pengunjung, data.distribusi.tamu],
                        chart: { type: 'donut', width: '100%' },
                        labels: ['Pengunjung', 'Tamu'],
                        colors: [primaryColor, successColor],
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '70%',
                                    labels: {
                                        show: true,
                                        total: {
                                            show: true,
                                            label: 'Total',
                                            formatter: function (w) {
                                                return w.globals.seriesTotals.reduce(function (a, b) { return a + b; }, 0);
                                            }
                                        }
                                    }
                                }
                            }
                        },
                        legend: { position: 'bottom' },
                        dataLabels: { enabled: true, formatter: function (val) { return val; } }
                    };
                    new ApexCharts(chartDistribusiEl, donutOptions).render();
                }
            })
            .catch(function (err) { console.error('Gagal load chart:', err); });
    }
});
