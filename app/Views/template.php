<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'My Website') ?></title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link href="<?= base_url('css/custom.css') ?>" rel="stylesheet">
    <!-- CSRF Token for AJAX -->
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #0dcaf0;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        body {
            background: var(--light-color);
            font-family: "Segoe UI", system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar {
            background: linear-gradient(90deg, var(--primary-color), #6610f2);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        /* Notification dropdown styles */
        .notification-dropdown .dropdown-menu {
            width: 350px;
            max-height: 400px;
            overflow-y: auto;
        }
        
        .notification-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            font-size: 0.6rem;
            padding: 0.15rem 0.4rem;
            border-radius: 50%;
            background-color: var(--danger-color);
            color: white;
            display: none;
        }
        
        .notification-item {
            transition: background-color 0.2s;
        }
        
        .notification-item:hover {
            background-color: rgba(0,0,0,0.03);
        }
        
        .notification-time {
            white-space: nowrap;
            margin-left: 10px;
        }
        
        .mark-as-read {
            text-decoration: none;
        }
        
        .mark-as-read:hover {
            text-decoration: underline;
        }
        .navbar-brand, .nav-link {
            color: #fff !important;
            font-weight: 500;
        }
        .navbar-brand:hover, .nav-link:hover {
            color: #d1e9ff !important;
        }
        main {
            flex: 1; /* pushes footer to bottom if content is short */
        }
        footer {
            background: #212529;
            color: #ccc;
            padding: 15px 0;
            text-align: center;
        }
        footer p {
            margin: 0;
            font-size: 14px;
        }
        footer a {
            color: #0d6efd;
            text-decoration: none;
        }
        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?= base_url('/') ?>">
            <i class="bi bi-mortarboard-fill me-2"></i>
            MyApp
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" 
                aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/') ?>">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('about') ?>">About</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('contact') ?>">Contact</a></li>
                
                <?php if (session()->get('isLoggedIn')): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    
                    <!-- Notification Dropdown -->
                    <li class="nav-item dropdown notification-dropdown me-3">
                        <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell fs-5"></i>
                            <?php if (isset($unreadNotificationsCount) && $unreadNotificationsCount > 0): ?>
                                <span class="notification-badge"><?= $unreadNotificationsCount ?></span>
                            <?php endif; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end notification-dropdown-menu p-0" style="width: 350px; max-height: 400px; overflow-y: auto;">
                            <div class="dropdown-header bg-light p-3 border-bottom">
                                <h6 class="mb-0">Notifications</h6>
                            </div>
                            <div id="notification-list">
                                <?php if (isset($notifications) && !empty($notifications)): ?>
                                    <?php foreach ($notifications as $notification): ?>
                                        <div class="notification-item p-3 border-bottom <?= $notification['is_read'] == '0' ? 'bg-light' : '' ?>">
                                            <div class="d-flex justify-content-between">
                                                <div class="notification-message"><?= esc($notification['message']) ?></div>
                                                <div class="notification-time small text-muted">
                                                    <?= time_ago($notification['created_at']) ?>
                                                </div>
                                            </div>
                                            <?php if ($notification['is_read'] == '0'): ?>
                                                <div class="text-end mt-2">
                                                    <a href="#" class="mark-as-read small text-muted" data-id="<?= $notification['id'] ?>">
                                                        Mark as read
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                    <div class="text-center p-2 border-top">
                                        <a href="<?= base_url('notifications') ?>" class="small">View All Notifications</a>
                                    </div>
                                <?php else: ?>
                                    <div class="p-4 text-center text-muted">
                                        No notifications
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </li>
                    
                    <!-- User Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i>
                            <?= esc(session('user_name') ?: 'User') ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= base_url('profile') ?>"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= base_url('auth/logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('auth/login') ?>">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('auth/register') ?>">
                            <i class="bi bi-person-plus me-1"></i>Register
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<main>
  <?= $this->renderSection('content') ?>
</main>

<!-- Sticky Footer -->
<footer>
  <div class="container">
      <p>&copy; <?= date('Y') ?> MyApp. All Rights Reserved. | 
         <a href="<?= base_url('privacy') ?>">Privacy Policy</a> | 
         <a href="<?= base_url('terms') ?>">Terms</a>
      </p>
  </div>
</footer>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
<!-- jQuery (required for AJAX) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
<!-- Custom JS -->
<script src="<?= base_url('js/notifications.js') ?>"></script>
    
<!-- Initialize tooltips -->
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Set base URL for JavaScript
    const baseUrl = '<?= base_url() ?>';
</script>
</body>
</html>