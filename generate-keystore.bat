@echo off
echo Generating Android Keystore for Couple Planner...
echo.

REM Check if Java is installed
java -version > nul 2>&1
if errorlevel 1 (
    echo Error: Java not found. Please install Java JDK first.
    echo You can download it from: https://adoptium.net/
    pause
    exit /b 1
)

echo Creating keystore file...
keytool -genkey -v -keystore coupleplan-keystore.keystore -alias coupleplan -keyalg RSA -keysize 2048 -validity 10000

echo.
echo Keystore created successfully!
echo File: coupleplan-keystore.keystore
echo.
echo Use these details in Bifrost:
echo - Keystore File: Upload the coupleplan-keystore.keystore file
echo - Keystore Password: [coupleplan2025]
echo - Key Alias: coupleplan
echo - Key Password: [coupleplan123]
echo.
pause
