<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\TamuModel;

/**
 * Controller untuk dashboard admin
 * Menampilkan statistik, daftar tamu/pengunjung, dan laporan
 */
class AdminController extends Controller
{
    protected $tamuModel;

    public function __construct()
    {
        $this->tamuModel = new TamuModel();
    }

    /**
     * Dashboard admin dengan statistik ringkasan
     *
     * @return string
     */
    public function index()
    {
        $data = [
            'title'   => 'Dashboard Admin',
            'stats'   => $this->tamuModel->ringkasanDashboard(),
        ];

        return view('admin/dashboard', $data);
    }

    /**
     * Daftar pengunjung
     *
     * @return string
     */
    public function pengunjung()
    {
        $data = [
            'title' => 'Daftar Pengunjung',
            'data'  => $this->tamuModel->pengunjung()
                ->orderBy('tanggal', 'DESC')
                ->paginate(20),
            'pager' => $this->tamuModel->pager,
        ];

        return view('admin/pengunjung_list', $data);
    }

    /**
     * Daftar tamu
     *
     * @return string
     */
    public function tamu()
    {
        $data = [
            'title' => 'Daftar Tamu',
            'data'  => $this->tamuModel->tamu()
                ->orderBy('tanggal', 'DESC')
                ->paginate(20),
            'pager' => $this->tamuModel->pager,
        ];

        return view('admin/tamu_list', $data);
    }

    /**
     * Laporan bulanan dengan chart
     *
     * @return string
     */
    public function laporan()
    {
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        // Data untuk tabel
        $dataLaporan = $this->tamuModel
            ->filterBulanTahun($bulan, $tahun)
            ->orderBy('tanggal', 'DESC')
            ->findAll();

        // Data statistik bulanan untuk chart
        $statistik = $this->tamuModel->statistikBulanan($tahun);

        $data = [
            'title'       => 'Laporan Bulanan',
            'bulan'       => $bulan,
            'tahun'       => $tahun,
            'dataLaporan' => $dataLaporan,
            'statistik'   => $statistik,
        ];

        return view('admin/laporan', $data);
    }

    /**
     * API endpoint untuk data chart
     * Mengembalikan data dalam format JSON
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function chartData()
    {
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        $statistik = $this->tamuModel->statistikBulanan($tahun);

        // Format data untuk Chart.js
        $labels = [];
        $pengunjungData = [];
        $tamuData = [];

        // Inisialisasi array untuk 12 bulan
        for ($i = 1; $i <= 12; $i++) {
            $labels[] = $this->getNamaBulan($i);
            $pengunjungData[$i] = 0;
            $tamuData[$i] = 0;
        }

        // Isi data dari query
        foreach ($statistik as $row) {
            $bulan = (int) $row['bulan'];
            if ($row['jenis_tamu'] === 'pengunjung') {
                $pengunjungData[$bulan] = (int) $row['jumlah'];
            } else {
                $tamuData[$bulan] = (int) $row['jumlah'];
            }
        }

        return $this->response->setJSON([
            'labels'      => $labels,
            'pengunjung'  => array_values($pengunjungData),
            'tamu'        => array_values($tamuData),
        ]);
    }

    /**
     * Helper untuk mendapatkan nama bulan
     *
     * @param int $bulan
     * @return string
     */
    private function getNamaBulan($bulan)
    {
        $bulanNama = [
            1  => 'Januari', 2  => 'Februari', 3  => 'Maret',
            4  => 'April',   5  => 'Mei',      6  => 'Juni',
            7  => 'Juli',    8  => 'Agustus',  9  => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return $bulanNama[$bulan] ?? '';
    }
}
