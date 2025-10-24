<?php

namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'course_id', 'enrollment_date'];
    protected $useTimestamps = false;

    // ✅ Student enrollments
    public function getEnrollmentsByStudent($userId)
    {
        return $this->select('courses.title, courses.description, enrollments.enrollment_date')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->where('enrollments.user_id', $userId)
            ->findAll();
    }

    // ✅ Teacher enrollments
    public function getEnrollmentsByTeacher($teacherId)
    {
        return $this->select('courses.title, users.name AS student_name, users.email, enrollments.enrollment_date')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->join('users', 'users.id = enrollments.user_id')
            ->where('courses.instructor_id', $teacherId)
            ->orderBy('courses.title', 'ASC')
            ->findAll();
    }

    // ✅ Admin enrollments
    public function getAllEnrollmentsWithDetails()
    {
        return $this->select('users.name AS student_name, users.email, courses.title, courses.instructor_id, enrollments.enrollment_date')
            ->join('users', 'users.id = enrollments.user_id')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->orderBy('enrollments.enrollment_date', 'DESC')
            ->findAll();
    }
}
