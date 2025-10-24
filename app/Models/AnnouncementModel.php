<?php

namespace App\Models;

use CodeIgniter\Model;

class AnnouncementModel extends Model
{
    protected $table = 'announcements';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'title', 'content'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Get announcements with user information
    public function getAnnouncementsWithUser()
    {
        return $this->select('announcements.*, users.name as posted_by, users.role')
                    ->join('users', 'users.id = announcements.user_id')
                    ->orderBy('announcements.created_at', 'DESC')
                    ->findAll();
    }
}
