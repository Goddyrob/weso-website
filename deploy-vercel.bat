@echo off
echo 🚀 WESO Website - Vercel Deployment Script
echo ==========================================

REM Check if Vercel CLI is installed
vercel --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Vercel CLI not found. Installing...
    npm install -g vercel
)

REM Check if user is logged in
vercel whoami >nul 2>&1
if %errorlevel% neq 0 (
    echo 🔐 Please login to Vercel:
    vercel login
    if %errorlevel% neq 0 (
        echo ❌ Login failed. Please try again.
        pause
        exit /b 1
    )
)

REM Deploy to Vercel
echo 📦 Deploying to Vercel...
vercel --prod

if %errorlevel% equ 0 (
    echo ✅ Deployment complete!
    echo 📝 Don't forget to:
    echo    1. Set environment variables in Vercel dashboard
    echo    2. Upload service-account.json to Vercel
    echo    3. Set up a cloud database for PHP functionality
    echo.
    echo 🔗 Check your deployment at the URL above!
) else (
    echo ❌ Deployment failed. Check the errors above.
)

pause</content>
<parameter name="filePath">c:\wamp64\www\wesommustbranch-main\deploy-vercel.bat