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

        // Get current role
        $role = $session->get('role'); // admin, teacher, or student

        // Determine allowed roles from route argument
        $allowedRoles = $arguments[0] ?? null;

        // If no roles specified, allow access
        if (!$allowedRoles) {
            return $request;
        }

        // Split comma-separated roles if needed
        if (is_string($allowedRoles)) {
            $allowedRoles = array_map('trim', explode(',', $allowedRoles));
        } elseif (!is_array($allowedRoles)) {
            $allowedRoles = [$allowedRoles];
        }

        // Check if user's role is in the allowed roles
        if (!in_array($role, $allowedRoles)) {
            log_message('warning', "Access denied for role '{$role}' to route. Allowed roles: " . implode(', ', $allowedRoles));
            return redirect()->to('/dashboard')->with('error', 'Access Denied: Insufficient Permissions.');
        }

        return $request; // proceed if authorized
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No after logic
    }
}
