<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run()
    {
        // Get the teacher user ID dynamically
        $teacherId = $this->db->table('users')->where('role', 'teacher')->get()->getRow()->id ?? 1;
        
        $data = [
            [
                'title'         => 'ITE 401 - Mobile Application Development',
                'description'   => 'Design and develop Android and iOS mobile applications using modern frameworks.',
                'instructor_id' => $teacherId,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'title'         => 'ITE 402 - Cloud Computing',
                'description'   => 'Covers cloud infrastructure, deployment models, and services such as AWS and Azure.',
                'instructor_id' => $teacherId,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'title'         => 'ITE 403 - Cybersecurity Fundamentals',
                'description'   => 'Introduction to network security, threat analysis, and data protection strategies.',
                'instructor_id' => $teacherId,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'title'         => 'ITE 404 - Artificial Intelligence',
                'description'   => 'Explore AI principles, machine learning, neural networks, and intelligent systems.',
                'instructor_id' => $teacherId,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'title'         => 'ITE 405 - Capstone Project 1',
                'description'   => 'Students develop project proposals, feasibility studies, and initial prototypes.',
                'instructor_id' => $teacherId,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
            [
                'title'         => 'ITE 406 - Capstone Project 2',
                'description'   => 'Implementation and presentation of final system projects for evaluation.',
                'instructor_id' => $teacherId,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],
        ];

        $this->db->table('courses')->insertBatch($data);
    }
}
