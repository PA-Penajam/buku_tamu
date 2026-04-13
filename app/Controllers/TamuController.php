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
            'foto_base64'=> 'required',
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

        // Proses penyimpanan Foto Base64
        $fotoName = null;
        $fotoBase64 = $this->request->getPost('foto_base64');
        if ($fotoBase64) {
            $imageParts = explode(";base64,", $fotoBase64);
            if (count($imageParts) === 2) {
                $imageTypeAux = explode("image/", $imageParts[0]);
                $imageType = $imageTypeAux[1] ?? 'jpeg';
                $imageBase64 = base64_decode($imageParts[1]);
                $fotoName = uniqid('tamu_') . '.' . $imageType;
                $filePath = FCPATH . 'uploads/tamu/' . $fotoName;
                file_put_contents($filePath, $imageBase64);
            }
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
            'foto'       => $fotoName,
        ];

        // Simpan ke database
        if ($this->tamuModel->insert($data)) {
            $insertId = $this->tamuModel->getInsertID();
            return redirect()->to('/tamu/sukses')
                ->with('tamu_id', $insertId)
                ->with('success', 'Terima kasih! Data Anda berhasil disimpan.');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
    }

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
}
