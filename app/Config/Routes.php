<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ✅ Public routes
$routes->get('/', 'Home::index');
$routes->get('about', 'Home::about');
$routes->get('contact', 'Home::contact');

// ✅ Authentication routes
$routes->match(['get', 'post'], 'auth/login', 'Auth::login');
$routes->match(['get', 'post'], 'auth/register', 'Auth::register');
$routes->get('auth/logout', 'Auth::logout');

// ✅ Optional short aliases for convenience
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::login');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::register');
$routes->get('logout', 'Auth::logout');

// ===============================
//   Admin Routes (Protected)
// ===============================
$routes->group('admin', ['filter' => 'roleauth:admin'], function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('course/(:num)/upload', 'Materials::upload/$1');
    $routes->post('course/(:num)/upload', 'Materials::upload/$1');
});

// ===============================
//   Teacher Routes (Protected)
// ===============================
$routes->group('teacher', ['filter' => 'roleauth:teacher'], function($routes) {
    $routes->get('dashboard', 'Teacher::dashboard');
});

// ===============================
//   Student Routes (Protected)
// ===============================
$routes->group('student', ['filter' => 'roleauth:student'], function($routes) {
    $routes->get('dashboard', 'Student::dashboard');
});

// ===============================
//   Announcements REMOVED - No longer available
// ===============================
// All announcement routes have been disabled

// ===============================
//   Materials Routes
// ===============================
$routes->get('materials/download/(:num)', 'Materials::download/$1');
$routes->get('materials/delete/(:num)', 'Materials::delete/$1');
$routes->get('materials/course/(:num)', 'Materials::viewCourse/$1');

// ===============================
//   Other Routes
// ===============================
$routes->get('dashboard', 'Auth::dashboard');
$routes->post('course/enroll', 'Course::enroll');
$routes->get('course/dashboard', 'Course::dashboard');

