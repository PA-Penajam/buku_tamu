document.addEventListener('DOMContentLoaded', function () {
    // Fetch data statistik hari ini
    // Membaca URL endpoint dari sebuah attribute (atau hardcode ke /api/stats/today)
    fetch('/api/stats/today')
        .then(function (response) { return response.json(); })
        .then(function (data) {
            // Update status kantor
            var statusDot = document.getElementById('statusDot');
            var statusText = document.getElementById('statusText');
            if (statusDot && statusText) {
                if (data.status_kantor === 'buka') {
                    statusDot.classList.remove('tutup');
                    statusDot.classList.add('buka');
                    statusText.textContent = 'Buka';
                    statusText.classList.add('text-success');
                } else {
                    statusDot.classList.remove('buka');
                    statusDot.classList.add('tutup');
                    statusText.textContent = 'Tutup';
                    statusText.classList.add('text-danger');
                }
            }

            // Update jam operasional
            var jamText = document.getElementById('jamText');
            if (jamText) jamText.textContent = data.jam_operasional;

            // Update counter kunjungan
            var counterKunjungan = document.getElementById('counterKunjungan');
            if (counterKunjungan) counterKunjungan.textContent = data.total_hari_ini;
        })
        .catch(function () {
            var statusText = document.getElementById('statusText');
            if (statusText) statusText.textContent = 'Gagal memuat';
        });
});
