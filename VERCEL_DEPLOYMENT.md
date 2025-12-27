# Vercel Deployment Guide for WESO Website

## 🚀 Quick Deploy to Vercel

### Prerequisites
1. **Vercel Account**: Sign up at [vercel.com](https://vercel.com)
2. **Git Repository**: Your code should be in a Git repository (GitHub, GitLab, or Bitbucket)

### Step 1: Install Vercel CLI
```bash
npm install -g vercel
```

### Step 2: Login to Vercel
```bash
vercel login
```

### Step 3: Deploy
```bash
# Navigate to your project directory
cd /path/to/your/wesommustbranch-main

# Deploy to Vercel
vercel

# For production deployment
vercel --prod
```

### Step 4: Set Environment Variables
In your Vercel dashboard or via CLI:
```bash
vercel env add DB_HOST
vercel env add DB_NAME
vercel env add DB_USER
vercel env add DB_PASS
```

## 📝 Important Notes

### ✅ What's Working
- **Static HTML/CSS/JS files**: All your pages (index.html, media.html, etc.)
- **Google Drive API**: Converted to Vercel serverless functions
- **Responsive Design**: Your mobile-friendly admin panel and login page

### ⚠️ Limitations on Vercel
- **PHP Files**: Vercel doesn't support PHP. You'll need to:
  - Convert PHP endpoints to Node.js API routes
  - Use a separate PHP hosting service (like Railway, Render, or DigitalOcean)
  - Or use Vercel's database integrations

- **MySQL Database**: You'll need a cloud database:
  - **PlanetScale** (recommended for Vercel)
  - **Railway**
  - **DigitalOcean Managed Database**
  - **AWS RDS**

### 🔄 Alternative Architecture
For full functionality, consider this setup:
1. **Frontend**: Vercel (HTML/CSS/JS)
2. **API**: Vercel serverless functions (Google Drive integration)
3. **Backend**: Railway or Render (PHP + MySQL)
4. **Database**: PlanetScale or Railway MySQL

## 🧪 Testing Your Deployment

### Check API Endpoints
```bash
# Health check
curl https://your-app.vercel.app/api/health

# Photos API
curl https://your-app.vercel.app/api/photos

# Album photos
curl "https://your-app.vercel.app/api/album-photos?folderId=YOUR_FOLDER_ID"
```

### Test Admin Panel
1. Visit: `https://your-app.vercel.app/weso-admin-panel.html`
2. Login with password: `weso2024admin`
3. Test responsive design on different devices

## 🔧 Troubleshooting

### Common Issues
1. **API Not Working**: Check Vercel function logs
2. **Google Drive Credentials**: Ensure `service-account.json` is uploaded
3. **Environment Variables**: Set all required env vars in Vercel dashboard

### Vercel Commands
```bash
# Check deployment status
vercel ls

# View logs
vercel logs

# Redeploy
vercel --prod
```

## 📞 Need Help?
- **Vercel Docs**: [vercel.com/docs](https://vercel.com/docs)
- **Google Drive API**: Ensure your service account has proper permissions
- **Database**: Set up a cloud MySQL instance and update connection strings

## 🎯 Next Steps
1. Deploy to Vercel (static files + API)
2. Set up cloud database
3. Migrate PHP functions to Node.js or separate hosting
4. Test all features thoroughly
5. Go live! 🚀</content>
<parameter name="filePath">c:\wamp64\www\wesommustbranch-main\VERCEL_DEPLOYMENT.md