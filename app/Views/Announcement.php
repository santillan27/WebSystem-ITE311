<!DOCTYPE html>
<html>
<head>
    <title>Announcements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">📢 Announcements</h2>

    <?php if (empty($announcements)): ?>
        <div class="alert alert-info">No announcements yet.</div>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($announcements as $a): ?>
                <div class="list-group-item mb-3 shadow-sm">
                    <h5><?= esc($a['title']) ?></h5>
                    <p><?= esc($a['content']) ?></p>
                    <small class="text-muted">Posted on: <?= $a['created_at'] ?></small>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
