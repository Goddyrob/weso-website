@echo off
echo 🚀 Pushing WESO Website to GitHub
echo ===================================

REM Check if remote is set
git remote -v | findstr "origin" >nul
if %errorlevel% neq 0 (
    echo ❌ No remote repository configured.
    echo Please create a GitHub repository first, then run:
    echo git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git
    echo git push -u origin main
    pause
    exit /b 1
)

echo 📤 Pushing to GitHub...
git push -u origin main

if %errorlevel% equ 0 (
    echo ✅ Successfully pushed to GitHub!
    echo 🌐 Your repository is now live at the URL above
    echo 🚀 Ready for Vercel deployment!
) else (
    echo ❌ Push failed. Check the error messages above.
    echo 💡 Common issues:
    echo   - Wrong repository URL
    echo   - Authentication issues (use GitHub CLI or personal access token)
    echo   - Repository doesn't exist
)

pause</content>
<parameter name="filePath">c:\wamp64\www\wesommustbranch-main\push-to-github.bat