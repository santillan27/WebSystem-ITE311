@echo off
echo ============================================
echo Resetting Database for LMS Santillan
echo ============================================
echo.

echo [1/5] Stopping MySQL service...
net stop mysql >nul 2>&1
c:\xampp\mysql\bin\mysqladmin.exe -u root shutdown >nul 2>&1
timeout /t 2 /nobreak >nul
echo MySQL stopped.
echo.

echo [2/5] Removing corrupted database folder...
if exist "c:\xampp\mysql\data\lms_santillan" (
    rd /s /q "c:\xampp\mysql\data\lms_santillan"
    echo Database folder removed.
) else (
    echo Database folder not found, skipping...
)
echo.

echo [3/5] Starting MySQL service...
start "" "c:\xampp\mysql\bin\mysqld.exe" --defaults-file="c:\xampp\mysql\bin\my.ini" --standalone --console
timeout /t 5 /nobreak >nul
echo MySQL started.
echo.

echo [4/5] Creating fresh database...
c:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS lms_santillan DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
echo Database created.
echo.

echo [5/5] Running migrations...
cd /d "c:\xampp\htdocs\ITE311-SANTILLAN"
php spark migrate
echo.

echo ============================================
echo Database reset complete!
echo ============================================
pause
