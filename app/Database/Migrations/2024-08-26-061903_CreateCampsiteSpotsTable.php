<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCampsiteSpotsTable extends Migration
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
            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'number_of_guests' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('campsite_spots');
    }

    public function down()
    {
        $this->forge->dropTable('campsite_spots');
    }
}