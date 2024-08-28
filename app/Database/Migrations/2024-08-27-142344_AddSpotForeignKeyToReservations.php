<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReservationsTableWithForeignKey extends Migration
{
    public function up()
    {
        // Drop the existing reservations table if it exists
        $this->forge->dropTable('reservations', true);

        // Create the new reservations table with the foreign key constraint
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'phone_number' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'date_reservation' => [
                'type' => 'DATE',
            ],
            'guests' => [
                'type' => 'INTEGER',
                'constraint' => 11,
            ],
            'spot' => [
                'type' => 'INTEGER',
                'unsigned' => true,
            ],
            'comment' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addForeignKey('spot', 'campsite_spots', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('reservations');
    }

    public function down()
    {
        // Drop the reservations table if this migration is rolled back
        $this->forge->dropTable('reservations', true);
    }
}