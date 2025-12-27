#!/bin/bash

echo "🚀 WESO Website - Vercel Deployment Script"
echo "=========================================="

# Check if Vercel CLI is installed
if ! command -v vercel &> /dev/null; then
    echo "❌ Vercel CLI not found. Installing..."
    npm install -g vercel
fi

# Check if user is logged in
if ! vercel whoami &> /dev/null; then
    echo "🔐 Please login to Vercel:"
    vercel login
fi

# Deploy to Vercel
echo "📦 Deploying to Vercel..."
vercel --prod

echo "✅ Deployment complete!"
echo "📝 Don't forget to:"
echo "   1. Set environment variables in Vercel dashboard"
echo "   2. Upload service-account.json to Vercel"
echo "   3. Set up a cloud database for PHP functionality"
echo ""
echo "🔗 Check your deployment at the URL above!"</content>
<parameter name="filePath">c:\wamp64\www\wesommustbranch-main\deploy-vercel.sh