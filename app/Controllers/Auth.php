<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends BaseController
{
    protected $helpers = ['form', 'url'];

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
                return redirect()->to('/announcements')->with('success', 'You have successfully logged in.');
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

        $data = [
            'title'           => 'Dashboard',
            'user_name'       => $userName,
            'user_role'       => $userRole,
            'user_id'         => $userId,
            'courses'         => $courses,
            'enrolledCourses' => $enrolledCourses,
        ];

        return view('auth/dashboard', $data);
    }
}
