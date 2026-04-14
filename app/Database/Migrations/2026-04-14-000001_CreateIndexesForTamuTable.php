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
        $this->forge->processIndexes('tamu');
    }

    public function down()
    {
        $this->forge->dropKey('tamu', 'tanggal');
        $this->forge->dropKey('tamu', 'jenis_tamu');
        $this->forge->dropKey('tamu', 'created_at');
    }
}
