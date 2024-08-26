<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserIdToApiKeysTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('api_keys', [
            'user_id' => [
                'type'       => 'INTEGER',
                'unsigned'   => true,
                'null'       => true, // Change to false if user_id is required
                'after'      => 'id', // Position the column after 'id'
            ],
        ]);

        // Optionally, add a foreign key constraint if there's a users table
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropForeignKey('api_keys', 'api_keys_user_id_foreign');
        $this->forge->dropColumn('api_keys', 'user_id');
    }
}
