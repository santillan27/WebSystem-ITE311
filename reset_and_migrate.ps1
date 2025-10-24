# ============================================
# Reset Database and Run Migrations
# ============================================

Write-Host "============================================" -ForegroundColor Cyan
Write-Host "Resetting Database for LMS Santillan" -ForegroundColor Cyan
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""

# Step 1: Stop MySQL via XAMPP
Write-Host "[1/6] Stopping MySQL..." -ForegroundColor Yellow
Stop-Process -Name "mysqld" -Force -ErrorAction SilentlyContinue
Start-Sleep -Seconds 3
Write-Host "MySQL stopped." -ForegroundColor Green
Write-Host ""

# Step 2: Remove corrupted database folder
Write-Host "[2/6] Removing corrupted database folder..." -ForegroundColor Yellow
$dbPath = "c:\xampp\mysql\data\lms_santillan"
if (Test-Path $dbPath) {
    Remove-Item -Path $dbPath -Recurse -Force
    Write-Host "Database folder removed." -ForegroundColor Green
} else {
    Write-Host "Database folder not found, skipping..." -ForegroundColor Gray
}
Write-Host ""

# Step 3: Start MySQL
Write-Host "[3/6] Starting MySQL..." -ForegroundColor Yellow
Start-Process -FilePath "c:\xampp\mysql\bin\mysqld.exe" -ArgumentList "--defaults-file=c:\xampp\mysql\bin\my.ini"
Start-Sleep -Seconds 5
Write-Host "MySQL started." -ForegroundColor Green
Write-Host ""

# Step 4: Create database
Write-Host "[4/6] Creating fresh database..." -ForegroundColor Yellow
& "c:\xampp\mysql\bin\mysql.exe" -u root -e "CREATE DATABASE IF NOT EXISTS lms_santillan DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
Write-Host "Database created." -ForegroundColor Green
Write-Host ""

# Step 5: Run migrations
Write-Host "[5/6] Running migrations..." -ForegroundColor Yellow
Set-Location "c:\xampp\htdocs\ITE311-SANTILLAN"
& php spark migrate
Write-Host ""

# Step 6: Create default admin user
Write-Host "[6/6] Creating default admin user..." -ForegroundColor Yellow
$createAdminSQL = @"
INSERT INTO users (name, email, password, role, status, created_at, updated_at) 
VALUES ('Admin User', 'admin@example.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active', NOW(), NOW());
"@
& "c:\xampp\mysql\bin\mysql.exe" -u root lms_santillan -e $createAdminSQL
Write-Host "Admin user created." -ForegroundColor Green
Write-Host ""

Write-Host "============================================" -ForegroundColor Cyan
Write-Host "Database reset complete!" -ForegroundColor Green
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "You can now login with:" -ForegroundColor White
Write-Host "Email: admin@example.com" -ForegroundColor Yellow
Write-Host "Password: admin123" -ForegroundColor Yellow
Write-Host ""
