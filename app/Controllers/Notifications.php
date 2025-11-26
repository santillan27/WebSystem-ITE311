<?php

namespace App\Controllers;

use App\Models\NotificationModel;

class Notifications extends BaseController
{
    protected $notificationModel;
    
    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
        helper(['form', 'url']);
    }

    /**
     * Get notifications for the current user
     */
    public function get()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Not authenticated'
            ]);
        }

        $userId = session()->get('user_id');
        
        $unreadCount = $this->notificationModel->getUnreadCount($userId);
        $notifications = $this->notificationModel->getNotificationsForUser($userId, 5);

        return $this->response->setJSON([
            'success' => true,
            'unreadCount' => $unreadCount,
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark a notification as read
     */
    public function mark_as_read($id = null)
    {
        if (!session()->get('isLoggedIn')) {
            log_message('warning', 'Unauthorized mark_as_read attempt');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Not authenticated'
            ]);
        }

        if (empty($id)) {
            log_message('warning', 'mark_as_read called with empty ID');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No notification ID provided'
            ]);
        }

        $userId = session()->get('user_id');
        
        log_message('debug', "Marking notification {$id} as read for user {$userId}");
        
        // Verify the notification belongs to this user
        $notification = $this->notificationModel->where('id', $id)->where('user_id', $userId)->first();
        
        if (!$notification) {
            log_message('warning', "Notification {$id} not found for user {$userId}");
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Notification not found'
            ]);
        }
        
        if ($this->notificationModel->markAsRead($id, $userId)) {
            log_message('debug', "Successfully marked notification {$id} as read");
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        }

        log_message('error', "Failed to mark notification {$id} as read");
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to mark notification as read'
        ]);
    }

    /**
     * Create a new notification (for testing)
     */
    public function create_test_notification()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $message = 'This is a test notification - ' . date('Y-m-d H:i:s');
        
        $this->notificationModel->createNotification($userId, $message);
        
        return redirect()->back()->with('success', 'Test notification created!');
    }
    
    /**
     * Mark all notifications as read for the current user
     */
    public function mark_all_read()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Not authenticated'
            ]);
        }

        $userId = session()->get('user_id');
        
        // Mark all as read in the database
        $this->notificationModel->where('user_id', $userId)
                              ->where('is_read', 0)
                              ->set(['is_read' => 1, 'updated_at' => date('Y-m-d H:i:s')])
                              ->update();
        
        // Get the updated unread count (should be 0)
        $unreadCount = $this->notificationModel->where('user_id', $userId)
                                             ->where('is_read', 0)
                                             ->countAllResults();
        
        return $this->response->setJSON([
            'success' => true,
            'unreadCount' => $unreadCount,
            'message' => 'All notifications marked as read'
        ]);
    }
    
    /**
     * Get unread notifications count
     */
    public function get_unread_count()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Not authenticated'
            ]);
        }

        $userId = session()->get('user_id');
        $unreadCount = $this->notificationModel->where('user_id', $userId)
                                             ->where('is_read', 0)
                                             ->countAllResults();
        
        return $this->response->setJSON([
            'success' => true,
            'unreadCount' => $unreadCount
        ]);
    }
}
