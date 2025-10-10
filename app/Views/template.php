<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'My Website') ?></title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: "Segoe UI", sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar {
            background: linear-gradient(90deg, #0d6efd, #6610f2);
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
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="<?= base_url('/') ?>">MyApp</a>
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
            <li class="nav-item"><a class="nav-link" href="<?= base_url('logout') ?>">Logout</a></li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('login') ?>">Login</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('register') ?>">Register</a></li>
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

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
