<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateIndexesForTamuTable extends Migration
{
    public function up()
    {
        $this->forge->addKey('tanggal');
        $this->forge->addKey('jenis_tamu');
        $this->forge->addKey('created_at');
        $this->forge->createTable('tamu');
    }

    public function down()
    {
        $this->forge->dropTable('tamu');
    }
}
