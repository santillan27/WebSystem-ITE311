// Base URL for AJAX requests
const baseUrl = window.location.origin;

// Function to update notifications
function updateNotifications() {
    fetch(`${baseUrl}/notifications`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                updateNotificationUI(data);
            }
        })
        .catch(error => {
            console.error('Error fetching notifications:', error);
        });
}

// Function to update the UI with new notifications
function updateNotificationUI(data) {
    // Update badge
    const badge = document.querySelector('.notification-badge');
    if (badge) {
        if (data.unreadCount > 0) {
            badge.textContent = data.unreadCount;
            badge.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
        }
    }

    // Update dropdown
    const dropdownMenu = document.querySelector('.notification-dropdown-menu');
    if (dropdownMenu) {
        dropdownMenu.innerHTML = ''; // Clear existing items

        if (data.notifications && data.notifications.length > 0) {
            data.notifications.forEach(notification => {
                const isUnread = notification.is_read === '0';
                const notificationItem = document.createElement('div');
                notificationItem.className = `notification-item p-2 border-bottom ${isUnread ? 'bg-light' : ''}`;
                
                const timeAgo = formatTimeAgo(notification.created_at);
                
                notificationItem.innerHTML = `
                    <div class="d-flex justify-content-between">
                        <div class="notification-message">${notification.message}</div>
                        <div class="notification-time small text-muted">${timeAgo}</div>
                    </div>
                    ${isUnread ? 
                        `<div class="text-end">
                            <a href="#" class="mark-as-read small text-muted" data-id="${notification.id}">Mark as read</a>
                        </div>` : ''
                    }
                `;
                
                dropdownMenu.appendChild(notificationItem);
            });

            // Add view all link
            const viewAll = document.createElement('div');
            viewAll.className = 'p-2 text-center';
            viewAll.innerHTML = '<a href="#" class="small">View All Notifications</a>';
            dropdownMenu.appendChild(viewAll);
        } else {
            const noNotifications = document.createElement('div');
            noNotifications.className = 'p-3 text-center text-muted';
            noNotifications.textContent = 'No notifications';
            dropdownMenu.appendChild(noNotifications);
        }
    }
}

// Function to mark a notification as read
function markAsRead(notificationId) {
    if (!notificationId) return;

    fetch(`${baseUrl}/notifications/delete/${notificationId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Remove the notification from the UI
            const notificationItem = document.querySelector(`.notification-item[data-id="${notificationId}"]`);
            if (notificationItem) {
                notificationItem.remove();
                
                // Update the unread count
                const unreadCountElement = document.querySelector('.unread-count');
                if (unreadCountElement) {
                    const currentCount = parseInt(unreadCountElement.textContent) || 0;
                    const newCount = Math.max(0, currentCount - 1);
                    unreadCountElement.textContent = newCount;
                    if (newCount === 0) {
                        unreadCountElement.style.display = 'none';
                        // If no more notifications, show a message
                        const notificationsList = document.querySelector('.notifications-list');
                        if (notificationsList && notificationsList.children.length === 0) {
                            const noNotifications = document.createElement('p');
                            noNotifications.textContent = 'No notifications yet.';
                            notificationsList.appendChild(noNotifications);
                        }
                    }
                }
            }
            
            // Update the notifications dropdown
            updateNotifications();
        }
    })
    .catch(error => {
        console.error('Error marking notification as read:', error);
    });
}

// Helper function to format time ago
function formatTimeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const seconds = Math.floor((now - date) / 1000);
    
    let interval = Math.floor(seconds / 31536000);
    if (interval > 1) return interval + ' years ago';
    if (interval === 1) return '1 year ago';
    
    interval = Math.floor(seconds / 2592000);
    if (interval > 1) return interval + ' months ago';
    if (interval === 1) return '1 month ago';
    
    interval = Math.floor(seconds / 86400);
    if (interval > 1) return interval + ' days ago';
    if (interval === 1) return '1 day ago';
    
    interval = Math.floor(seconds / 3600);
    if (interval >= 1) {
        return interval + ' hour' + (interval === 1 ? '' : 's') + ' ago';
    }
    
    interval = Math.floor(seconds / 60);
    if (interval >= 1) {
        return interval + ' minute' + (interval === 1 ? '' : 's') + ' ago';
    }
    
    return 'just now';
}

// Initialize notification system when the DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Load notifications on page load
    updateNotifications();

    // Set up periodic refresh (every 30 seconds)
    setInterval(updateNotifications, 30000);

    // Handle mark as read for both dropdown and main notifications
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('mark-as-read') || e.target.closest('.mark-as-read')) {
            e.preventDefault();
            const button = e.target.classList.contains('mark-as-read') ? e.target : e.target.closest('.mark-as-read');
            const notificationId = button.dataset.id;
            if (notificationId) {
                markAsRead(notificationId);
            }
        }
    });
    
    // Refresh notifications when dropdown is shown
    const notificationDropdown = document.querySelector('.notification-dropdown');
    if (notificationDropdown) {
        // Handle when dropdown is shown
        notificationDropdown.addEventListener('shown.bs.dropdown', function () {
            // Mark all visible notifications as read when dropdown is opened
            const unreadNotifications = document.querySelectorAll('.notification-item.unread, .notification-item.bg-light');
            unreadNotifications.forEach(item => {
                const markAsReadLink = item.querySelector('.mark-as-read');
                if (markAsReadLink) {
                    const notificationId = markAsReadLink.dataset.id;
                    if (notificationId) {
                        markAsRead(notificationId);
                    }
                }
            });
        });
        
        // Refresh notifications when dropdown is about to be shown
        notificationDropdown.addEventListener('show.bs.dropdown', function() {
            updateNotifications();
        });
    }
});
