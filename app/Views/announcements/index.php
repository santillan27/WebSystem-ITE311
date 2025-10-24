<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements - ITE311-SANTILLAN</title>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .header {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            text-align: center;
        }
        h1 { color: #667eea; margin: 0 0 10px 0; }
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background: #5568d3;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .announcement-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }
        .announcement-title {
            color: #667eea;
            margin: 0 0 15px 0;
            font-size: 24px;
        }
        .announcement-content {
            color: #333;
            margin: 0 0 15px 0;
            line-height: 1.6;
        }
        .announcement-meta {
            color: #999;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .announcement-actions {
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>📢 Announcements</h1>
        <?php if ($user_role === 'admin'): ?>
            <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary">← Back to Dashboard</a>
            <a href="<?= base_url('announcement/create') ?>" class="btn btn-primary">➕ Create New Announcement</a>
        <?php elseif ($user_role === 'teacher'): ?>
            <a href="<?= base_url('teacher/dashboard') ?>" class="btn btn-secondary">← Back to Dashboard</a>
        <?php else: ?>
            <!-- Students stay on announcements page -->
        <?php endif; ?>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($announcements)): ?>
        <?php foreach ($announcements as $announcement): ?>
            <div class="announcement-card">
                <h2 class="announcement-title"><?= esc($announcement['title']) ?></h2>
                <p class="announcement-content"><?= esc($announcement['content']) ?></p>
                <div class="announcement-meta">
                    Posted by: <strong><?= esc($announcement['posted_by']) ?></strong> | 
                    <?= date('F d, Y h:i A', strtotime($announcement['created_at'])) ?>
                </div>
                
                <?php if ($user_role === 'admin'): ?>
                    <div class="announcement-actions">
                        <a href="<?= base_url('announcement/edit/' . $announcement['id']) ?>" class="btn btn-primary">✏️ Edit</a>
                        <a href="<?= base_url('announcement/delete/' . $announcement['id']) ?>" 
                           class="btn btn-danger"
                           onclick="return confirm('Are you sure you want to delete this announcement?')">
                           🗑️ Delete
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="announcement-card">
            <p>No announcements available at this time.</p>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
