<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\MaterialModel;
use CodeIgniter\Controller;

class Auth extends BaseController
{
    protected $helpers = ['form', 'url', 'download'];

    // ✅ LOGIN
    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/course/dashboard');
        }

        if ($this->request->getMethod() === 'GET') {
            return view('auth/login');
        }

        if ($this->request->getMethod() === 'POST') {
            if (!$this->validate([
                'email'    => 'required|valid_email',
                'password' => 'required|min_length[6]'
            ])) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $userModel = new UserModel();
            $email     = $this->request->getPost('email');
            $password  = $this->request->getPost('password');
            $user      = $userModel->where('email', $email)->first();

            if (!$user) {
                return redirect()->back()->withInput()->with('error', 'Email not found.');
            }

            if (!password_verify($password, $user['password'])) {
                return redirect()->back()->withInput()->with('error', 'Incorrect password.');
            }

            if (isset($user['status']) && $user['status'] !== 'active') {
                return redirect()->back()->with('error', 'Your account is not active. Please contact admin.');
            }

            // ✅ Store session
            session()->set([
                'user_id'    => $user['id'],
                'user_name'  => $user['name'],
                'role'       => strtolower($user['role']),
                'isLoggedIn' => true,
            ]);

            // ✅ Redirect based on role
            $role = strtolower($user['role']);
            if ($role === 'admin') {
                return redirect()->to('/admin/dashboard')->with('success', 'You have successfully logged in.');
            } elseif ($role === 'teacher') {
                return redirect()->to('/teacher/dashboard')->with('success', 'You have successfully logged in.');
            } else {
                // Student
                return redirect()->to('/dashboard')->with('success', 'You have successfully logged in.');
            }
        }
    }

    // ✅ FIXED REGISTER FUNCTION
    public function register()
    {
        if ($this->request->getMethod() === 'GET') {
            return view('auth/register');
        }

        if ($this->request->getMethod() === 'POST') {
            if (!$this->validate([
                'name'              => 'required|min_length[3]|max_length[255]',
                'email'             => 'required|valid_email|is_unique[users.email]',
                'password'          => 'required|min_length[6]',
                'confirm_password'  => 'required|matches[password]',
                'role'              => 'required|in_list[admin,teacher,student]',
            ])) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $userModel = new UserModel();

            // ✅ Hash password securely
            $hashedPassword = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

            $data = [
                'name'     => $this->request->getPost('name'),
                'email'    => $this->request->getPost('email'),
                'password' => $hashedPassword,
                'role'     => strtolower($this->request->getPost('role')),
                'status'   => 'active', // optional, if your DB has this column
            ];

            // ✅ Insert user directly
            if ($userModel->insert($data)) {
                return redirect()->to('/auth/login')->with('success', 'Account created successfully! You can now login.');
            } else {
                return redirect()->back()->withInput()->with('error', 'Registration failed. Please try again.');
            }
        }
    }

    // ✅ LOGOUT
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login')->with('success', 'You have been logged out.');
    }

    // ✅ DASHBOARD
    public function dashboard()
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Please login first.');
        }

        $db = \Config\Database::connect();
        $userId   = $session->get('user_id');
        $userRole = strtolower($session->get('role'));
        $userName = $session->get('user_name');

        // ✅ Fetch notifications
        $notifications = $db->table('notifications')
                          ->where('user_id', $userId)
                          ->orderBy('created_at', 'DESC')
                          ->limit(10)
                          ->get()
                          ->getResultArray();

        // ✅ Count unread notifications
        $unreadCount = $db->table('notifications')
                         ->where('user_id', $userId)
                         ->where('is_read', 0)
                         ->countAllResults();

        // ✅ Fetch all courses
        $courses = $db->table('courses')
                      ->select('id, title, description')
                      ->get()
                      ->getResultArray();

        // ✅ Fetch enrolled courses
        $enrolledCourses = $db->table('enrollments')
            ->select('courses.id, courses.title, courses.description, enrollments.enrollment_date')
            ->join('courses', 'enrollments.course_id = courses.id')
            ->where('enrollments.user_id', $userId)
            ->get()
            ->getResultArray();

        foreach ($enrolledCourses as &$en) {
            if (empty($en['enrollment_date']) || $en['enrollment_date'] == '0000-00-00 00:00:00') {
                $en['enrollment_date'] = '(No date recorded)';
            }
        }

        // ✅ Fetch materials for student's enrolled courses
        $studentMaterials = [];
        if ($userRole === 'student') {
            $materialModel = new MaterialModel();
            $studentMaterials = $materialModel->getMaterialsByEnrolledCourses($userId);
        }

        // ✅ Fetch teacher courses if teacher
        $teacherCourses = [];
        if ($userRole === 'teacher') {
            $teacherCourses = $db->table('courses')
                ->where('instructor_id', $userId)
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
            $teacherCourses = $teacherCoursesData;
        }

        // ✅ Fetch admin data if admin
        $allEnrollments = [];
        $announcements = [];
        if ($userRole === 'admin') {
            $allEnrollments = $db->table('enrollments')
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
        }

        // ✅ Fetch announcements for all roles
        $announcements = $db->table('announcements')
            ->select('announcements.*, users.name as posted_by')
            ->join('users', 'users.id = announcements.user_id')
            ->orderBy('announcements.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title'            => 'Dashboard',
            'user_name'        => $userName,
            'user_role'        => $userRole,
            'user_id'          => $userId,
            'notifications'    => $notifications,
            'unreadCount'      => $unreadCount,
            'courses'          => $courses,
            'enrolledCourses'  => $enrolledCourses,
            'studentMaterials' => $studentMaterials,
            'teacherCourses'   => $teacherCourses,
            'allEnrollments'   => $allEnrollments,
            'announcements'    => $announcements,
        ];

        return view('auth/dashboard', $data);
    }
}
