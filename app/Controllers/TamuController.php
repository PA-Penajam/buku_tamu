<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\TamuModel;

/**
 * Controller untuk form pendaftaran tamu dan pengunjung
 */
class TamuController extends Controller
{
    protected $tamuModel;

    public function __construct()
    {
        $this->tamuModel = new TamuModel();
    }

    /**
     * Form pendaftaran pengunjung
     *
     * @return string
     */
    public function pengunjung()
    {
        $data = [
            'title'     => 'Pendaftaran Pengunjung',
            'jenis_tamu' => 'pengunjung',
        ];

        return view('tamu/form', $data);
    }

    /**
     * Form pendaftaran tamu
     *
     * @return string
     */
    public function tamu()
    {
        $data = [
            'title'     => 'Pendaftaran Tamu',
            'jenis_tamu' => 'tamu',
        ];

        return view('tamu/form', $data);
    }

    /**
     * Menyimpan data tamu/pengunjung
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function store()
    {
        // Validasi input
        $rules = [
            'jenis_tamu' => 'required|in_list[pengunjung,tamu]',
            'nama'       => 'required|max_length[255]',
            'hp'         => 'permit_empty|max_length[20]',
            'tujuan'     => 'required',
        ];

        // Tambah validasi berdasarkan jenis tamu
        $jenisTamu = $this->request->getPost('jenis_tamu');
        if ($jenisTamu === 'pengunjung') {
            $rules['alamat'] = 'required';
        } else {
            $rules['instansi'] = 'required';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Siapkan data untuk disimpan
        $data = [
            'jenis_tamu' => $jenisTamu,
            'tanggal'    => date('Y-m-d H:i:s'),
            'nama'       => $this->request->getPost('nama'),
            'alamat'     => $this->request->getPost('alamat') ?? null,
            'instansi'   => $this->request->getPost('instansi') ?? null,
            'hp'         => $this->request->getPost('hp') ?? null,
            'tujuan'     => $this->request->getPost('tujuan'),
        ];

        // Simpan ke database
        if ($this->tamuModel->insert($data)) {
            return redirect()->to('/')
                ->with('success', 'Terima kasih! Data Anda berhasil disimpan.');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
    }
}
