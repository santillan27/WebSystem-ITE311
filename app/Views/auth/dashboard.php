<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Dashboard') ?> - ITE311-SANTILLAN</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            text-align: center;
        }
        .header h1 {
            color: #333;
            margin-bottom: 10px;
        }
        .user-info {
            color: #555;
            margin-bottom: 20px;
        }
        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            transition: background 0.3s;
        }
        .logout-btn:hover {
            background: #c82333;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .dashboard-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .card-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }
        .card-title {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 10px;
        }
        .card-text {
            color: #666;
            margin-bottom: 20px;
        }
        .card-btn {
            background: #667eea;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            transition: background 0.3s;
        }
        .card-btn:hover {
            background: #5a6fd8;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome, <?= esc($user_name ?? $user['name'] ?? 'User') ?>! 🎉</h1>
            <div class="user-info">
                <p><strong>Role:</strong> <?= esc(ucfirst($user_role ?? $user['role'] ?? 'User')) ?></p>
                <p><strong>Email:</strong> <?= esc($user_email ?? $user['email'] ?? 'No email') ?></p>
            </div>
            <a href="<?= base_url('auth/logout') ?>" class="logout-btn">Logout</a>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <div class="dashboard-grid">
            <!-- Profile Card -->
            <div class="dashboard-card">
                <div class="card-icon">👤</div>
                <h3 class="card-title">Profile</h3>
                <p class="card-text">View or edit your account details.</p>
                <a href="#" class="card-btn">Manage Profile</a>
            </div>

            <!-- Stats Card -->
            <div class="dashboard-card">
                <div class="card-icon">📊</div>
                <h3 class="card-title">Statistics</h3>
                <p class="card-text">
                    Logged in as <strong><?= esc(ucfirst($user_role ?? 'User')) ?></strong>.<br>
                    User ID: <?= esc($user['id'] ?? 'N/A') ?>
                </p>
                <a href="#" class="card-btn">View Stats</a>
            </div>

            <!-- Settings Card -->
            <div class="dashboard-card">
                <div class="card-icon">⚙️</div>
                <h3 class="card-title">Settings</h3>
                <p class="card-text">Customize your dashboard and preferences.</p>
                <a href="#" class="card-btn">Open Settings</a>
            </div>

            <!-- Admin Panel -->
            <?php if (isset($user_role) && $user_role === 'admin'): ?>
                <div class="dashboard-card">
                    <div class="card-icon">🧑‍💼</div>
                    <h3 class="card-title">Admin Panel</h3>
                    <p class="card-text">Access administrative tools and manage users.</p>
                    <a href="<?= base_url('admin/manage-users') ?>" class="card-btn">Go to Admin Panel</a>
                </div>
            <?php endif; ?>

            <!-- Teacher Panel -->
            <?php if (isset($user_role) && $user_role === 'teacher'): ?>
                <div class="dashboard-card">
                    <div class="card-icon">📚</div>
                    <h3 class="card-title">My Courses</h3>
                    <p class="card-text">Manage your course materials and students.</p>
                    <a href="<?= base_url('teacher/courses') ?>" class="card-btn">Go to Courses</a>
                </div>
            <?php endif; ?>

            <!-- Student Panel -->
            <?php if (isset($user_role) && $user_role === 'student'): ?>
                <div class="dashboard-card">
                    <div class="card-icon">🎓</div>
                    <h3 class="card-title">My Enrollments</h3>
                    <p class="card-text">View your enrolled courses and progress.</p>
                    <a href="<?= base_url('student/enrollments') ?>" class="card-btn">View Enrollments</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
