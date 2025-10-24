<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Admin extends BaseController
{
    public function dashboard()
    {
        $session = session();
        $db = db_connect();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $user_id   = $session->get('user_id');
        $user_role = $session->get('role');
        $user_name = $session->get('user_name');

        // Fetch announcements with poster info
        $data['announcements'] = $db->table('announcements')
            ->select('announcements.*, users.name as posted_by')
            ->join('users', 'users.id = announcements.user_id')
            ->orderBy('announcements.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // Fetch all enrollments
        $data['allEnrollments'] = $db->table('enrollments')
            ->select('courses.title AS course_title, 
                      instructors.name AS instructor_name, 
                      students.name AS student_name, 
                      enrollments.enrollment_date')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->join('users AS instructors', 'instructors.id = courses.instructor_id')
            ->join('users AS students', 'students.id = enrollments.user_id')
            ->orderBy('enrollments.enrollment_date', 'DESC')
            ->get()
            ->getResultArray();

        return view('admin_dashboard', $data);
    }
}
