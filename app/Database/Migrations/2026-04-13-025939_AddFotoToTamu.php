<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFotoToTamu extends Migration
{
    public function up()
    {
        $fields = [
            'foto' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ];
        $this->forge->addColumn('tamu', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('tamu', 'foto');
    }
}
