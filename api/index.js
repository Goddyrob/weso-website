import { google } from 'googleapis';
import fs from 'fs';

// Load credentials
const credentials = JSON.parse(fs.readFileSync('./service-account.json', 'utf8'));

// Initialize Google Drive
const auth = new google.auth.GoogleAuth({
  credentials: credentials,
  scopes: ['https://www.googleapis.com/auth/drive']
});

const drive = google.drive({ version: 'v3', auth });

// 📸 API: Get all photos from default folder
export default async function handler(req, res) {
  // Enable CORS
  res.setHeader('Access-Control-Allow-Origin', '*');
  res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
  res.setHeader('Access-Control-Allow-Headers', 'Content-Type');

  if (req.method === 'OPTIONS') {
    res.status(200).end();
    return;
  }

  const { pathname } = new URL(req.url, `http://${req.headers.host}`);

  try {
    if (pathname === '/api/health') {
      res.status(200).json({
        status: 'OK',
        message: 'WESO Photos API is running',
        timestamp: new Date().toISOString()
      });
      return;
    }

    if (pathname === '/api/photos') {
      const FOLDER_ID = '1hsWTDGrSFrG1givj5KYnOzp1z0uCCStD';
      console.log('📸 Fetching photos from default folder...');

      const response = await drive.files.list({
        q: `'${FOLDER_ID}' in parents and mimeType contains 'image/' and trashed=false`,
        fields: 'files(id, name, mimeType, size, createdTime, webContentLink)',
        orderBy: 'createdTime desc',
        pageSize: 100
      });

      const photos = await Promise.all(
        response.data.files.map(async (file) => {
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

          return {
            id: file.id,
            name: file.name,
            url: `https://drive.google.com/uc?export=view&id=${file.id}`,
            thumbnail: `https://drive.google.com/uc?export=view&id=${file.id}&sz=s400`,
            createdTime: file.createdTime,
            size: file.size
          };
        })
      );

      res.status(200).json({
        success: true,
        photos: photos,
        count: photos.length
      });
      return;
    }

    if (pathname.startsWith('/api/album-photos')) {
      const url = new URL(req.url, `http://${req.headers.host}`);
      const folderId = url.searchParams.get('folderId');

      if (!folderId) {
        res.status(400).json({
          success: false,
          error: 'folderId parameter is required'
        });
        return;
      }

      console.log(`📸 Fetching photos from album: ${folderId}`);

      const response = await drive.files.list({
        q: `'${folderId}' in parents and mimeType contains 'image/' and trashed=false`,
        fields: 'files(id, name, mimeType, size, createdTime, webContentLink)',
        orderBy: 'createdTime desc',
        pageSize: 100
      });

      const photos = await Promise.all(
        response.data.files.map(async (file) => {
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

          return {
            id: file.id,
            name: file.name,
            url: `https://drive.google.com/uc?export=view&id=${file.id}`,
            thumbnail: `https://drive.google.com/uc?export=view&id=${file.id}&sz=s400`,
            createdTime: file.createdTime,
            size: file.size
          };
        })
      );

      res.status(200).json({
        success: true,
        photos: photos,
        count: photos.length,
        folderId: folderId
      });
      return;
    }

    if (pathname.startsWith('/api/cover-photo')) {
      const url = new URL(req.url, `http://${req.headers.host}`);
      const folderId = url.searchParams.get('folderId');

      if (!folderId) {
        res.status(400).json({
          success: false,
          error: 'folderId parameter is required'
        });
        return;
      }

      console.log(`📸 Fetching cover photo for album: ${folderId}`);

      const response = await drive.files.list({
        q: `'${folderId}' in parents and mimeType contains 'image/' and trashed=false`,
        fields: 'files(id, name, createdTime)',
        orderBy: 'createdTime desc',
        pageSize: 1
      });

      if (response.data.files.length === 0) {
        res.status(404).json({
          success: false,
          error: 'No photos found in this album'
        });
        return;
      }

      const coverPhoto = response.data.files[0];

      try {
        await drive.permissions.create({
          fileId: coverPhoto.id,
          requestBody: {
            role: 'reader',
            type: 'anyone'
          }
        });
      } catch (e) {
        // Already public, ignore error
      }

      res.status(200).json({
        success: true,
        coverPhoto: {
          id: coverPhoto.id,
          name: coverPhoto.name,
          url: `https://drive.google.com/uc?export=view&id=${coverPhoto.id}`,
          thumbnail: `https://drive.google.com/uc?export=view&id=${coverPhoto.id}&sz=s400`,
          createdTime: coverPhoto.createdTime
        },
        folderId: folderId
      });
      return;
    }

    // 404 for unknown API routes
    res.status(404).json({
      success: false,
      error: 'API endpoint not found'
    });

  } catch (error) {
    console.error('API Error:', error);
    res.status(500).json({
      success: false,
      error: 'Internal server error',
      message: error.message
    });
  }
}</content>
<parameter name="filePath">c:\wamp64\www\wesommustbranch-main\api/index.js