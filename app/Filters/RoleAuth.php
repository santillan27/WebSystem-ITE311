<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        // Get current role and page path
        $role = $session->get('role'); // admin, teacher, or student
        $uri  = service('uri')->getPath(); // e.g. "teacher/dashboard"

        // Determine allowed role from route group argument
        $allowedRole = $arguments[0] ?? null;

        // Block access if role doesn't match
        if ($allowedRole && $role !== $allowedRole) {
            return redirect()->to('/announcements')->with('error', 'Access Denied: Insufficient Permissions.');
        }

        return $request; // proceed if authorized
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No after logic
    }
}
