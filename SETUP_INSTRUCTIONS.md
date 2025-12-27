# WESO Website Setup Instructions

## Overview
This is a complete, fully functional website for Western Outreach Ministries (WESO) - a department under Masinde Muliro University Christian Union (MMUST CU).

## Features
- ✅ Responsive design for desktop, tablet, and mobile
- ✅ Animated sections with fade-in effects
- ✅ Dynamic event listings
- ✅ **NEW: Database-driven media album management**
- ✅ **NEW: Admin interface for adding Google Drive folders**
- ✅ Registration form ready for Google Sheets integration
- ✅ Contact form with validation
- ✅ Sticky navigation with scroll effects
- ✅ WESO branding (Navy Blue #002147 and Gold #FFD700)

## File Structure
```
/project
├── css/
│   └── style.css              # Main stylesheet
├── js/
│   ├── script.js              # Navigation and animations
│   ├── events.js              # Events data and rendering
│   └── media.js               # Media album management with Supabase
├── index.html                  # Home page
├── about.html                  # About page with leadership
├── wings.html                  # Ministry wings
├── media.html                  # Album gallery (displays from database)
├── admin-media.html            # Admin interface for managing albums
├── events.html                 # Events listing
├── register.html               # Registration form
└── contact.html                # Contact form and map
```

## Setup Instructions

### 1. Media Album Management (NEW!)

The media section now uses Supabase database to manage event albums with Google Drive integration. This allows you to easily add, edit, and manage photo albums without touching any code!

#### How It Works:
1. Upload your event photos to Google Drive
2. Use the admin interface at `admin-media.html` to add the album
3. The album appears automatically on the media page
4. Users click "View Album" to see photos in Google Drive

#### Step-by-Step Guide:

**A. Prepare Your Photos**
1. Create a folder in Google Drive for each event (e.g., "Campus Revival Week 2025")
2. Upload all event photos to that folder
3. Right-click the folder > Share > Change to "Anyone with the link can view"
4. Copy the folder URL - it looks like: `https://drive.google.com/drive/folders/1a2b3c4d5e6f7g8h9i0j...`
5. The Folder ID is everything after `/folders/` - save this!

**B. Add Album to Website**
1. Navigate to `admin-media.html` on your website
2. Fill in the form:
   - **Album Title**: Name of the event (e.g., "Campus Revival Week 2025")
   - **Description**: Brief description of the event
   - **Google Drive Folder ID**: Paste the ID you copied (just the ID, not the full URL)
   - **Category**: Select appropriate category (Campus Missions, Street Evangelism, etc.)
   - **Event Date**: Date when the event happened
   - **Cover Image URL** (optional): URL to a cover photo
3. Click "Add Album"
4. The album will instantly appear on the media page!

**C. Managing Albums**
- **Edit**: Click the "Edit" button to modify album details
- **Hide/Show**: Toggle album visibility without deleting
- **Delete**: Remove album permanently (photos in Drive remain safe)

**Note:** The database is already set up with Supabase. No additional configuration needed!

### 2. Adding Sample Albums (Optional)

To test the media system, you can add some sample albums:

1. Go to `admin-media.html`
2. Add test albums using publicly accessible Google Drive folders
3. Or use these example Folder IDs (public test folders):
   - You can create your own test folders and use them

### 3. Google Apps Script Setup (Forms)

To connect the registration and contact forms to Google Sheets:

#### Step 1: Create a Google Sheet
1. Go to [Google Sheets](https://sheets.google.com)
2. Create a new spreadsheet named "WESO Registrations"
3. Create another sheet tab named "Contact Messages"

#### Step 2: Create Apps Script
1. In your spreadsheet, go to **Extensions > Apps Script**
2. Delete any default code
3. Paste this code:

```javascript
function doPost(e) {
  try {
    const data = JSON.parse(e.postData.contents);
    const sheet = SpreadsheetApp.getActiveSpreadsheet();

    // Determine which form submitted
    let targetSheet;
    if (data.fullName && data.ministry) {
      // Registration form
      targetSheet = sheet.getSheetByName('WESO Registrations');
      if (!targetSheet) targetSheet = sheet.insertSheet('WESO Registrations');

      // Add headers if empty
      if (targetSheet.getLastRow() === 0) {
        targetSheet.appendRow(['Timestamp', 'Full Name', 'Email', 'Phone', 'Gender', 'Church', 'Ministry', 'Testimony']);
      }

      targetSheet.appendRow([
        new Date(),
        data.fullName,
        data.email,
        data.phone,
        data.gender,
        data.church,
        data.ministry,
        data.testimony || ''
      ]);
    } else {
      // Contact form
      targetSheet = sheet.getSheetByName('Contact Messages');
      if (!targetSheet) targetSheet = sheet.insertSheet('Contact Messages');

      // Add headers if empty
      if (targetSheet.getLastRow() === 0) {
        targetSheet.appendRow(['Timestamp', 'Name', 'Email', 'Subject', 'Message']);
      }

      targetSheet.appendRow([
        new Date(),
        data.name,
        data.email,
        data.subject,
        data.message
      ]);
    }

    return ContentService
      .createTextOutput(JSON.stringify({ result: 'success' }))
      .setMimeType(ContentService.MimeType.JSON);
  } catch (error) {
    return ContentService
      .createTextOutput(JSON.stringify({ result: 'error', error: error.toString() }))
      .setMimeType(ContentService.MimeType.JSON);
  }
}
```

4. Click **Deploy > New deployment**
5. Select type: **Web app**
6. Settings:
   - Execute as: **Me**
   - Who has access: **Anyone**
7. Click **Deploy**
8. Copy the **Web app URL**

#### Step 3: Update Website Forms
1. Open `register.html`
2. Find line with `const scriptURL = "YOUR_GOOGLE_APPS_SCRIPT_URL";`
3. Replace with your actual URL: `const scriptURL = "https://script.google.com/macros/s/YOUR_ID/exec";`
4. Open `contact.html`
5. Do the same replacement

### 3. Google Maps Integration

The contact page includes an embedded Google Map. To customize it:

1. Go to [Google Maps](https://maps.google.com)
2. Search for "Masinde Muliro University"
3. Click **Share > Embed a map**
4. Copy the iframe code
5. Open `contact.html`
6. Replace the existing iframe (around line 103) with your new embed code

### 4. Social Media Links

Update social media links throughout the site:

1. Search for `https://facebook.com` in all HTML files
2. Replace with your actual Facebook page URL
3. Do the same for Instagram and YouTube links

Example:
```html
<a href="https://facebook.com/wesoministries" target="_blank">f</a>
<a href="https://instagram.com/wesoministries" target="_blank">📷</a>
<a href="https://youtube.com/@wesoministries" target="_blank">▶️</a>
```

## Customization Guide

### Update Logo
The logo URL is currently: `https://mmustcu.org/img/wesoLogo.jfif`

If you want to use a different logo:
1. Upload your logo to your server or use a CDN
2. Replace the logo URL in all HTML files (appears in navbar and hero section)

### Update Colors
All colors are defined in `css/style.css`:
```css
:root {
  --navy-blue: #002147;
  --gold: #FFD700;
  --white: #FFFFFF;
  --light-gray: #f5f5f5;
  --dark-gray: #333;
}
```

### Update Contact Information
Search and replace across all files:
- Email: `weso@mmustcu.org`
- Phone: `+254 712 345 678`
- Address: Update in footer sections

### Add/Update Events
Edit `js/events.js` to add or modify events:
```javascript
{
  id: 7,
  title: "New Event Name",
  date: "December 15, 2025",
  location: "Event Location",
  description: "Event description...",
  image: "image-url.jpg"
}
```

## Deployment

### Option 1: cPanel / Traditional Hosting
1. Compress all files into a ZIP
2. Upload to your hosting via cPanel File Manager
3. Extract in public_html or desired directory
4. Access via your domain

### Option 2: Firebase Hosting
```bash
# Install Firebase CLI
npm install -g firebase-tools

# Login to Firebase
firebase login

# Initialize Firebase
firebase init hosting

# Deploy
firebase deploy
```

### Option 3: Netlify
1. Create account at [netlify.com](https://netlify.com)
2. Drag and drop your project folder
3. Site will be live instantly

## Browser Compatibility
- ✅ Chrome (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Edge (latest)
- ✅ Mobile browsers

## Support
For issues or questions about the website setup, contact:
- **Developer:** Godswill Web Solutions
- **Client:** WESO - MMUST CU

## License
© 2025 Western Outreach Ministries (WESO) - MMUST CU. All rights reserved.
Powered by Godswill Web Solutions
