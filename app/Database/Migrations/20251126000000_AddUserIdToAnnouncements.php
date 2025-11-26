<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserIdToAnnouncements extends Migration
{
    public function up()
    {
        $fields = [
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'after' => 'id',
                'null' => true,
            ]
        ];

        $this->forge->addColumn('announcements', $fields);
        
        // Add foreign key constraint if needed
        // $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'SET NULL');
    }

    public function down()
    {
        // Drop the column if rolling back
        $this->forge->dropColumn('announcements', 'user_id');
    }
}
