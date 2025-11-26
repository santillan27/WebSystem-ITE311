<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['user_id', 'message', 'is_read', 'type', 'data', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get unread notifications count for a user
     */
    public function getUnreadCount($userId)
    {
        return $this->where([
            'user_id' => $userId,
            'is_read' => 0
        ])->countAllResults();
    }

    /**
     * Get notifications for a user
     */
    public function getNotificationsForUser($userId, $limit = 5)
    {
        return $this->where('user_id', $userId)
                   ->orderBy('created_at', 'DESC')
                   ->findAll($limit);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($notificationId, $userId)
    {
        try {
            $result = $this->where('id', $notificationId)
                          ->where('user_id', $userId)
                          ->set('is_read', 1)
                          ->update();
            
            if ($result) {
                log_message('debug', "Notification {$notificationId} marked as read for user {$userId}");
            } else {
                log_message('warning', "Failed to update notification {$notificationId}");
            }
            
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Error marking notification as read: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create a new notification
     * 
     * @param int $userId The user ID to notify
     * @param string $message The notification message
     * @param string $type Notification type (e.g., 'enrollment', 'new_student')
     * @param array $data Additional data to store with the notification
     * @return int|bool The ID of the inserted notification or false on failure
     */
    public function createNotification($userId, $message, $type = 'info', $data = [])
    {
        // Ensure the user exists
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);
        
        if (!$user) {
            log_message('error', "Failed to create notification: User ID {$userId} not found");
            return false;
        }
        
        $notificationData = [
            'user_id' => $userId,
            'message' => $message,
            'type' => $type,
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Add data if provided
        if (!empty($data)) {
            $notificationData['data'] = json_encode($data);
        }
        
        try {
            $result = $this->insert($notificationData);
            
            if (!$result) {
                log_message('error', 'Failed to create notification: ' . json_encode($this->errors()));
                return false;
            }
            
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Exception when creating notification: ' . $e->getMessage());
            return false;
        }
    }
}
