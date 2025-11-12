@echo off
echo Building Couple Planner Android App...
echo.

REM Check if NativePHP is properly installed
php artisan --version > nul
if errorlevel 1 (
    echo Error: PHP/Laravel not found. Please ensure you're in the project directory.
    pause
    exit /b 1
)

echo 1. Installing dependencies...
call npm install

echo.
echo 2. Building assets...
call npm run build

echo.
echo 3. Building Android APK...
php artisan native:android

echo.
echo Build complete! Check the 'dist' folder for your APK file.
echo.
pause
