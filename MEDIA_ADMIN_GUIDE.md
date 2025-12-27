# WESO Media Album Management Guide

## Quick Start Guide

This guide explains how to manage your event photo albums using the admin interface.

## Overview

The WESO website now has a database-driven media management system that allows you to:
- Add photo albums from Google Drive
- Organize albums by category
- Hide/show albums without deleting
- Edit album details anytime
- Display albums automatically on the public media page

## Step 1: Prepare Your Photos in Google Drive

1. **Create a folder for each event**
   - Go to [Google Drive](https://drive.google.com)
   - Create a new folder (e.g., "Campus Revival Week October 2025")
   - Upload all event photos to this folder

2. **Make the folder public**
   - Right-click the folder
   - Click "Share"
   - Under "General access", select "Anyone with the link"
   - Set permission to "Viewer"
   - Click "Done"

3. **Get the Folder ID**
   - The folder URL looks like: `https://drive.google.com/drive/folders/1a2b3c4d5e6f7g8h9i0j`
   - Copy everything after `/folders/` - that's your Folder ID
   - Example: `1a2b3c4d5e6f7g8h9i0j`

## Step 2: Add Album to Website

1. **Access the admin page**
   - Go to: `https://your-website.com/admin-media.html`
   - Or navigate to the admin-media.html page on your site

2. **Fill in the form**
   - **Album Title**: Give your album a clear name
     - Example: "Campus Revival Week - October 2025"
   - **Description**: Brief description of the event
     - Example: "A powerful week of revival on campus with over 300 students saved!"
   - **Google Drive Folder ID**: Paste the ID you copied (NOT the full URL)
     - Example: `1a2b3c4d5e6f7g8h9i0j`
   - **Category**: Choose the appropriate category
     - Campus Missions
     - Street Evangelism
     - Hospital Visits
     - Market Outreach
     - Prison Ministry
     - Other Events
   - **Event Date**: Select the date of the event
   - **Cover Image URL** (optional): Link to a cover photo
     - If left empty, a default image will be used based on category

3. **Submit**
   - Click "Add Album"
   - You'll see a success message
   - The album appears in the list on the right
   - Check the public media page to see it live!

## Step 3: Managing Your Albums

### Edit an Album
1. Find the album in the "Existing Albums" list
2. Click the "Edit" button
3. Make your changes in the form
4. Click "Update Album"

### Hide/Show an Album
- Click the "Hide" button to temporarily remove from public view
- Click "Show" to make it visible again
- Hidden albums stay in the database but don't appear on the media page

### Delete an Album
- Click the "Delete" button
- Confirm the deletion
- **Note:** This only removes the album entry from the website
- Your photos in Google Drive are NOT affected and remain safe

## Step 4: How Visitors See Albums

When visitors go to the media page:
1. They see all active albums organized by category
2. Each album shows:
   - Album title
   - Event date
   - Description
   - Cover image
3. They can filter by category using the buttons
4. When they click "View Album", they're taken to your Google Drive folder to see all photos

## Tips for Best Results

### Photo Organization
- Keep 10-50 photos per album for best viewing experience
- Name your photos descriptively in Drive (optional)
- Remove blurry or duplicate photos before uploading

### Album Titles
- Use descriptive, specific titles
- Include the date or month
- Examples:
  - ✅ "Hospital Ministry at Kakamega General - Nov 2025"
  - ✅ "Street Evangelism Lurambi Market - October 15"
  - ❌ "Event 1"
  - ❌ "Photos"

### Categories
- Use consistent categories for easy filtering
- Campus Missions: All on-campus evangelism
- Street Evangelism: Market, street, and public outreach
- Hospital Visits: Hospital ministry activities
- Market Outreach: Specific market evangelism
- Prison Ministry: Prison visits and ministry
- Other Events: Special events that don't fit other categories

### Cover Images
- Use high-quality images (at least 800x600px)
- Choose images that represent the event well
- If you don't provide one, a default will be used

### Timing
- Add albums shortly after events for timely updates
- Keep albums active for at least 3-6 months
- Hide older albums to keep the gallery fresh

## Troubleshooting

### "Error adding album"
- Check that the Folder ID is correct (no extra characters)
- Verify the folder is set to "Anyone with the link can view"
- Try copying the Folder ID again

### "Album not appearing on public page"
- Make sure the album status shows "✅ Active"
- Check if you accidentally hid it
- Refresh the media page

### "View Album button doesn't work"
- Verify the Google Drive folder is set to public
- Check that the Folder ID is correct
- Test the Drive link: `https://drive.google.com/drive/folders/YOUR_FOLDER_ID`

### "Can't see admin page"
- Make sure you're accessing the correct URL: `/admin-media.html`
- Clear your browser cache
- Try a different browser

## Security Notes

- The admin page doesn't require authentication by default
- Consider adding password protection via your hosting provider
- Only share the admin page URL with trusted team members
- Keep your Supabase credentials secure

## Need Help?

If you encounter issues:
1. Check that your Google Drive folder is public
2. Verify the Folder ID is copied correctly (just the ID, not the full URL)
3. Try using a different browser
4. Contact your web developer if problems persist

## Example Workflow

Here's a complete example:

1. **After the Event**
   - Upload 30 photos from "Campus Revival Week" to Google Drive
   - Folder name: "Campus Revival Week October 2025"
   - Make folder public

2. **Add to Website**
   - Go to admin-media.html
   - Title: "Campus Revival Week - October 2025"
   - Description: "Five days of powerful ministry with 300 salvations!"
   - Folder ID: `1abc2def3ghi4jkl5mno`
   - Category: Campus Missions
   - Date: October 15, 2025
   - Cover Image: (leave blank to use default)
   - Submit

3. **Check Public Page**
   - Go to media.html
   - See your album appear
   - Click "View Album" to test
   - Share the link with your community!

---

**Remember:** Albums are stored in the database and link to Google Drive. Your photos are always safe in Drive even if you delete an album from the website.
