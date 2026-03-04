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
        $data = [
            'title' => 'Selamat Datang - Buku Tamu',
        ];

        return view('home/index', $data);
    }
}
