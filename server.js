import express from 'express';
import cors from 'cors';
import { google } from 'googleapis';
import fs from 'fs';

const app = express();
app.use(cors());
app.use(express.json());

// Load credentials
const credentials = JSON.parse(fs.readFileSync('./service-account.json', 'utf8'));

// Initialize Google Drive
const auth = new google.auth.GoogleAuth({
  credentials: credentials,
  scopes: ['https://www.googleapis.com/auth/drive']
});

const drive = google.drive({ version: 'v3', auth });

// 📸 API: Get all photos from default folder
app.get('/api/photos', async (req, res) => {
  try {
    const FOLDER_ID = '1hsWTDGrSFrG1givj5KYnOzp1z0uCCStD'; // Your default folder
    console.log('📸 Fetching photos from default folder...');
    
    // Get image files from your folder
    const response = await drive.files.list({
      q: `'${FOLDER_ID}' in parents and mimeType contains 'image/' and trashed=false`,
      fields: 'files(id, name, mimeType, size, createdTime, webContentLink)',
      orderBy: 'createdTime desc',
      pageSize: 100
    });
    
    const photos = await Promise.all(
      response.data.files.map(async (file) => {
        // Ensure file is publicly accessible
        try {
          await drive.permissions.create({
            fileId: file.id,
            requestBody: {
              role: 'reader',
              type: 'anyone'
            }
          });
        } catch (e) {
          // Already public, ignore error
        }
        
        // Create display URLs
        const directUrl = `https://drive.google.com/uc?export=view&id=${file.id}`;
        const thumbnailUrl = `https://drive.google.com/thumbnail?id=${file.id}&sz=w400`;
        
        return {
          id: file.id,
          name: file.name,
          url: directUrl,
          thumbnail: thumbnailUrl,
          size: file.size,
          uploaded: file.createdTime,
          type: file.mimeType
        };
      })
    );
    
    console.log(`✅ Found ${photos.length} photos in default folder`);
    
    res.json({
      success: true,
      count: photos.length,
      photos: photos
    });
    
  } catch (error) {
    console.error('❌ Error fetching photos:', error);
    res.status(500).json({
      success: false,
      error: 'Failed to fetch photos',
      message: error.message
    });
  }
});

// 📸 API: Get photos for a specific folder (for album display)
app.get('/api/album-photos', async (req, res) => {
  try {
    const folderId = req.query.folderId;
    
    if (!folderId) {
      return res.status(400).json({
        success: false,
        error: 'Folder ID is required'
      });
    }
    
    console.log(`📸 Fetching photos from folder: ${folderId}`);
    
    // Get image files from the specific folder
    const response = await drive.files.list({
      q: `'${folderId}' in parents and mimeType contains 'image/' and trashed=false`,
      fields: 'files(id, name, mimeType, size, createdTime)',
      orderBy: 'createdTime desc',
      pageSize: 100
    });
    
    const photos = await Promise.all(
      response.data.files.map(async (file) => {
        // Ensure file is publicly accessible
        try {
          await drive.permissions.create({
            fileId: file.id,
            requestBody: {
              role: 'reader',
              type: 'anyone'
            }
          });
        } catch (e) {
          // Already public, ignore error
        }
        
        // Create display URLs
        const directUrl = `https://drive.google.com/uc?export=view&id=${file.id}`;
        const thumbnailUrl = `https://drive.google.com/thumbnail?id=${file.id}&sz=w400`;
        
        return {
          id: file.id,
          name: file.name,
          url: directUrl,
          thumbnail: thumbnailUrl,
          size: file.size,
          uploaded: file.createdTime,
          type: file.mimeType
        };
      })
    );
    
    console.log(`✅ Found ${photos.length} photos in folder ${folderId}`);
    
    res.json({
      success: true,
      count: photos.length,
      photos: photos
    });
    
  } catch (error) {
    console.error('❌ Error fetching folder photos:', error);
    res.status(500).json({
      success: false,
      error: 'Failed to fetch photos from folder',
      message: error.message
    });
  }
});

// 📸 API: Get first photo from a folder (for cover images)
app.get('/api/cover-photo', async (req, res) => {
  try {
    const folderId = req.query.folderId;
    
    if (!folderId) {
      return res.status(400).json({
        success: false,
        error: 'Folder ID is required'
      });
    }
    
    console.log(`🖼️ Getting cover photo from folder: ${folderId}`);
    
    // Get first image file from the folder
    const response = await drive.files.list({
      q: `'${folderId}' in parents and mimeType contains 'image/' and trashed=false`,
      fields: 'files(id, name)',
      orderBy: 'createdTime desc',
      pageSize: 1
    });
    
    if (response.data.files.length === 0) {
      return res.json({
        success: true,
        coverPhoto: null,
        message: 'No photos found in folder'
      });
    }
    
    const file = response.data.files[0];
    const thumbnailUrl = `https://drive.google.com/thumbnail?id=${file.id}&sz=w400`;
    
    res.json({
      success: true,
      coverPhoto: {
        id: file.id,
        name: file.name,
        thumbnail: thumbnailUrl
      }
    });
    
  } catch (error) {
    console.error('❌ Error fetching cover photo:', error);
    res.json({
      success: false,
      coverPhoto: null,
      error: error.message
    });
  }
});

// Health check endpoint
app.get('/api/health', (req, res) => {
  res.json({ 
    status: 'OK', 
    service: 'WESO Photos API',
    endpoints: [
      '/api/photos',
      '/api/album-photos?folderId=YOUR_FOLDER_ID',
      '/api/cover-photo?folderId=YOUR_FOLDER_ID',
      '/api/health'
    ]
  });
});

// Start server
const PORT = process.env.PORT || 5000;
app.listen(PORT, () => {
  console.log(`🚀 WESO Photos API running on port ${PORT}`);
  console.log(`📸 Endpoints:`);
  console.log(`   - All photos: http://localhost:${PORT}/api/photos`);
  console.log(`   - Album photos: http://localhost:${PORT}/api/album-photos?folderId=FOLDER_ID`);
  console.log(`   - Cover photo: http://localhost:${PORT}/api/cover-photo?folderId=FOLDER_ID`);
  console.log(`   - Health check: http://localhost:${PORT}/api/health`);
});