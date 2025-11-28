<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title><?= esc($title ?? 'Dashboard') ?> - ITE311-SANTILLAN</title>
    <style>
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: Arial, sans-serif;
            padding: 20px;
            color: #333;
        }
        .container { max-width: 1200px; margin: 0 auto; }
        .header {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            text-align: center;
        }

        .logout-btn {
            background: #dc3545; color: white;
            padding: 10px 20px; border-radius: 5px;
            text-decoration: none;
        }


        .logout-btn:hover { background: #b52a38; }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .dashboard-card {
            background: white; padding: 30px; border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center; transition: transform 0.3s;
        }
        .dashboard-card:hover { transform: translateY(-5px); }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background: #667eea; color: white; }
        .enroll-btn {
            background: #28a745; color: white; border: none;
            padding: 8px 15px; border-radius: 5px; cursor: pointer;
        }
        .enroll-btn:hover { background: #218838; }
        .section {
            background: white; padding: 25px; border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 30px;
        }
    </style>
<style>
    #notifications.show {
        display: block !important;
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #ff4d4d;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
    }
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Welcome, <?= esc($user_name ?? 'User') ?> 🎉</h1>
        <p><strong>Role:</strong> <?= esc(ucfirst($user_role)) ?></p>
        <a href="<?= base_url('auth/logout') ?>" class="logout-btn">Logout</a>
    </div>

    <!-- 🔹 Common Dashboard Cards -->
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="card-icon">👤</div>
            <h3>Profile</h3>
            <p>View or edit your account details.</p>
        </div>
        <div class="dashboard-card">
            <div class="card-icon">📊</div>
            <h3>Statistics</h3>
            <p>You are logged in as <strong><?= esc($user_role) ?></strong>.</p>
        </div>
        <div class="dashboard-card" style="cursor: pointer;" onclick="toggleNotifications()">
            <div class="card-icon">🔔</div>
            <h3>Notifications 
                <span id="notification-badge" class="notification-badge" style="background: #ff4d4d; color: white; border-radius: 50%; padding: 2px 8px; font-size: 12px; display: <?= isset($unreadCount) && $unreadCount > 0 ? 'inline-block' : 'none' ?>;">
                    <?= isset($unreadCount) ? $unreadCount : 0 ?>
                </span>
            </h3>
            <p id="notification-text">You have <strong><span id="unread-count"><?= isset($unreadCount) ? $unreadCount : 0 ?></span> unread</strong> notification<?= isset($unreadCount) && $unreadCount !== 1 ? 's' : '' ?></p>
        </div>
    </div>

    <!-- 🔔 Notifications Section (Initially Hidden) -->
    <div id="notifications" class="section" style="display: none;">
        <h2>🔔 My Notifications</h2>
        <?php if (!empty($notifications)): ?>
            <div class="notifications-list">
                <?php foreach ($notifications as $notification): ?>
                    <div id="notification-<?= $notification['id'] ?>" class="notification-item <?= $notification['is_read'] == 0 ? 'unread' : '' ?>" 
                         data-id="<?= $notification['id'] ?>"
                         style="padding: 15px; margin-bottom: 10px; border-left: 4px solid #667eea; background: #f8f9fa; border-radius: 4px; transition: opacity 0.3s ease;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <p style="margin: 0; font-weight: <?= $notification['is_read'] == 0 ? 'bold' : 'normal' ?>;">
                                    <?= esc($notification['message']) ?>
                                </p>
                                <small style="color: #666;">
                                    <?= date('M j, Y g:i A', strtotime($notification['created_at'])) ?>
                                </small>
                            </div>
                            <?php if ($notification['is_read'] == 0): ?>
                                <button onclick="markAsRead(<?= $notification['id'] ?>)" class="mark-as-read" 
                                        style="background: #667eea; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">
                                    Mark as read
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No notifications yet.</p>
        <?php endif; ?>
    </div>

    <!-- 🔹 STUDENT DASHBOARD -->
    <?php if ($user_role === 'student'): ?>
        <!-- 🔍 Search Section -->
        <div class="section">
            <h2>🔍 Search & Filter Courses</h2>
            <p style="color: #666; margin-bottom: 15px;">Search courses by title or description. Results update instantly as you type.</p>
            
            <!-- Unified Search Bar -->
            <form id="searchForm" style="display: flex; gap: 10px; margin-bottom: 15px;">
                <?= csrf_field() ?>
                <input type="text" id="unifiedSearch" name="search" placeholder="Search courses by title or description..." 
                       style="flex: 1; padding: 12px; border: 2px solid #ddd; border-radius: 5px; font-size: 14px; transition: border-color 0.3s;">
                <button type="submit" id="searchBtn" style="background: #667eea; color: white; padding: 12px 25px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; transition: background 0.3s;">
                    🔎 Search
                </button>
            </form>
            <small style="color: #999; display: block;">💡 Type to filter instantly or click Search to see all results</small>
        </div>

        <div class="section">
            <h2>📘 Available Courses</h2>
            <?php if (!empty($courses)): ?>
                <table id="coursesTable">
                    <thead><tr><th>Title</th><th>Description</th><th>Action</th></tr></thead>
                    <tbody>
                    <?php foreach ($courses as $course): ?>
                        <?php
                            $enrolled = false;
                            foreach ($enrolledCourses as $en) {
                                if ($en['id'] == $course['id']) { $enrolled = true; break; }
                            }
                        ?>
                        <tr class="course-row" data-title="<?= esc(strtolower($course['title'])) ?>" 
                            data-description="<?= esc(strtolower($course['description'] ?? '')) ?>">
                            <td><?= esc($course['title']) ?></td>
                            <td><?= esc($course['description']) ?></td>
                            <td>
                                <?php if ($enrolled): ?>
                                    ✅ Enrolled
                                <?php else: ?>
                                    <button class="enroll-btn" data-id="<?= $course['id'] ?>">Enroll</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No available courses.</p>
            <?php endif; ?>
        </div>

        <div class="section">
            <h2>🎓 My Enrolled Courses</h2>
            <?php if (!empty($enrolledCourses)): ?>
                <table>
                    <thead><tr><th>Title</th><th>Description</th><th>Enrollment Date</th><th>Actions</th></tr></thead>
                    <tbody>
                    <?php foreach ($enrolledCourses as $en): ?>
                        <tr>
                            <td><?= esc($en['title']) ?></td>
                            <td><?= esc($en['description']) ?></td>
                            <td><?= esc($en['enrollment_date']) ?></td>
                            <td>
                                <a href="<?= base_url('materials/course/' . $en['id']) ?>" 
                                   style="background: #007bff; color: white; padding: 6px 12px; 
                                          border-radius: 5px; text-decoration: none; display: inline-block;">
                                    📚 View Materials
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>You are not enrolled in any courses yet.</p>
            <?php endif; ?>
        </div>

        <div class="section">
            <h2>📁 My Course Materials</h2>
            <?php if (!empty($studentMaterials)): ?>
                <table>
                    <thead><tr><th>Course</th><th>File Name</th><th>Upload Date</th><th>Action</th></tr></thead>
                    <tbody>
                    <?php foreach ($studentMaterials as $material): ?>
                        <tr>
                            <td><?= esc($material['course_title']) ?></td>
                            <td><?= esc($material['file_name']) ?></td>
                            <td><?= date('M d, Y', strtotime($material['created_at'])) ?></td>
                            <td>
                                <a href="<?= base_url('materials/download/' . $material['id']) ?>" 
                                   style="background: #28a745; color: white; padding: 6px 12px; 
                                          border-radius: 5px; text-decoration: none; display: inline-block;">
                                    ⬇️ Download
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No materials available for your enrolled courses yet.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- 🔹 TEACHER DASHBOARD -->
    <?php if ($user_role === 'teacher'): ?>
        <div class="section">
            <h2>👨‍🏫 My Courses & Enrolled Students</h2>
            <?php if (!empty($teacherCourses)): ?>
                <?php foreach ($teacherCourses as $course): ?>
                    <h3><?= esc($course['title']) ?></h3>
                    <p><?= esc($course['description']) ?></p>

                    <?php if (!empty($course['students'])): ?>
                        <table>
                            <thead><tr><th>Student Name</th><th>Enrollment Date</th></tr></thead>
                            <tbody>
                            <?php foreach ($course['students'] as $student): ?>
                                <tr>
                                    <td><?= esc($student['name']) ?></td>
                                    <td><?= esc($student['enrollment_date']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No students enrolled yet.</p>
                    <?php endif; ?>
                    <hr>
                <?php endforeach; ?>
            <?php else: ?>
                <p>You have no assigned courses.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- 🔹 ADMIN DASHBOARD -->
    <?php if ($user_role === 'admin'): ?>
        <div class="section">
            <h2>🧑‍💼 Admin Dashboard - All Enrollments</h2>
            <?php if (!empty($allEnrollments)): ?>
                <table>
                    <thead><tr><th>Course</th><th>Instructor</th><th>Student</th><th>Enrollment Date</th></tr></thead>
                    <tbody>
                    <?php foreach ($allEnrollments as $row): ?>
                        <tr>
                            <td><?= esc($row['course_title']) ?></td>
                            <td><?= esc($row['instructor_name']) ?></td>
                            <td><?= esc($row['student_name']) ?></td>
                            <td><?= esc($row['enrollment_date']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No enrollments found.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Notification Functions -->
<script>
// Base URL for AJAX requests
const baseUrl = '<?= base_url() ?>';

// Toggle notifications panel
function toggleNotifications() {
    const notificationsPanel = document.getElementById('notifications');
    const isVisible = notificationsPanel.style.display === 'block';
    notificationsPanel.style.display = isVisible ? 'none' : 'block';
}

// Update the notification count in the UI
function updateNotificationCount(count) {
    const badge = document.getElementById('notification-badge');
    const counter = document.getElementById('unread-count');
    const notificationText = document.getElementById('notification-text');
    
    if (count > 0) {
        badge.style.display = 'inline-block';
        badge.textContent = count;
        counter.textContent = count;
        // Update the plural form
        notificationText.innerHTML = `You have <strong>${count} unread</strong> notification${count !== 1 ? 's' : ''}`;
    } else {
        badge.style.display = 'none';
        counter.textContent = '0';
        notificationText.textContent = 'You have no unread notifications';
    }
}

// Fetch and update the unread count
function updateUnreadCount() {
    fetch(`${baseUrl}/notifications/unread_count`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateNotificationCount(data.unreadCount);
        }
    })
    .catch(error => console.error('Error fetching unread count:', error));
}

// Mark notification as read
function markAsRead(notificationId) {
    if (!notificationId) {
        console.error('No notification ID provided');
        return;
    }
    
    console.log('Marking notification as read:', notificationId);
    
    // Find the notification element
    const notificationElement = document.getElementById(`notification-${notificationId}`);
    if (!notificationElement) {
        console.error('Notification element not found:', `notification-${notificationId}`);
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    console.log('CSRF Token:', csrfToken ? 'Present' : 'Missing');
    
    const url = `${baseUrl}/notifications/mark_read/${notificationId}`;
    console.log('Calling URL:', url);
    
    // Make the API call to mark as read
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken
        },
        credentials: 'same-origin',
        body: JSON.stringify({})
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // Update the UI to show the notification as read
            notificationElement.classList.remove('unread');
            const button = notificationElement.querySelector('button');
            if (button) {
                button.style.display = 'none';
            }
            
            // Update the unread count
            updateUnreadCount();
            alert('Notification marked as read!');
        } else {
            console.error('Failed to mark as read:', data.message);
            alert('Failed to mark notification as read: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error marking notification as read:', error);
        alert('Error: ' + error.message);
    });
}

// Mark all notifications as read
function markAllAsRead() {
    fetch(`${baseUrl}/notifications/mark_all_read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update all notifications to appear as read
            document.querySelectorAll('.notification-item.unread').forEach(item => {
                item.classList.remove('unread');
                const button = item.querySelector('button');
                if (button) button.style.display = 'none';
            });
            
            // Update the unread count
            updateNotificationCount(0);
        }
    })
    .catch(error => {
        console.error('Error marking all notifications as read:', error);
    });
}

// Update the notification count when the page loads
document.addEventListener('DOMContentLoaded', function() {
    // Initial update
    updateUnreadCount();
    
    // Update every 30 seconds
    setInterval(updateUnreadCount, 30000);
    
    // Add event delegation for mark as read buttons
    document.addEventListener('click', function(event) {
        // Handle mark as read button clicks
        if (event.target.classList.contains('mark-as-read')) {
            event.preventDefault();
            event.stopPropagation();
            
            const notificationDiv = event.target.closest('.notification-item');
            if (notificationDiv) {
                const notificationId = notificationDiv.getAttribute('data-id');
                console.log('Button clicked for notification:', notificationId);
                markAsRead(notificationId);
            }
        }
        
        // Handle click outside to close notifications
        const notificationsPanel = document.getElementById('notifications');
        const notificationBadge = document.querySelector('.dashboard-card[onclick="toggleNotifications()"]');
        
        if (notificationsPanel && notificationBadge && 
            !notificationsPanel.contains(event.target) && 
            !notificationBadge.contains(event.target) &&
            !event.target.classList.contains('mark-as-read')) {
            notificationsPanel.style.display = 'none';
        }
    });
});

// ============================================
// UNIFIED SEARCH BAR - INSTANT FILTERING
// ============================================
const unifiedSearchInput = document.getElementById('unifiedSearch');
if (unifiedSearchInput) {
    unifiedSearchInput.addEventListener('keyup', function(e) {
        // Don't filter if Enter key is pressed (let form submit instead)
        if (e.key === 'Enter') {
            return;
        }
        
        const filterValue = this.value.toLowerCase().trim();
        const courseRows = document.querySelectorAll('.course-row');
        let visibleCount = 0;

        courseRows.forEach(row => {
            const title = row.getAttribute('data-title');
            const description = row.getAttribute('data-description');

            // If filter is empty, show all
            if (filterValue === '') {
                row.style.display = '';
                visibleCount++;
            } else if (title.includes(filterValue) || description.includes(filterValue)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        console.log(`Instant filter: "${filterValue}" - Showing ${visibleCount} of ${courseRows.length} courses`);
    });
}

// ============================================
// AJAX SEARCH FORM SUBMISSION
// ============================================
console.log('🔵 ========== AJAX SEARCH SCRIPT STARTING ==========');

function setupSearchForm() {
    console.log('🔵 setupSearchForm() called');
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('unifiedSearch');
    const searchBtn = document.getElementById('searchBtn');
    
    console.log('🔵 searchForm element:', searchForm ? '✅ FOUND' : '❌ NOT FOUND');
    console.log('🔵 searchInput element:', searchInput ? '✅ FOUND' : '❌ NOT FOUND');
    console.log('🔵 searchBtn element:', searchBtn ? '✅ FOUND' : '❌ NOT FOUND');
    
    if (!searchForm) {
        console.error('❌ searchForm not found! Cannot attach listener');
        return false;
    }
    
    console.log('🔵 ✅ All elements found! Attaching submit listener...');
    
    searchForm.addEventListener('submit', function(e) {
        console.log('🔵 ✅✅✅ FORM SUBMIT EVENT TRIGGERED! ✅✅✅');
        e.preventDefault();
        e.stopPropagation();
            
            const searchTerm = document.getElementById('unifiedSearch').value.trim();
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
            const searchBtn = document.getElementById('searchBtn');
            
            console.log('🔵 Search term:', searchTerm);
            console.log('🔵 CSRF Token present:', csrfToken ? 'YES' : 'NO');
            
            if (!searchTerm) {
                console.log('🔵 Empty search, submitting normally');
                return;
            }
            
            const searchUrl = '<?= base_url('course/search') ?>';
            console.log('🔵 Search URL:', searchUrl);
            console.log('🔵 Sending AJAX POST request...');
            
            // Show loading state
            const originalText = searchBtn.textContent;
            searchBtn.textContent = '⏳ Searching...';
            searchBtn.disabled = true;
            
            fetch(searchUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: `search=${encodeURIComponent(searchTerm)}`,
                credentials: 'same-origin'
            })
            .then(response => {
                console.log('🔵 Response received:', response.status, response.statusText);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('🟢 AJAX SUCCESS! Response received');
                console.log('🟢 Search results:', data);
                console.log('🟢 Found', data.count, 'courses');
                console.log('✅ AJAX request complete - results ready');
                console.log('✅ AJAX request will stay visible in Network tab for 15 seconds');
                console.log('✅ Waiting 15 seconds before navigating...');
                
                // Wait 15 seconds so AJAX request stays visible in Network tab
                setTimeout(() => {
                    console.log('✅ 15 seconds elapsed - now navigating to search results...');
                    
                    // Navigate using GET method which shows in Network tab
                    const form = document.createElement('form');
                    form.method = 'GET';
                    form.action = searchUrl;
                    
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'search';
                    input.value = searchTerm;
                    
                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                }, 15000);
            })
            .catch(error => {
                console.error('❌ AJAX Error:', error);
                alert('❌ Search failed: ' + error.message);
                searchBtn.textContent = originalText;
                searchBtn.disabled = false;
            });
        }, false);
    
    return true;
}

// Try immediately
console.log('🔵 Attempting immediate setup...');
const setupResult1 = setupSearchForm();
console.log('🔵 Immediate setup result:', setupResult1);

// Try on DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔵 DOMContentLoaded fired, attempting setup...');
    const setupResult2 = setupSearchForm();
    console.log('🔵 DOMContentLoaded setup result:', setupResult2);
});

// Try on readystatechange
document.addEventListener('readystatechange', function() {
    if (document.readyState === 'interactive' || document.readyState === 'complete') {
        console.log('🔵 Document ready state:', document.readyState);
        const setupResult3 = setupSearchForm();
        console.log('🔵 readystatechange setup result:', setupResult3);
    }
});

console.log('🔵 ========== AJAX SEARCH SCRIPT LOADED ==========');

// AJAX Enroll
document.querySelectorAll('.enroll-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        const id = btn.dataset.id;
        console.log('🔵 Enrollment button clicked for course ID:', id);
        
        if (!id) {
            console.error('❌ No course ID found');
            return;
        }
        
        e.preventDefault();
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const enrollUrl = '<?= base_url('course/enroll') ?>';
        
        console.log('🔵 Sending AJAX request to:', enrollUrl);
        console.log('🔵 Course ID:', id);
        console.log('🔵 CSRF Token:', csrfToken);
        
        fetch(enrollUrl, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            body: 'course_id=' + id,
            credentials: 'same-origin'
        })
        .then(response => {
            console.log('🔵 Response received:', response.status, response.statusText);
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('🟢 AJAX Response:', data);
            alert(data.message);
            
            if (data.status === 'success') {
                console.log('✅ Enrollment successful!');
                
                // Update notification count after successful enrollment
                setTimeout(() => {
                    console.log('🔵 Updating unread count...');
                    updateUnreadCount();
                }, 100);
                
                setTimeout(() => {
                    console.log('🔵 Reloading page...');
                    location.reload();
                }, 800);
            } else {
                console.error('❌ Enrollment failed:', data.message);
            }
        })
        .catch(error => {
            console.error('❌ AJAX Error:', error);
            alert('❌ An error occurred: ' + error.message);
        });
    });
});
</script>
</body>
</html>
