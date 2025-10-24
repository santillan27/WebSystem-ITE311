<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    </div>

    <!-- 🔹 STUDENT DASHBOARD -->
    <?php if ($user_role === 'student'): ?>
        <div class="section">
            <h2>📘 Available Courses</h2>
            <?php if (!empty($courses)): ?>
                <table>
                    <thead><tr><th>Title</th><th>Description</th><th>Action</th></tr></thead>
                    <tbody>
                    <?php foreach ($courses as $course): ?>
                        <?php
                            $enrolled = false;
                            foreach ($enrolledCourses as $en) {
                                if ($en['id'] == $course['id']) { $enrolled = true; break; }
                            }
                        ?>
                        <tr>
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
                    <thead><tr><th>Title</th><th>Description</th><th>Enrollment Date</th></tr></thead>
                    <tbody>
                    <?php foreach ($enrolledCourses as $en): ?>
                        <tr>
                            <td><?= esc($en['title']) ?></td>
                            <td><?= esc($en['description']) ?></td>
                            <td><?= esc($en['enrollment_date']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>You are not enrolled in any courses yet.</p>
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

<!-- ✅ AJAX Enroll -->
<script>
document.querySelectorAll('.enroll-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        const id = btn.dataset.id;
        if (!id) return; // Skip if no course ID
        
        e.preventDefault(); // Prevent default action
        
        fetch('<?= base_url('course/enroll') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'course_id=' + id
        })
        .then(r => r.json())
        .then(data => {
            alert(data.message);
            if (data.status === 'success') location.reload();
        })
        .catch(() => alert('Error occurred.'));
    });
});
</script>
</body>
</html>
