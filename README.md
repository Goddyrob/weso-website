# WESO Website - Western Outreach Ministries

![WESO Logo](https://mmustcu.org/img/wesoLogo.jfif)

**Winning Souls for Christ**

A complete, modern website for Western Outreach Ministries (WESO) - the evangelism department of Masinde Muliro University Christian Union (MMUST CU).

## Features

### Core Features
- ✅ Fully responsive design (mobile, tablet, desktop)
- ✅ Modern animations and transitions
- ✅ WESO branding (Navy Blue #002147 & Gold #FFD700)
- ✅ Fast loading with optimized images
- ✅ SEO-friendly structure

### Pages
- **Home** - Hero section, ministry pillars, recent events
- **About** - Mission, vision, values, leadership team
- **Wings** - Six ministry departments with descriptions
- **Media** - Database-driven photo album gallery
- **Events** - Upcoming and past events listing
- **Registration** - Member registration form
- **Contact** - Contact form with map integration

### Advanced Features
- **📸 Media Album Management**
  - Admin interface for managing photo albums
  - Google Drive integration for photo storage
  - Category filtering and search
  - Hide/show albums without deleting
  - Google Apps Script backend

- **📝 Form Integration**
  - Registration form (Google Sheets via Apps Script)
  - Contact form (Google Sheets via Apps Script)
  - Client-side validation
  - Success/error feedback

- **🎨 Interactive Elements**
  - Scroll animations
  - Sticky navigation
  - Mobile menu
  - Hover effects
  - Smooth scrolling

## Quick Start

### 1. Media Management (NEW!)

**Add Your First Album:**
1. Go to `admin-media.html`
2. Upload photos to Google Drive and make folder public
3. Copy the Folder ID from the Drive URL
4. Fill in the album details and submit
5. Album appears instantly on media page!

See [MEDIA_ADMIN_GUIDE.md](MEDIA_ADMIN_GUIDE.md) for detailed instructions.

### 2. Forms Setup

Connect registration and contact forms to Google Sheets:
1. Create a Google Sheet
2. Set up Apps Script (code provided in SETUP_INSTRUCTIONS.md)
3. Update form URLs in register.html and contact.html

See [SETUP_INSTRUCTIONS.md](SETUP_INSTRUCTIONS.md) for complete setup guide.

### 3. Customization

**Update Contact Info:**
- Search for `weso@mmustcu.org` and replace with your email
- Search for `+254 712 345 678` and replace with your phone

**Update Social Media:**
- Update Facebook, Instagram, YouTube links in footer

**Update Colors (optional):**
- Edit `css/style.css` root variables

## File Structure

```
weso-website/
├── index.html              # Home page
├── about.html              # About page
├── wings.html              # Ministry wings
├── media.html              # Photo gallery
├── admin-media.html        # Media management admin
├── events.html             # Events listing
├── register.html           # Registration form
├── contact.html            # Contact form
├── css/
│   └── style.css          # Main stylesheet
├── js/
│   ├── script.js          # Navigation & animations
│   ├── events.js          # Events data
│   └── media.js           # Media album management
├── SETUP_INSTRUCTIONS.md   # Detailed setup guide
├── MEDIA_ADMIN_GUIDE.md    # Media management guide
└── README.md               # This file
```

## Technology Stack

- **Frontend:** HTML5, CSS3, JavaScript (ES6+)
- **Database:** Supabase (PostgreSQL)
- **Storage:** Google Drive (for photos)
- **Forms:** Google Apps Script + Sheets
- **Fonts:** Google Fonts (Poppins, Inter)
- **Images:** Pexels stock photos
- **Build:** Vite

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## Database Schema

### media_albums Table
```sql
- id (uuid, primary key)
- title (text)
- description (text)
- folder_id (text) - Google Drive folder ID
- category (text)
- event_date (date)
- cover_image (text)
- is_active (boolean)
- created_at (timestamptz)
- updated_at (timestamptz)
```

## Admin Access

**🔒 Protected Admin Panel:** `https://your-domain.com/weso-admin-panel.html`

**Access Instructions:**
1. Navigate directly to the admin URL (not visible in navigation)
2. Enter the admin password: `weso2024admin`
3. Click "Access Admin Panel"

**Security Features:**
- Password protected with session-based authentication
- Not visible in main navigation menu
- File renamed for additional obscurity

**Note:** Change the default password in the JavaScript code for production use.

## Key Features Explained

### Media Album System
- Albums are stored in Supabase database
- Photos are hosted on Google Drive (no server storage needed)
- Admin can add/edit/hide albums without coding
- Public page fetches albums in real-time
- Category filtering for easy browsing

### Form Integration
- Forms submit to Google Sheets via Apps Script
- No server-side code required
- Email notifications can be added in Apps Script
- Data is automatically organized in spreadsheet

## Database Setup with Google Cloud SQL

If you prefer to use Google Cloud SQL as your database instead of local MySQL:

1. **Create a Google Cloud Project:**
   - Go to [Google Cloud Console](https://console.cloud.google.com/)
   - Create a new project or select existing one

2. **Enable Cloud SQL API:**
   - In the Cloud Console, go to APIs & Services > Library
   - Search for "Cloud SQL" and enable it

3. **Create a Cloud SQL Instance:**
   - Go to SQL in the sidebar
   - Create instance > MySQL
   - Choose version (8.0 recommended)
   - Set instance ID, password, region
   - For development, choose lightweight machine type

4. **Create Database and User:**
   - In the instance, go to Databases tab, create database (e.g., church_db)
   - Go to Users tab, create user with password

5. **Configure Authorized Networks:**
   - For local development, add your IP address to authorized networks
   - Or use Cloud SQL Proxy for secure connection

6. **Update Environment Variables:**
   - Copy `.env.example` to `.env`
   - Set DB_HOST to the public IP of your Cloud SQL instance
   - Set DB_NAME, DB_USER, DB_PASS accordingly

7. **Run Database Schema:**
   - Connect to your Cloud SQL instance using MySQL client or phpMyAdmin
   - Run the SQL from `php/init.sql` to create tables

8. **Deploy to Production:**
   - For App Engine, use connection name: `/cloudsql/project:region:instance`
   - Ensure service account has Cloud SQL Client role

## 🚀 Deployment Options

### Vercel (Recommended for Testing)
Perfect for testing your responsive design and Google Drive integration!

**Quick Deploy:**
```bash
# Install Vercel CLI
npm install -g vercel

# Login and deploy
vercel login
vercel --prod
```

**Features:**
- ✅ Static files (HTML/CSS/JS) - Works perfectly
- ✅ Google Drive API - Converted to serverless functions
- ✅ Responsive admin panel - Test on all devices
- ⚠️ PHP/MySQL - Requires separate hosting

**See:** `VERCEL_DEPLOYMENT.md` for detailed instructions

### Other Platforms
- **Netlify**: Similar to Vercel, great for static sites
- **Railway**: Full-stack (Node.js + PHP + MySQL)
- **DigitalOcean**: VPS with Apache/Nginx + PHP + MySQL
- **Google Cloud**: App Engine + Cloud SQL (original setup)

## Credits

- **Client:** WESO - MMUST CU
- **Developer:** Godswill Web Solutions
- **Database:** Google Sheets (via Apps Script)
- **Photos:** Pexels
- **Fonts:** Google Fonts

## License

© 2025 Western Outreach Ministries (WESO) - MMUST CU. All rights reserved.

---

**Built with ❤️ for the Kingdom of God**

*"Go into all the world and preach the gospel to all creation." - Mark 16:15*
