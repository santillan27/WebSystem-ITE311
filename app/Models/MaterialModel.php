<?php

namespace App\Models;

use CodeIgniter\Model;

class MaterialModel extends Model
{
    protected $table = 'materials';
    protected $primaryKey = 'id';
    protected $allowedFields = ['course_id', 'file_name', 'file_path', 'created_at'];
    protected $useTimestamps = false;

    /**
     * Insert a new material record
     * @param array $data
     * @return int|bool Insert ID or false on failure
     */
    public function insertMaterial($data)
    {
        return $this->insert($data);
    }

    /**
     * Get all materials for a specific course
     * @param int $courseId
     * @return array
     */
    public function getMaterialsByCourse($courseId)
    {
        return $this->where('course_id', $courseId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get a single material by ID
     * @param int $materialId
     * @return array|null
     */
    public function getMaterialById($materialId)
    {
        return $this->find($materialId);
    }

    /**
     * Delete a material by ID
     * @param int $materialId
     * @return bool
     */
    public function deleteMaterial($materialId)
    {
        return $this->delete($materialId);
    }

    /**
     * Get materials for courses a student is enrolled in
     * @param int $userId
     * @return array
     */
    public function getMaterialsByEnrolledCourses($userId)
    {
        return $this->select('materials.*, courses.title as course_title')
                    ->join('courses', 'courses.id = materials.course_id')
                    ->join('enrollments', 'enrollments.course_id = courses.id')
                    ->where('enrollments.user_id', $userId)
                    ->orderBy('materials.created_at', 'DESC')
                    ->findAll();
    }
}
