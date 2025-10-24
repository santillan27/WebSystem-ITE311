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
//   Announcements (Accessible by Students)
// ===============================
$routes->get('announcements', 'Announcement::index');

// ===============================
//   Announcement Management (Admin Only)
// ===============================
$routes->get('announcement', 'Announcement::index');
$routes->get('announcement/create', 'Announcement::create');
$routes->post('announcement/store', 'Announcement::store');
$routes->get('announcement/edit/(:num)', 'Announcement::edit/$1');
$routes->post('announcement/update/(:num)', 'Announcement::update/$1');
$routes->get('announcement/delete/(:num)', 'Announcement::delete/$1');

// ===============================
//   Other Routes
// ===============================
$routes->get('dashboard', 'Auth::dashboard');
$routes->post('course/enroll', 'Course::enroll');
$routes->get('course/dashboard', 'Course::dashboard');

