<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model untuk tabel tamu
 * Menangani CRUD data tamu dan pengunjung
 */
class TamuModel extends Model
{
    protected $table            = 'tamu';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $skipValidation   = false;
    protected $cleanValidationRules = true;

    protected $allowedFields = [
        'jenis_tamu',
        'tanggal',
        'nama',
        'alamat',
        'instansi',
        'hp',
        'tujuan',
    ];

    protected $validationRules = [
        'jenis_tamu' => 'required|in_list[pengunjung,tamu]',
        'tanggal'    => 'required|valid_date',
        'nama'       => 'required|max_length[255]',
        'hp'         => 'permit_empty|max_length[20]',
    ];

    protected $validationMessages = [
        'jenis_tamu' => [
            'required'  => 'Jenis tamu harus dipilih',
            'in_list'   => 'Jenis tamu tidak valid',
        ],
        'tanggal' => [
            'required'   => 'Tanggal kunjungan harus diisi',
            'valid_date' => 'Format tanggal tidak valid',
        ],
        'nama' => [
            'required'   => 'Nama harus diisi',
            'max_length' => 'Nama maksimal 255 karakter',
        ],
    ];

    /**
     * Mengambil data pengunjung saja
     *
     * @return $this
     */
    public function pengunjung()
    {
        return $this->where('jenis_tamu', 'pengunjung');
    }

    /**
     * Mengambil data tamu saja
     *
     * @return $this
     */
    public function tamu()
    {
        return $this->where('jenis_tamu', 'tamu');
    }

    /**
     * Filter berdasarkan bulan dan tahun
     *
     * @param int $bulan
     * @param int $tahun
     * @return $this
     */
    public function filterBulanTahun($bulan, $tahun)
    {
        if ($this->db->DBDriver === 'SQLite3') {
            return $this->where("strftime('%m', tanggal)", sprintf('%02d', $bulan))
                        ->where("strftime('%Y', tanggal)", (string)$tahun);
        }
        return $this->where('MONTH(tanggal)', $bulan)
                    ->where('YEAR(tanggal)', $tahun);
    }

    /**
     * Statistik jumlah kunjungan per bulan dalam setahun
     *
     * @param int $tahun
     * @return array
     */
    public function statistikBulanan($tahun)
    {
        if ($this->db->DBDriver === 'SQLite3') {
            return $this->select("CAST(strftime('%m', tanggal) AS INTEGER) as bulan, jenis_tamu, COUNT(*) as jumlah")
                        ->where("strftime('%Y', tanggal)", (string)$tahun)
                        ->groupBy("strftime('%m', tanggal), jenis_tamu")
                        ->findAll();
        }
        
        return $this->select("MONTH(tanggal) as bulan, jenis_tamu, COUNT(*) as jumlah")
                    ->where('YEAR(tanggal)', $tahun)
                    ->groupBy('MONTH(tanggal), jenis_tamu')
                    ->findAll();
    }

    /**
     * Ringkasan statistik dashboard
     *
     * @return array
     */
    public function ringkasanDashboard()
    {
        $today = date('Y-m-d');
        $thisMonth = date('m');
        $thisYear = date('Y');

        if ($this->db->DBDriver === 'SQLite3') {
            return [
                'total_hari_ini' => $this->where("date(tanggal)", $today)->countAllResults(),
                'total_bulan_ini' => $this->where("strftime('%m', tanggal)", $thisMonth)
                                          ->where("strftime('%Y', tanggal)", $thisYear)
                                          ->countAllResults(),
                'total_tahun_ini' => $this->where("strftime('%Y', tanggal)", $thisYear)->countAllResults(),
                'total_semua' => $this->countAll(),
                'total_pengunjung' => $this->where('jenis_tamu', 'pengunjung')->countAllResults(),
                'total_tamu' => $this->where('jenis_tamu', 'tamu')->countAllResults(),
            ];
        }

        return [
            'total_hari_ini' => $this->where('DATE(tanggal)', $today)->countAllResults(),
            'total_bulan_ini' => $this->where('MONTH(tanggal)', $thisMonth)
                                      ->where('YEAR(tanggal)', $thisYear)
                                      ->countAllResults(),
            'total_tahun_ini' => $this->where('YEAR(tanggal)', $thisYear)->countAllResults(),
            'total_semua' => $this->countAll(),
            'total_pengunjung' => $this->where('jenis_tamu', 'pengunjung')->countAllResults(),
            'total_tamu' => $this->where('jenis_tamu', 'tamu')->countAllResults(),
        ];
    }
}
