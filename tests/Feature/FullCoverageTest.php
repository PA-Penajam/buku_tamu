<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

final class FullCoverageTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;

    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = 'App';

    public function testHomeRedirectsToPengunjung()
    {
        $result = $this->get('/');
        $result->assertStatus(200);
    }

    public function testPengunjungForm()
    {
        $result = $this->get('/pengunjung');
        $result->assertStatus(200);
        $result->assertSee('pengunjung');
    }

    public function testTamuForm()
    {
        $result = $this->get('/tamu');
        $result->assertStatus(200);
        $result->assertSee('tamu');
    }

    public function testStoreTamuPublic()
    {
        $data = [
            'jenis_tamu' => 'tamu',
            'nama'       => 'Public Tamu',
            'instansi'   => 'Public Instansi',
            'hp'         => '08123456789',
            'tujuan'     => 'Public Tujuan'
        ];

        $result = $this->post('/tamu/store', $data);
        $result->assertRedirectTo('/');
        $this->seeInDatabase('tamu', ['nama' => 'Public Tamu']);
    }

    public function testStorePengunjungPublicValidationError()
    {
        $data = [
            'jenis_tamu' => 'pengunjung',
            'nama'       => '',
        ];

        $result = $this->post('/tamu/store', $data);
        $result->assertRedirect();
    }

    public function testLoginView()
    {
        $result = $this->get('/login');
        $result->assertStatus(200);
    }

    public function testLoginAuthenticateFailed()
    {
        $result = $this->post('/login', ['password' => 'wrong']);
        $result->assertRedirect();
    }

    public function testLoginAuthenticateSuccess()
    {
        $result = $this->post('/login', ['password' => 'admin123']);
        $result->assertRedirectTo('/admin');
    }

    public function testLogout()
    {
        $result = $this->withSession(['isLoggedIn' => true])->get('/logout');
        $result->assertRedirectTo('/login');
    }

    public function testAdminDashboard()
    {
        $result = $this->withSession(['isLoggedIn' => true])->get('/admin');
        $result->assertStatus(200);
    }

    public function testAdminLaporan()
    {
        $result = $this->withSession(['isLoggedIn' => true])->get('/admin/laporan');
        $result->assertStatus(200);
    }

    public function testAdminChartData()
    {
        $result = $this->withSession(['isLoggedIn' => true])->get('/admin/chart');
        $result->assertStatus(200);
        $result->assertJSONFragment(['labels' => ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']]);
    }

    public function testTamuModelMethods()
    {
        $model = new \App\Models\TamuModel();
        
        // Insert sample data
        $model->insert([
            'jenis_tamu' => 'pengunjung',
            'tanggal'    => date('Y-m-d H:i:s'),
            'nama'       => 'Test Pengunjung',
            'alamat'     => 'Test Alamat',
            'tujuan'     => 'Test Tujuan'
        ]);

        $model->insert([
            'jenis_tamu' => 'tamu',
            'tanggal'    => date('Y-m-d H:i:s'),
            'nama'       => 'Test Tamu',
            'instansi'   => 'Test Instansi',
            'tujuan'     => 'Test Tujuan'
        ]);

        $pengunjung = $model->pengunjung()->findAll();
        $this->assertNotEmpty($pengunjung);

        $tamu = $model->tamu()->findAll();
        $this->assertNotEmpty($tamu);

        $stats = $model->ringkasanDashboard();
        $this->assertEquals(2, $stats['total_semua']);
        $this->assertEquals(1, $stats['total_pengunjung']);
        $this->assertEquals(1, $stats['total_tamu']);

        $bulanan = $model->statistikBulanan(date('Y'));
        $this->assertNotEmpty($bulanan);

        $filter = $model->filterBulanTahun((int)date('m'), (int)date('Y'))->findAll();
        $this->assertNotEmpty($filter);
    }

    public function testAdminDeleteTamu()
    {
        $model = new \App\Models\TamuModel();
        $id = $model->insert([
            'jenis_tamu' => 'tamu',
            'tanggal'    => date('Y-m-d H:i:s'),
            'nama'       => 'Test Delete',
            'tujuan'     => 'Delete'
        ]);

        $result = $this->withSession(['isLoggedIn' => true, 'role' => 'admin'])
                       ->post("/admin/tamu/delete/{$id}");
        $result->assertStatus(200);
        $result->assertJSONExact(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        
        $this->dontSeeInDatabase('tamu', ['id' => $id]);
    }

    public function testAdminDeleteTamuNotFound()
    {
        $result = $this->withSession(['isLoggedIn' => true, 'role' => 'admin'])
                       ->post("/admin/tamu/delete/9999");
        $result->assertStatus(200);
    }

}
