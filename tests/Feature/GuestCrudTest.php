<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\TamuModel;

/**
 * @internal
 */
final class GuestCrudTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;

    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = 'App';

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testAdminCanCreateTamuViaAjax()
    {
        $data = [
            'jenis_tamu' => 'tamu',
            'nama'       => 'Test Ajax Tamu',
            'instansi'   => 'PT Testing',
            'hp'         => '08123456789',
            'tujuan'     => 'Meeting'
        ];

        $result = $this->withSession(['isLoggedIn' => true, 'role' => 'admin'])
                       ->post('/admin/tamu/store', $data);
        
        $result->assertStatus(200);
        $result->assertJSONExact(['status' => 'success', 'message' => 'Data berhasil disimpan']);

        $this->seeInDatabase('tamu', ['nama' => 'Test Ajax Tamu']);
    }

    public function testAdminCanUpdateTamuViaAjax()
    {
        $model = new TamuModel();
        $id = $model->insert([
            'jenis_tamu' => 'tamu',
            'tanggal'    => date('Y-m-d H:i:s'),
            'nama'       => 'Old Name',
            'instansi'   => 'Old Instansi',
            'tujuan'     => 'Old Tujuan'
        ]);

        $updateData = [
            'nama'     => 'New Name',
            'instansi' => 'New Instansi',
            'tujuan'   => 'New Tujuan'
        ];

        $result = $this->withSession(['isLoggedIn' => true, 'role' => 'admin'])
                       ->post("/admin/tamu/update/{$id}", $updateData);
        
        $result->assertStatus(200);
        $result->assertJSONExact(['status' => 'success', 'message' => 'Data berhasil diupdate']);

        $this->seeInDatabase('tamu', ['id' => $id, 'nama' => 'New Name']);
    }
}
