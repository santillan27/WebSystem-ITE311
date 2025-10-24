<?php

namespace App\Controllers;

use App\Models\EnrollmentModel;
use CodeIgniter\Controller;

class Course extends BaseController
{
    // ✅ ENROLL FUNCTION
    public function enroll()
    {
        $session = session();
        $db = db_connect();

        if (!$session->get('isLoggedIn')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'You must be logged in to enroll.'
            ]);
        }

        $user_id = $session->get('user_id');
        $course_id = $this->request->getPost('course_id');

        if (empty($course_id)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No course selected.'
            ]);
        }

        $enrollmentModel = new EnrollmentModel();
        $exists = $enrollmentModel->where('user_id', $user_id)
                                  ->where('course_id', $course_id)
                                  ->first();

        if ($exists) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'You are already enrolled in this course.'
            ]);
        }

        $data = [
            'user_id'         => $user_id,
            'course_id'       => $course_id,
            'enrollment_date' => date('Y-m-d H:i:s')
        ];

        if ($enrollmentModel->insert($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Enrollment successful!'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Enrollment failed. Please try again.'
        ]);
    }

    // ✅ DASHBOARD for all roles
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

        $data = [
            'user_name' => $user_name,
            'user_role' => $user_role,
            'title'     => 'Dashboard'
        ];

        // 🔹 STUDENT DASHBOARD
        if ($user_role === 'student') {
            $data['courses'] = $db->table('courses')->get()->getResultArray();

            $data['enrolledCourses'] = $db->table('enrollments')
                ->select('courses.id, courses.title, courses.description, enrollments.enrollment_date')
                ->join('courses', 'courses.id = enrollments.course_id')
                ->where('enrollments.user_id', $user_id)
                ->get()
                ->getResultArray();
        }

        // 🔹 TEACHER DASHBOARD
        if ($user_role === 'teacher') {
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
        }

        // 🔹 ADMIN DASHBOARD
        if ($user_role === 'admin') {
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
            
            // ✅ Fetch announcements with poster info
            $data['announcements'] = $db->table('announcements')
                ->select('announcements.*, users.name as posted_by')
                ->join('users', 'users.id = announcements.user_id')
                ->orderBy('announcements.created_at', 'DESC')
                ->get()
                ->getResultArray();
        }
        
        // ✅ Fetch announcements for all roles
        if (!isset($data['announcements'])) {
            $data['announcements'] = $db->table('announcements')
                ->select('announcements.*, users.name as posted_by')
                ->join('users', 'users.id = announcements.user_id')
                ->orderBy('announcements.created_at', 'DESC')
                ->get()
                ->getResultArray();
        }

        return view('auth/dashboard', $data);
    }
}
