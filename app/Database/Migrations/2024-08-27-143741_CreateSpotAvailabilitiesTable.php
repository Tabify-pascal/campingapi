<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSpotAvailabilitiesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'spot_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'date' => [
                'type' => 'DATE',
            ],
            'is_available' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('spot_id', 'campsite_spots', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('spot_availabilities');
    }

    public function down()
    {
        $this->forge->dropTable('spot_availabilities', true);
    }
}