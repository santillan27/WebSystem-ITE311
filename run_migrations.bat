@echo off
echo ============================================
echo Running Database Migrations
echo ============================================
echo.

echo [1/4] Checking MySQL connection...
c:\xampp\mysql\bin\mysql.exe -u root -e "SELECT 1" >nul 2>&1
if errorlevel 1 (
    echo ERROR: MySQL is not running!
    echo Please start MySQL in XAMPP Control Panel first.
    echo.
    pause
    exit /b 1
)
echo MySQL is running!
echo.

echo [2/4] Creating database...
c:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS lms_santillan DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
echo Database ready.
echo.

echo [3/4] Running CodeIgniter migrations...
cd /d "c:\xampp\htdocs\ITE311-SANTILLAN"
php spark migrate
echo.

echo [4/4] Creating default admin user...
c:\xampp\mysql\bin\mysql.exe -u root lms_santillan -e "INSERT INTO users (name, email, password, role, status, created_at, updated_at) VALUES ('Admin User', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active', NOW(), NOW()) ON DUPLICATE KEY UPDATE name=name;"
echo Admin user created.
echo.

echo ============================================
echo Migration Complete!
echo ============================================
echo.
echo Login credentials:
echo Email: admin@example.com
echo Password: admin123
echo.
echo All tables created with users status field set to 'active' by default.
echo.
pause
