<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run()
    {
        // Get the admin user ID dynamically
        $adminId = $this->db->table('users')->where('role', 'admin')->get()->getRow()->id ?? 1;
        
        $data = [
            [
                'user_id' => $adminId,
                'title' => 'Welcome to the New Portal',
                'content' => 'This is the new Online Student Portal. Explore features and stay updated!',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => $adminId,
                'title' => 'Midterm Exams Schedule',
                'content' => 'Midterm exams will start on October 25, 2025. Please check your schedules.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('announcements')->insertBatch($data);
    }
}
