<?php

namespace App\Repositories;

use App\Models\TamuModel;

/**
 * Repository untuk mengelola operasi data tamu
 * Memisahkan business logic dari controller
 *
 * @see https://codeigniter.com/user_guide/models/repository.html
 */
class TamuRepository
{
    /**
     * Instance model Tamu
     */
    protected TamuModel $model;

    /**
     * Konstruktor - Inisialisasi model
     */
    public function __construct()
    {
        $this->model = new TamuModel();
    }

    /**
     * Menyimpan data tamu baru ke database
     *
     * @param array $data Data tamu yang akan disimpan
     * @return bool|int ID data jika berhasil, false jika gagal
     */
    public function saveGuest(array $data): bool|int
    {
        return $this->model->insert($data);
    }

    /**
     * Mendapatkan daftar tamu terbaru
     *
     * @param int $limit Jumlah data yang akan diambil
     * @return array Daftar tamu
     */
    public function getRecentGuests(int $limit = 10): array
    {
        return $this->model
            ->orderBy('tanggal', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Mendapatkan data tamu berdasarkan ID
     *
     * @param int $id ID tamu
     * @return array|null Data tamu atau null jika tidak ditemukan
     */
    public function findById(int $id): ?array
    {
        return $this->model->find($id);
    }

    /**
     * Menghitung jumlah tamu pada hari tertentu
     *
     * @param string $date Tanggal dalam format Y-m-d
     * @return int Jumlah tamu
     */
    public function countGuestsByDate(string $date): int
    {
        return $this->model
            ->where('DATE(tanggal)', $date)
            ->countAllResults();
    }

    /**
     * Mendapatkan nomor urut antrian tamu pada hari ini
     *
     * @param int $tamuId ID tamu yang akan dihitung antriannya
     * @return int Nomor antrian
     */
    public function getQueueNumber(int $tamuId): int
    {
        $today = date('Y-m-d');

        return $this->model
            ->where('DATE(tanggal)', $today)
            ->where('id <=', $tamuId)
            ->countAllResults();
    }
}
