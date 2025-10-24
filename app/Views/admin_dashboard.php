<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ITE311-SANTILLAN</title>
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
        .section {
            background: white; padding: 25px; border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 30px;
        }
        .create-btn {
            display: inline-block;
            margin-bottom: 15px;
            text-decoration: none;
            background: #28a745;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
        }
        .create-btn:hover {
            background: #218838;
        }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background: #667eea; color: white; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Welcome, Admin! 🎉</h1>
        <p><strong>Role:</strong> Admin</p>
        <a href="<?= base_url('auth/logout') ?>" class="logout-btn">Logout</a>
    </div>

    <!-- Success/Error Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div style="background:#d4edda; color:#155724; padding:15px; border-radius:5px; margin-bottom:20px; border:1px solid #c3e6cb;">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div style="background:#f8d7da; color:#721c24; padding:15px; border-radius:5px; margin-bottom:20px; border:1px solid #f5c6cb;">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- 📢 ANNOUNCEMENTS SECTION (ADMIN CAN MANAGE) -->
    <div class="section">
        <h2>📢 Announcements</h2>
        <a href="<?= base_url('announcement/create') ?>" class="create-btn">➕ Create New Announcement</a>
        
        <?php if (!empty($announcements)): ?>
            <?php foreach ($announcements as $announcement): ?>
                <div style="background:#f8f9fa; padding:15px; border-radius:8px; margin-bottom:15px; border-left:4px solid #667eea;">
                    <h3 style="margin:0 0 10px 0; color:#667eea;"><?= esc($announcement['title']) ?></h3>
                    <p style="margin:0 0 10px 0; color:#666;"><?= esc($announcement['content']) ?></p>
                    <small style="color:#999;">
                        Posted by: <strong><?= esc($announcement['posted_by']) ?></strong> | 
                        <?= date('M d, Y h:i A', strtotime($announcement['created_at'])) ?>
                    </small>
                    <div style="margin-top:10px;">
                        <a href="<?= base_url('announcement/edit/' . $announcement['id']) ?>" style="color:#007bff; text-decoration:none; margin-right:15px;">✏️ Edit</a>
                        <a href="<?= base_url('announcement/delete/' . $announcement['id']) ?>" 
                           onclick="return confirm('Are you sure you want to delete this announcement?')" 
                           style="color:#dc3545; text-decoration:none;">🗑️ Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No announcements yet. Create your first announcement!</p>
        <?php endif; ?>
    </div>

    <!-- 🧑‍💼 ADMIN DASHBOARD - ALL ENROLLMENTS -->
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
</div>
</body>
</html>
