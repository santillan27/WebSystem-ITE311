<?php

namespace App\Controllers;

use App\Models\MaterialModel;
use App\Models\EnrollmentModel;
use CodeIgniter\Controller;

class Materials extends BaseController
{
    protected $helpers = ['form', 'url', 'download'];

    /**
     * Display upload form and handle file upload
     * @param int $courseId
     */
    public function upload($courseId)
    {
        // Check if user is logged in and is admin or teacher
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Please login first.');
        }

        $userRole = strtolower($session->get('role'));
        if (!in_array($userRole, ['admin', 'teacher'])) {
            return redirect()->back()->with('error', 'You do not have permission to upload materials.');
        }

        // GET request - Display upload form
        if ($this->request->getMethod() === 'GET') {
            $db = \Config\Database::connect();
            $course = $db->table('courses')->where('id', $courseId)->get()->getRowArray();

            if (!$course) {
                return redirect()->back()->with('error', 'Course not found.');
            }

            $data = [
                'title' => 'Upload Material',
                'course' => $course,
            ];

            return view('materials/upload', $data);
        }

        // POST request - Handle file upload
        if ($this->request->getMethod() === 'POST') {
            // Validate the file
            $validation = \Config\Services::validation();
            $validation->setRules([
                'material_file' => [
                    'label' => 'Material File',
                    'rules' => 'uploaded[material_file]|max_size[material_file,10240]|ext_in[material_file,pdf,doc,docx,ppt,pptx,xls,xlsx,txt]',
                ],
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }

            $file = $this->request->getFile('material_file');

            if ($file->isValid() && !$file->hasMoved()) {
                // Define upload path
                $uploadPath = WRITEPATH . 'uploads/materials/';

                // Create directory if it doesn't exist
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                // Generate unique filename
                $newName = $file->getRandomName();
                
                // Move the file
                if ($file->move($uploadPath, $newName)) {
                    // Save to database
                    $materialModel = new MaterialModel();
                    $data = [
                        'course_id' => $courseId,
                        'file_name' => $file->getClientName(),
                        'file_path' => $newName,
                        'created_at' => date('Y-m-d H:i:s'),
                    ];

                    if ($materialModel->insertMaterial($data)) {
                        return redirect()->to('/admin/dashboard')->with('success', 'Material uploaded successfully!');
                    } else {
                        // Delete the uploaded file if database insert fails
                        unlink($uploadPath . $newName);
                        return redirect()->back()->with('error', 'Failed to save material information.');
                    }
                } else {
                    return redirect()->back()->with('error', 'Failed to upload file.');
                }
            } else {
                return redirect()->back()->with('error', 'Invalid file or file has already been moved.');
            }
        }
    }

    /**
     * Delete a material
     * @param int $materialId
     */
    public function delete($materialId)
    {
        // Check if user is logged in and is admin or teacher
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Please login first.');
        }

        $userRole = strtolower($session->get('role'));
        if (!in_array($userRole, ['admin', 'teacher'])) {
            return redirect()->back()->with('error', 'You do not have permission to delete materials.');
        }

        $materialModel = new MaterialModel();
        $material = $materialModel->getMaterialById($materialId);

        if (!$material) {
            return redirect()->back()->with('error', 'Material not found.');
        }

        // Delete the physical file
        $filePath = WRITEPATH . 'uploads/materials/' . $material['file_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete from database
        if ($materialModel->deleteMaterial($materialId)) {
            return redirect()->back()->with('success', 'Material deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to delete material.');
        }
    }

    /**
     * Download a material (for enrolled students)
     * @param int $materialId
     */
    public function download($materialId)
    {
        // Check if user is logged in
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Please login first.');
        }

        $userId = $session->get('user_id');
        $userRole = strtolower($session->get('role'));

        $materialModel = new MaterialModel();
        $material = $materialModel->getMaterialById($materialId);

        if (!$material) {
            return redirect()->back()->with('error', 'Material not found.');
        }

        // Check if user is enrolled in the course or is admin/teacher
        if ($userRole === 'student') {
            $enrollmentModel = new EnrollmentModel();
            $enrollment = $enrollmentModel->where([
                'user_id' => $userId,
                'course_id' => $material['course_id']
            ])->first();

            if (!$enrollment) {
                return redirect()->back()->with('error', 'You are not enrolled in this course.');
            }
        }

        // Serve the file for download
        $filePath = WRITEPATH . 'uploads/materials/' . $material['file_path'];

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found on server.');
        }

        return $this->response->download($filePath, null)->setFileName($material['file_name']);
    }

    /**
     * View all materials for a course
     * @param int $courseId
     */
    public function viewCourse($courseId)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Please login first.');
        }

        $db = \Config\Database::connect();
        $course = $db->table('courses')->where('id', $courseId)->get()->getRowArray();

        if (!$course) {
            return redirect()->back()->with('error', 'Course not found.');
        }

        $materialModel = new MaterialModel();
        $materials = $materialModel->getMaterialsByCourse($courseId);

        $data = [
            'title' => 'Course Materials - ' . $course['title'],
            'course' => $course,
            'materials' => $materials,
        ];

        return view('materials/view_course', $data);
    }
}
