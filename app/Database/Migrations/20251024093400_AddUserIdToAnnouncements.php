<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserIdToAnnouncements extends Migration
{
    public function up()
    {
        $this->forge->addColumn('announcements', [
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'after'      => 'id'
            ],
        ]);

        // Add foreign key constraint
        $this->forge->processIndexes('announcements');
        $this->db->query('ALTER TABLE announcements ADD CONSTRAINT announcements_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->forge->dropForeignKey('announcements', 'announcements_user_id_foreign');
        $this->forge->dropColumn('announcements', 'user_id');
    }
}
