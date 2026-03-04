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
            'title' => 'Daftar Pengunjung'
        ];

        return view('admin/pengunjung_list', $data);
    }

    public function pengunjungDt()
    {
        $builder = $this->tamuModel->builder()->where('jenis_tamu', 'pengunjung');
        return \Hermawan\DataTables\DataTable::of($builder)
            ->add('aksi', function($row){
                $rowData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                return '<button class="btn btn-sm btn-light-primary me-2" onclick="openEditModal('.$rowData.')">Edit</button>' .
                       '<button class="btn btn-sm btn-light-danger" onclick="deleteData('.$row->id.')">Hapus</button>';
            })
            ->format('tanggal', function($value){
                return '<div class="text-gray-800 fw-bold">'.date('d/m/Y', strtotime($value)).'</div>'.
                       '<div class="text-muted">'.date('H:i', strtotime($value)).'</div>';
            })
            ->format('nama', function($value){
                return '<div class="text-gray-800 fw-bold">'.esc($value).'</div>';
            })
            ->toJson(true);
    }

    /**
     * Daftar tamu
     *
     * @return string
     */
    public function tamu()
    {
        $data = [
            'title' => 'Daftar Tamu'
        ];

        return view('admin/tamu_list', $data);
    }

    public function tamuDt()
    {
        $builder = $this->tamuModel->builder()->where('jenis_tamu', 'tamu');
        return \Hermawan\DataTables\DataTable::of($builder)
            ->add('aksi', function($row){
                $rowData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                return '<button class="btn btn-sm btn-light-primary me-2" onclick="openEditModal('.$rowData.')">Edit</button>' .
                       '<button class="btn btn-sm btn-light-danger" onclick="deleteData('.$row->id.')">Hapus</button>';
            })
            ->format('tanggal', function($value){
                return '<div class="text-gray-800 fw-bold">'.date('d/m/Y', strtotime($value)).'</div>'.
                       '<div class="text-muted">'.date('H:i', strtotime($value)).'</div>';
            })
            ->format('nama', function($value){
                return '<div class="text-gray-800 fw-bold">'.esc($value).'</div>';
            })
            ->toJson(true);
    }

    /**
     * Laporan bulanan dengan chart
     *
     * @return string
     */
    public function laporan()
    {
        $bulan = (int) ($this->request->getGet('bulan') ?? date('m'));
        $tahun = (int) ($this->request->getGet('tahun') ?? date('Y'));

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
     * Menyimpan data tamu/pengunjung baru via AJAX
     */
    public function storeTamu()
    {
        $jenisTamu = $this->request->getPost('jenis_tamu');
        
        $rules = [
            'jenis_tamu' => 'required|in_list[pengunjung,tamu]',
            'nama'       => 'required|max_length[255]',
            'hp'         => 'permit_empty|max_length[20]',
            'tujuan'     => 'required',
        ];

        if ($jenisTamu === 'pengunjung') {
            $rules['alamat'] = 'required';
        } else {
            $rules['instansi'] = 'required';
        }

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'jenis_tamu' => $jenisTamu,
            'tanggal'    => date('Y-m-d H:i:s'),
            'nama'       => $this->request->getPost('nama'),
            'alamat'     => $this->request->getPost('alamat') ?? null,
            'instansi'   => $this->request->getPost('instansi') ?? null,
            'hp'         => $this->request->getPost('hp') ?? null,
            'tujuan'     => $this->request->getPost('tujuan'),
        ];

        if ($this->tamuModel->insert($data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil disimpan']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan data']);
    }

    /**
     * Update data tamu/pengunjung via AJAX
     */
    public function updateTamu($id)
    {
        $tamu = $this->tamuModel->find($id);
        if (!$tamu) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }

        $jenisTamu = $tamu['jenis_tamu'];
        
        $rules = [
            'nama'       => 'required|max_length[255]',
            'hp'         => 'permit_empty|max_length[20]',
            'tujuan'     => 'required',
        ];

        if ($jenisTamu === 'pengunjung') {
            $rules['alamat'] = 'required';
        } else {
            $rules['instansi'] = 'required';
        }

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'nama'       => $this->request->getPost('nama'),
            'alamat'     => $this->request->getPost('alamat') ?? null,
            'instansi'   => $this->request->getPost('instansi') ?? null,
            'hp'         => $this->request->getPost('hp') ?? null,
            'tujuan'     => $this->request->getPost('tujuan'),
        ];

        if ($this->tamuModel->update($id, $data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil diupdate']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal update data']);
    }

    /**
     * Hapus data tamu/pengunjung via AJAX
     */
    public function deleteTamu($id)
    {
        if ($this->tamuModel->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal hapus data']);
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
