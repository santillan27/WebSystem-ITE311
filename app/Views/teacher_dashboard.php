<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - ITE311-SANTILLAN</title>
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
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background: #667eea; color: white; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Welcome, Teacher! 👨‍🏫</h1>
        <p><strong>Role:</strong> Teacher</p>
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

    <!-- 👨‍🏫 MY COURSES & ENROLLED STUDENTS -->
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
</div>
</body>
</html>
