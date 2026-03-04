<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration untuk membuat tabel tamu
 * Menggunakan Single Table Inheritance untuk tamu dan pengunjung
 */
class CreateTamuTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'jenis_tamu' => [
                'type'       => 'ENUM',
                'constraint' => ['pengunjung', 'tamu'],
                'default'    => 'pengunjung',
            ],
            'tanggal' => [
                'type'    => 'DATETIME',
                'null'    => false,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'alamat' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'instansi' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'hp' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'tujuan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('jenis_tamu');
        $this->forge->addKey('tanggal');
        $this->forge->createTable('tamu');
    }

    public function down()
    {
        $this->forge->dropTable('tamu');
    }
}
