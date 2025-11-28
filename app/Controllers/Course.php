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

        try {
            // Start transaction
            $db->transStart();

            // Insert enrollment
            $enrollmentId = $enrollmentModel->insert($data);
            
            if (!$enrollmentId) {
                throw new \RuntimeException('Failed to create enrollment');
            }

            // Get course details
            $course = $db->table('courses')
                        ->where('id', $course_id)
                        ->get()
                        ->getRowArray();

            if ($course) {
                // Get the notification model
                $notificationModel = new \App\Models\NotificationModel();
                
                // Create notification for the student
                $message = 'You have successfully enrolled in "' . $course['title'] . '"';
                $notificationModel->createNotification($user_id, $message, 'enrollment', ['course_id' => $course_id]);
                
                // If this is a teacher's course, also notify the teacher
                if (!empty($course['instructor_id'])) {
                    $teacherMessage = 'New student enrolled in your course: "' . $course['title'] . '"';
                    $notificationModel->createNotification(
                        $course['instructor_id'], 
                        $teacherMessage, 
                        'new_student', 
                        ['course_id' => $course_id, 'student_id' => $user_id]
                    );
                }
            }

            // Commit transaction
            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                throw new \RuntimeException('Transaction failed');
            }
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Enrollment successful!',
                'redirect' => base_url('dashboard')
            ]);
            
        } catch (\Exception $e) {
            // Rollback transaction on error
            $db->transRollback();
            
            log_message('error', 'Enrollment error: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'An error occurred during enrollment. Please try again.'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Enrollment failed. Please try again.'
        ]);
    }

    // ✅ DASHBOARD for all roles
    /**
     * Delete a notification
     */
    public function deleteNotification($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method.'
            ]);
        }

        if (empty($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No notification ID provided.'
            ]);
        }

        $db = db_connect();
        $session = session();
        $userId = $session->get('user_id');

        // Verify the notification belongs to the current user
        $notification = $db->table('notifications')
                         ->where('id', $id)
                         ->where('user_id', $userId)
                         ->get()
                         ->getRowArray();

        if (!$notification) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Notification not found or access denied.'
            ]);
        }

        // Delete the notification
        $db->table('notifications')->delete(['id' => $id]);

        // Get updated unread count
        $unreadCount = $db->table('notifications')
                         ->where('user_id', $userId)
                         ->where('is_read', 0)
                         ->countAllResults();

        return $this->response->setJSON([
            'success' => true,
            'unreadCount' => $unreadCount
        ]);
    }

    /**
     * Search courses - Server-side search functionality
     * Accepts GET/POST requests with search term parameter
     * Returns JSON for AJAX requests or renders view for regular requests
     */
    public function search()
    {
        $session = session();
        $db = db_connect();

        if (!$session->get('isLoggedIn')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'You must be logged in to search courses.'
                ]);
            }
            return redirect()->to('/auth/login');
        }

        $searchTerm = $this->request->getVar('search') ?? '';
        $searchTerm = trim($searchTerm);

        // Build the query
        $query = $db->table('courses');

        // Apply search filter if search term is provided
        if (!empty($searchTerm)) {
            $query->like('title', $searchTerm)
                  ->orLike('description', $searchTerm);
        }

        // Execute the query
        $results = $query->get()->getResultArray();

        // Log the search activity
        log_message('info', "User {$session->get('user_id')} searched for: '{$searchTerm}' - Found " . count($results) . " results");

        // If AJAX request, return JSON
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'searchTerm' => $searchTerm,
                'results' => $results,
                'count' => count($results)
            ]);
        }

        // Otherwise, render the view with results
        $user_id   = $session->get('user_id');
        $user_role = $session->get('role');
        $user_name = $session->get('user_name');

        // Get enrolled courses for comparison
        $enrolledCourses = [];
        if ($user_role === 'student') {
            $enrolledCourses = $db->table('enrollments')
                ->select('course_id')
                ->where('user_id', $user_id)
                ->get()
                ->getResultArray();
            $enrolledCourses = array_column($enrolledCourses, 'course_id');
        }

        // Get user's notifications
        $notifications = $db->table('notifications')
                          ->where('user_id', $user_id)
                          ->orderBy('created_at', 'DESC')
                          ->limit(10)
                          ->get()
                          ->getResultArray();

        // Count unread notifications
        $unreadCount = $db->table('notifications')
                         ->where('user_id', $user_id)
                         ->where('is_read', 0)
                         ->countAllResults();

        $data = [
            'user_name'        => $user_name,
            'user_role'        => $user_role,
            'title'            => 'Search Results',
            'searchTerm'       => $searchTerm,
            'courses'          => $results,
            'enrolledCourses'  => $enrolledCourses,
            'notifications'    => $notifications,
            'unreadCount'      => $unreadCount
        ];

        return view('courses/search_results', $data);
    }

    /**
     * Dashboard for all roles
     */
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

        // Get user's notifications
        $notifications = $db->table('notifications')
                          ->where('user_id', $user_id)
                          ->orderBy('created_at', 'DESC')
                          ->limit(10)
                          ->get()
                          ->getResultArray();

        // Count unread notifications
        $unreadCount = $db->table('notifications')
                         ->where('user_id', $user_id)
                         ->where('is_read', 0)
                         ->countAllResults();

        // Debug: Log the unread count
        log_message('debug', "Unread count for user {$user_id}: {$unreadCount}");
        
        $data = [
            'user_name'    => $user_name,
            'user_role'    => $user_role,
            'title'        => 'Dashboard',
            'notifications' => $notifications,
            'unreadCount'  => $unreadCount
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
