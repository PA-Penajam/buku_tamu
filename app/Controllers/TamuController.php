<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\TamuModel;

/**
 * Controller untuk form pendaftaran tamu dan pengunjung
 */
class TamuController extends Controller
{
    protected TamuRepository $tamuRepository;

    public function __construct()
    {
        $this->tamuRepository = new TamuRepository();
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
            'title'      => 'Pendaftaran Tamu',
            'jenis_tamu' => 'tamu',
            'css_files'  => ['assets/css/pages/form_tamu.css'],
            'js_files'   => ['assets/js/pages/form_tamu.js'],
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
        // Validasi input dengan security hardening
        $rules = [
            'jenis_tamu' => 'required|in_list[pengunjung,tamu]',
            'nama'       => 'required|max_length[255]|alpha_numeric_spaces',
            'hp'         => 'permit_empty|max_length[20]|numeric',
            'tujuan'     => 'required|max_length[500]|alpha_numeric_punct',
            'foto_base64'=> 'required|regex_match[/^data:image\/(jpeg|jpg|png|gif);base64,/]',
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

        // Proses penyimpanan Foto Base64 dengan validasi ketat
        $fotoName = null;
        $fotoBase64 = $this->request->getPost('foto_base64');
        if ($fotoBase64) {
            $imageParts = explode(";base64,", $fotoBase64);
            if (count($imageParts) === 2) {
                $imageTypeAux = explode("image/", $imageParts[0]);
                $imageType = strtolower($imageTypeAux[1] ?? 'jpeg');
                // Validasi tipe file yang diizinkan
                $allowedTypes = ['jpeg', 'jpg', 'png', 'gif'];
                if (!in_array($imageType, $allowedTypes)) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Tipe file gambar tidak diizinkan. Hanya JPG, PNG, dan GIF yang diperbolehkan.');
                }
                // Validasi ukuran file (maks 2MB)
                $imageData = base64_decode($imageParts[1], true);
                if ($imageData === false || strlen($imageData) > 2 * 1024 * 1024) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Ukuran gambar terlalu besar. Maksimal 2MB diperbolehkan.');
                }
                $imageBase64 = base64_decode($imageParts[1]);
                $fotoName = 'tamu_' . bin2hex(random_bytes(8)) . '.' . $imageType;
                $uploadDir = FCPATH . 'uploads/tamu/';
                // Buat directory jika belum ada
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $filePath = $uploadDir . $fotoName;
                if (!file_put_contents($filePath, $imageBase64)) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Gagal menyimpan file gambar. Silakan coba lagi.');
                }
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

        // Simpan ke database melalui Repository
        $insertId = $this->tamuRepository->saveGuest($data);
        if ($insertId) {
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

        $tamu = $this->tamuRepository->findById($tamuId);
        if (!$tamu) {
            return redirect()->to('/');
        }

        // Hitung nomor urut antrian dari Repository
        $antrian = $this->tamuRepository->getQueueNumber($tamuId);

        $data = [
            'title'   => 'Pendaftaran Berhasil',
            'tamu'    => $tamu,
            'antrian' => $antrian,
        ];

        return view('tamu/sukses', $data);
    }
}
