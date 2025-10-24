<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITE311-SANTILLAN - Learning Management System</title>
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
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            color: white;
            margin-bottom: 50px;
        }
        
        .header h1 {
            font-size: 3rem;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s;
        }
        
        .card:hover {
            transform: translateY(-10px);
        }
        
        .card h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 1.5rem;
        }
        
        .card p {
            color: #666;
            margin-bottom: 20px;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s;
            margin: 5px;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        
        .btn-info {
            background: #17a2b8;
            color: white;
        }
        
        .btn-info:hover {
            background: #138496;
            transform: translateY(-2px);
        }
        
        .status-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        
        .status-section h3 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .status-list {
            list-style: none;
            padding: 0;
        }
        
        .status-list li {
            padding: 10px;
            margin: 5px 0;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #28a745;
        }
        
        .test-accounts {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .test-accounts h3 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .account-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .account-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        
        .account-card h4 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .account-card p {
            color: #666;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to ITE311-SANTILLAN</h1>
            <p>Learning Management System</p>
        </div>
        
        <div class="cards-container">
            <div class="card">
                <h3>🔐 Authentication System</h3>
                <p>Register new accounts and login to access the system</p>
                <a href="<?= base_url('register') ?>" class="btn btn-success">Register New Account</a>
                <a href="<?= base_url('login') ?>" class="btn btn-primary">Login</a>
            </div>
            
            <div class="card">
                <h3>📊 Dashboard</h3>
                <p>Access your personalized dashboard after login</p>
                <a href="<?= base_url('dashboard') ?>" class="btn btn-info">Go to Dashboard</a>
            </div>
            
            <div class="card">
                <h3>🔧 System Test</h3>
                <p>Test database connection and system functionality</p>
                <a href="<?= base_url('test') ?>" class="btn btn-primary">Database Test</a>
            </div>
        </div>
        
        <div class="status-section">
            <h3>✅ System Status</h3>
            <ul class="status-list">
                <li>Routes configured for registration and login</li>
                <li>User model with password hashing</li>
                <li>Database migration for users table</li>
                <li>Clean, modern UI design</li>
                <li>Session management</li>
                <li>Dashboard access control</li>
            </ul>
        </div>
        
        <div class="test-accounts">
            <h3>🧪 Test Accounts Available</h3>
            <div class="account-grid">
                <div class="account-card">
                    <h4>👑 Admin</h4>
                    <p><strong>Email:</strong> admin@example.com<br>
                    <strong>Password:</strong> admin123</p>
                </div>
                <div class="account-card">
                    <h4>👨‍🏫 Instructor</h4>
                    <p><strong>Email:</strong> instructor@example.com<br>
                    <strong>Password:</strong> instructor123</p>
                </div>
                <div class="account-card">
                    <h4>🎓 Student</h4>
                    <p><strong>Email:</strong> student@example.com<br>
                    <strong>Password:</strong> student123</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
