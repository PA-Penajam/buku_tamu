<?php

namespace App\Controllers;

use CodeIgniter\Controller;

/**
 * Controller untuk halaman utama
 * Menampilkan homepage dengan 2 card pilihan (Tamu dan Pengunjung)
 */
class HomeController extends Controller
{
    /**
     * Menampilkan homepage dengan 2 card pilihan
     *
     * @return string
     */
    public function index()
    {
        // Langsung redirect ke form tamu karena fitur tamu/pengunjung telah disatukan
        return redirect()->to('/tamu');
        
        $data = [
            'title' => 'Selamat Datang - Buku Tamu',
        ];

        return view('home/index', $data);
    }

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
}
