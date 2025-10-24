<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Teacher extends BaseController
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

        // Fetch announcements
        $data['announcements'] = $db->table('announcements')
            ->select('announcements.*, users.name as posted_by')
            ->join('users', 'users.id = announcements.user_id')
            ->orderBy('announcements.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // Fetch teacher courses and enrolled students
        $teacherCourses = $db->table('courses')
            ->where('instructor_id', $user_id)
            ->get()
            ->getResultArray();

        $teacherCoursesData = [];
        foreach ($teacherCourses as $course) {
            $students = $db->table('enrollments')
                ->select('users.name, enrollments.enrollment_date')
                ->join('users', 'users.id = enrollments.user_id')
                ->where('enrollments.course_id', $course['id'])
                ->get()
                ->getResultArray();

            $course['students'] = $students;
            $teacherCoursesData[] = $course;
        }

        $data['teacherCourses'] = $teacherCoursesData;

        return view('teacher_dashboard', $data);
    }
}
