<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('/') ?>">
                <i class="fas fa-graduation-cap"></i> LMS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/dashboard') ?>">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/auth/logout') ?>">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-book"></i> <?= esc($course['title']) ?> - Course Materials
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <p class="lead"><?= esc($course['description'] ?? 'No description available') ?></p>

                        <?php if (!empty($materials)): ?>
                            <div class="table-responsive mt-4">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th><i class="fas fa-file"></i> File Name</th>
                                            <th><i class="fas fa-calendar"></i> Uploaded Date</th>
                                            <th class="text-center"><i class="fas fa-cog"></i> Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($materials as $material): ?>
                                            <tr>
                                                <td>
                                                    <i class="fas fa-file-pdf text-danger"></i>
                                                    <?= esc($material['file_name']) ?>
                                                </td>
                                                <td>
                                                    <?= date('M d, Y h:i A', strtotime($material['created_at'])) ?>
                                                </td>
                                                <td class="text-center">
                                                    <a href="<?= base_url('/materials/download/' . $material['id']) ?>" 
                                                       class="btn btn-sm btn-success">
                                                        <i class="fas fa-download"></i> Download
                                                    </a>
                                                    <?php if (session()->get('role') === 'admin' || session()->get('role') === 'teacher'): ?>
                                                        <a href="<?= base_url('/materials/delete/' . $material['id']) ?>" 
                                                           class="btn btn-sm btn-danger"
                                                           onclick="return confirm('Are you sure you want to delete this material?')">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info mt-4">
                                <i class="fas fa-info-circle"></i> No materials have been uploaded for this course yet.
                            </div>
                        <?php endif; ?>

                        <div class="mt-4">
                            <a href="<?= base_url('/dashboard') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Dashboard
                            </a>
                            <?php if (session()->get('role') === 'admin' || session()->get('role') === 'teacher'): ?>
                                <a href="<?= base_url('/admin/course/' . $course['id'] . '/upload') ?>" 
                                   class="btn btn-primary">
                                    <i class="fas fa-upload"></i> Upload New Material
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
