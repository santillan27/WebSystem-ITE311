<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $helpers = ['form', 'url'];

    // ✅ LOGIN
    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
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
            $user      = $userModel->findUserByEmail($email);

            if (!$user) {
                return redirect()->back()->withInput()->with('error', 'Email not found.');
            }

            if (!password_verify($password, $user['password'])) {
                return redirect()->back()->withInput()->with('error', 'Incorrect password.');
            }

            if (isset($user['status']) && $user['status'] !== 'active') {
                return redirect()->back()->with('error', 'Your account is not active. Please contact admin.');
            }

            session()->set([
                'user_id'   => $user['id'],
                'user_name' => $user['name'],
                'user_role' => strtolower($user['role']),
                'logged_in' => true,
            ]);

            return redirect()->to('/dashboard')->with('success', 'You have successfully logged in.');
        }
    }

    // ✅ REGISTER
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
            $result = $userModel->createAccount([
                'name'     => $this->request->getPost('name'),
                'email'    => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'role'     => strtolower($this->request->getPost('role')),
            ]);

            if (is_array($result)) {
                return redirect()->back()->withInput()->with('errors', $result);
            }

            return redirect()->to('/auth/login')->with('success', 'Account created successfully. You can now login.');
        }
    }

    // ✅ LOGOUT
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login')->with('success', 'You have been logged out.');
    }

   public function dashboard()
{
    $session = session();

    if (!$session->get('logged_in')) {
        return redirect()->to('/auth/login')->with('error', 'Please login first.');
    }

    $db        = \Config\Database::connect();
    $userModel = new UserModel();

    $userId   = $session->get('user_id');
    $userRole = strtolower($session->get('user_role'));
    $user     = $userModel->find($userId);

    // ✅ Prepare data for the dashboard view
    $data = [
        'title'      => 'Dashboard',
        'user'       => $user,
        'user_name'  => $user['name'] ?? 'Unknown',
        'user_email' => $user['email'] ?? 'N/A',
        'user_role'  => $userRole,
    ];

    return view('auth/dashboard', $data);
}
}