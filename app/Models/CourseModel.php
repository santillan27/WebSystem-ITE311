<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['title', 'description', 'instructor_id', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Get course by ID with instructor details
    public function getCourseWithInstructor($courseId)
    {
        return $this->select('courses.*, users.name as instructor_name, users.email as instructor_email')
                   ->join('users', 'users.id = courses.instructor_id')
                   ->where('courses.id', $courseId)
                   ->first();
    }

    // Get all courses with instructor details
    public function getAllCoursesWithInstructors()
    {
        return $this->select('courses.*, users.name as instructor_name')
                   ->join('users', 'users.id = courses.instructor_id')
                   ->findAll();
    }
}
