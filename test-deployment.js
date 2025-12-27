// Simple test script for Vercel API functions
import { google } from 'googleapis';
import fs from 'fs';

console.log('🧪 Testing WESO API Functions...\n');

// Test 1: Check if service account file exists
try {
  const credentials = JSON.parse(fs.readFileSync('./service-account.json', 'utf8'));
  console.log('✅ Service account file found and valid');
} catch (error) {
  console.log('❌ Service account file missing or invalid:', error.message);
  process.exit(1);
}

// Test 2: Check Google Drive API initialization
try {
  const credentials = JSON.parse(fs.readFileSync('./service-account.json', 'utf8'));
  const auth = new google.auth.GoogleAuth({
    credentials: credentials,
    scopes: ['https://www.googleapis.com/auth/drive']
  });
  console.log('✅ Google Drive API initialized successfully');
} catch (error) {
  console.log('❌ Google Drive API initialization failed:', error.message);
  process.exit(1);
}

// Test 3: Check required files exist
const requiredFiles = [
  'index.html',
  'media.html',
  'weso-admin-panel.html',
  'css/style.css',
  'api/index.js',
  'vercel.json'
];

console.log('\n📁 Checking required files...');
let allFilesExist = true;

requiredFiles.forEach(file => {
  if (fs.existsSync(file)) {
    console.log(`✅ ${file}`);
  } else {
    console.log(`❌ ${file} - MISSING`);
    allFilesExist = false;
  }
});

if (allFilesExist) {
  console.log('\n🎉 All checks passed! Ready for Vercel deployment.');
  console.log('\n🚀 To deploy:');
  console.log('   npm install -g vercel');
  console.log('   vercel login');
  console.log('   vercel --prod');
  console.log('\n📖 See VERCEL_DEPLOYMENT.md for detailed instructions');
} else {
  console.log('\n❌ Some files are missing. Please check the file structure.');
  process.exit(1);
}