<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Media Gallery - WESO | MMUST CU</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <style>
    /* Media Page Specific Styles */
    .media-container {
      max-width: 1200px;
      margin: 0 auto;
    }
    
    .media-tabs {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 1rem;
      margin: 2rem 0 3rem;
      padding: 0 1rem;
    }
    
    .media-tab {
      padding: 12px 24px;
      background: #f8f9fa;
      border: 2px solid #e9ecef;
      border-radius: 50px;
      cursor: pointer;
      font-weight: 600;
      font-size: 1rem;
      transition: all 0.3s ease;
      color: #495057;
    }
    
    .media-tab:hover {
      background: #e9ecef;
      transform: translateY(-2px);
    }
    
    .media-tab.active {
      background: var(--gold);
      color: white;
      border-color: var(--gold);
      box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2);
    }
    
    .media-section {
      display: none;
      animation: fadeIn 0.5s ease;
    }
    
    .media-section.active {
      display: block;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    /* Photos Grid */
    .albums-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 2rem;
      margin-top: 2rem;
    }
    
    .album-card {
      background: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 5px 20px rgba(0,0,0,0.08);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .album-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    
    .album-image {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-bottom: 3px solid var(--gold);
    }
    
    .album-content {
      padding: 1.5rem;
    }
    
    .album-title {
      margin: 0 0 0.5rem;
      color: #333;
      font-size: 1.3rem;
    }
    
    .album-date {
      color: var(--gold);
      font-weight: 600;
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .album-description {
      color: #666;
      margin-bottom: 1.5rem;
      line-height: 1.5;
    }
    
    .album-button {
      display: inline-block;
      background: var(--gold);
      color: white;
      padding: 10px 20px;
      border-radius: 5px;
      text-decoration: none;
      font-weight: 600;
      transition: background 0.3s ease;
    }
    
    .album-button:hover {
      background: #d4a017;
    }
    
    /* Sermons Grid */
    .sermons-grid, .articles-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
      gap: 2rem;
      margin-top: 2rem;
    }
    
    .sermon-card, .article-card {
      background: white;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }
    
    .sermon-title, .article-title {
      margin: 0 0 0.5rem;
      color: #333;
      font-size: 1.3rem;
    }
    
    .sermon-speaker, .article-author {
      color: var(--gold);
      font-weight: 600;
      margin-bottom: 0.5rem;
    }
    
    .sermon-date, .article-date {
      color: #666;
      margin-bottom: 1rem;
    }
    
    .download-links {
      display: flex;
      gap: 1rem;
      margin-top: 1.5rem;
    }
    
    .download-btn {
      padding: 8px 16px;
      background: #f8f9fa;
      border: 2px solid #e9ecef;
      border-radius: 5px;
      text-decoration: none;
      color: #495057;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    
    .download-btn:hover {
      background: var(--gold);
      color: white;
      border-color: var(--gold);
    }
    
    .article-summary {
      color: #666;
      line-height: 1.6;
      margin: 1rem 0;
    }
    
    .read-more {
      display: inline-block;
      color: var(--gold);
      font-weight: 600;
      text-decoration: none;
      margin-top: 1rem;
    }
    
    .read-more:hover {
      text-decoration: underline;
    }
    
    /* Videos Grid */
    .videos-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
      gap: 2rem;
      margin-top: 2rem;
    }
    
    .video-card {
      background: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }
    
    .video-iframe {
      width: 100%;
      height: 250px;
      border: none;
    }
    
    .video-content {
      padding: 1.5rem;
    }
    
    .video-title {
      margin: 0 0 0.5rem;
      color: #333;
      font-size: 1.3rem;
    }
    
    .video-description {
      color: #666;
      line-height: 1.5;
    }
    
    /* Photo Viewer */
    .photo-viewer {
      display: none;
      margin-top: 3rem;
      animation: fadeIn 0.5s ease;
    }
    
    .viewer-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      padding-bottom: 1rem;
      border-bottom: 2px solid var(--gold);
    }
    
    .close-viewer {
      background: #dc3545;
      color: white;
      border: none;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      font-size: 20px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .photos-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 1rem;
      margin-top: 1rem;
    }
    
    .photo-item {
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      transition: transform 0.3s ease;
    }
    
    .photo-item:hover {
      transform: scale(1.05);
    }
    
    .photo-item img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      cursor: pointer;
    }
    
    /* Loading States */
    .loading {
      text-align: center;
      padding: 3rem;
      color: #666;
    }
    
    .spinner {
      border: 4px solid #f3f3f3;
      border-top: 4px solid var(--gold);
      border-radius: 50%;
      width: 40px;
      height: 40px;
      animation: spin 1s linear infinite;
      margin: 0 auto 1rem;
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    
    .empty-state {
      text-align: center;
      padding: 4rem 2rem;
      color: #666;
      background: #f8f9fa;
      border-radius: 10px;
      margin-top: 2rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
      .media-tabs {
        flex-direction: column;
        align-items: center;
      }
      
      .media-tab {
        width: 100%;
        max-width: 300px;
        text-align: center;
      }
      
      .albums-grid,
      .sermons-grid,
      .articles-grid,
      .videos-grid {
        grid-template-columns: 1fr;
      }
      
      .photos-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
      }
    }
  </style>
</head>
<body>
  <nav class="navbar">
    <div class="nav-container">
      <div class="nav-logo">
        <img src="https://mmustcu.org/img/wesoLogo.jfif" alt="WESO Logo">
        <h2>WESO</h2>
      </div>
      <ul class="nav-menu">
        <li><a href="index.html">Home</a></li>
        <li><a href="about.html">About</a></li>
        <li><a href="wings.html">Wings</a></li>
        <li><a href="media.html" class="active">Media</a></li>
        <li><a href="events.html">Events</a></li>
        <li><a href="register.html">Registration</a></li>
        <li><a href="contact.html">Contact</a></li>
        <li><a href="admin-media.html">Admin</a></li>
      </ul>
      <div class="hamburger">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </nav>

  <section class="section" style="padding-top: 8rem; background: #f5f5f5; min-height: 100vh;">
    <div class="container">
      <h2 class="section-title fade-in">Media Gallery</h2>
      <p style="text-align: center; max-width: 700px; margin: 0 auto 2rem; font-size: 1.1rem;" class="fade-in">
        Explore sermons, articles, photos, and videos from WESO activities and events.
      </p>

      <!-- Media Tabs -->
      <div class="media-tabs">
        <button class="media-tab active" data-target="photos">📷 Photos</button>
        <button class="media-tab" data-target="sermons">📖 Sermons</button>
        <button class="media-tab" data-target="articles">📝 Articles</button>
        <button class="media-tab" data-target="videos">🎥 Videos</button>
      </div>

      <div class="media-container">
        <!-- Photos Section -->
        <div id="photos-section" class="media-section active">
          <div id="albums-loading" class="loading">
            <div class="spinner"></div>
            <p>Loading photo albums...</p>
          </div>
          <div id="albums-container" class="albums-grid" style="display: none;"></div>
          <div id="no-albums" class="empty-state" style="display: none;">
            <h3>No photo albums yet</h3>
            <p>Check back soon for photos from our events!</p>
          </div>
        </div>

        <!-- Sermons Section -->
        <div id="sermons-section" class="media-section">
          <div id="sermons-loading" class="loading">
            <div class="spinner"></div>
            <p>Loading sermons...</p>
          </div>
          <div id="sermons-container" class="sermons-grid" style="display: none;"></div>
          <div id="no-sermons" class="empty-state" style="display: none;">
            <h3>No sermons available</h3>
            <p>Sermon notes will be uploaded here after services.</p>
          </div>
        </div>

        <!-- Articles Section -->
        <div id="articles-section" class="media-section">
          <div id="articles-loading" class="loading">
            <div class="spinner"></div>
            <p>Loading articles...</p>
          </div>
          <div id="articles-container" class="articles-grid" style="display: none;"></div>
          <div id="no-articles" class="empty-state" style="display: none;">
            <h3>No articles yet</h3>
            <p>Articles and Bible studies coming soon!</p>
          </div>
        </div>

        <!-- Videos Section -->
        <div id="videos-section" class="media-section">
          <div id="videos-loading" class="loading">
            <div class="spinner"></div>
            <p>Loading videos...</p>
          </div>
          <div id="videos-container" class="videos-grid" style="display: none;"></div>
          <div id="no-videos" class="empty-state" style="display: none;">
            <h3>No videos yet</h3>
            <p>Video recordings will be uploaded here.</p>
          </div>
        </div>

        <!-- Photo Viewer (hidden by default) -->
        <div id="photo-viewer" class="photo-viewer">
          <div class="viewer-header">
            <h2 id="viewer-title"></h2>
            <button class="close-viewer" onclick="closePhotoViewer()">×</button>
          </div>
          <div id="photos-loading" class="loading">
            <div class="spinner"></div>
            <p>Loading photos...</p>
          </div>
          <div id="photos-container" class="photos-grid" style="display: none;"></div>
          <div id="no-photos" class="empty-state" style="display: none;">
            <h3>No photos in this album</h3>
            <p>Photos will be uploaded soon.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer class="footer">
    <div class="container">
      <div class="footer-content">
        <div class="footer-section">
          <h3>About WESO</h3>
          <p>Western Outreach Ministries is the evangelism department of Masinde Muliro University Christian Union, dedicated to winning souls for Christ across Western Kenya.</p>
        </div>
        <div class="footer-section">
          <h3>Quick Links</h3>
          <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="about.html">About Us</a></li>
            <li><a href="wings.html">Our Wings</a></li>
            <li><a href="media.html">Media</a></li>
            <li><a href="events.html">Events</a></li>
          </ul>
        </div>
        <div class="footer-section">
          <h3>Contact Info</h3>
          <p>📍 Masinde Muliro University of Science and Technology</p>
          <p>Kakamega, Kenya</p>
          <p>📧 Wesommust001@gmail.com</p>
          <p>📱 +254 712 345 678</p>
        </div>
        <div class="footer-section">
          <h3>Connect With Us</h3>
          <div class="social-icons">
            <a href="https://facebook.com/wesommust" target="_blank">f</a>
            <a href="https://instagram.com/wesommust" target="_blank">📷</a>
            <a href="https://youtube.com" target="_blank">▶️</a>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2025 Western Outreach Ministries (WESO) - MMUST CU. All rights reserved.</p>
        <p>Powered by <strong>Godswill Web Solutions</strong></p>
      </div>
    </div>
  </footer>

  <script src="js/script.js"></script>
  <script>
    // Global variables
    let currentAlbumId = null;
    let currentAlbumTitle = '';
    
    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function() {
      // Tab switching
      const tabs = document.querySelectorAll('.media-tab');
      tabs.forEach(tab => {
        tab.addEventListener('click', function() {
          const target = this.dataset.target;
          switchTab(target);
        });
      });
      
      // Load initial content (photos)
      loadPhotoAlbums();
    });
    
    // Switch between tabs
    function switchTab(tabName) {
      // Update active tab
      document.querySelectorAll('.media-tab').forEach(tab => {
        tab.classList.remove('active');
        if (tab.dataset.target === tabName) {
          tab.classList.add('active');
        }
      });
      
      // Hide all sections
      document.querySelectorAll('.media-section').forEach(section => {
        section.classList.remove('active');
      });
      
      // Hide photo viewer
      closePhotoViewer();
      
      // Show selected section
      document.getElementById(`${tabName}-section`).classList.add('active');
      
      // Load data for selected tab if not loaded yet
      switch(tabName) {
        case 'photos':
          if (!window.albumsLoaded) loadPhotoAlbums();
          break;
        case 'sermons':
          if (!window.sermonsLoaded) loadSermons();
          break;
        case 'articles':
          if (!window.articlesLoaded) loadArticles();
          break;
        case 'videos':
          if (!window.videosLoaded) loadVideos();
          break;
      }
    }
    
    // ========== LOAD PHOTO ALBUMS ==========
    async function loadPhotoAlbums() {
      try {
        const response = await fetch('helpers/fetch.php?action=albums');
        const albums = await response.json();
        
        document.getElementById('albums-loading').style.display = 'none';
        
        if (albums.length === 0) {
          document.getElementById('no-albums').style.display = 'block';
          return;
        }
        
        const container = document.getElementById('albums-container');
        container.innerHTML = '';
        
        albums.forEach(album => {
          // Format date
          let eventDate = 'No date';
          if (album.event_date) {
            const d = new Date(album.event_date);
            if (!isNaN(d.getTime())) {
              eventDate = d.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
              });
            }
          }
          
          // Use cover image or default
          const coverImage = album.cover_image || 'https://mmustcu.org/img/wesoLogo.jfif';
          
          const albumCard = `
            <div class="album-card">
              <img src="${coverImage}" alt="${album.title}" class="album-image">
              <div class="album-content">
                <h3 class="album-title">${album.title}</h3>
                <div class="album-date">📅 ${eventDate}</div>
                <p class="album-description">${album.description || 'Click to view photos'}</p>
                <a href="#" class="album-button" onclick="openAlbum('${album.folder_id}', '${album.title}'); return false;">
                  View Album
                </a>
              </div>
            </div>
          `;
          
          container.innerHTML += albumCard;
        });
        
        container.style.display = 'grid';
        window.albumsLoaded = true;
        
      } catch (error) {
        console.error('Error loading albums:', error);
        document.getElementById('albums-loading').innerHTML = `
          <p style="color: #dc3545;">Error loading albums. Please try again.</p>
        `;
      }
    }
    
    // ========== LOAD SERMONS ==========
    async function loadSermons() {
      try {
        const response = await fetch('helpers/fetch.php?action=sermons');
        const sermons = await response.json();
        
        document.getElementById('sermons-loading').style.display = 'none';
        
        if (sermons.length === 0) {
          document.getElementById('no-sermons').style.display = 'block';
          return;
        }
        
        const container = document.getElementById('sermons-container');
        container.innerHTML = '';
        
        sermons.forEach(sermon => {
          // Format date
          let sermonDate = 'No date';
          if (sermon.date) {
            const d = new Date(sermon.date);
            if (!isNaN(d.getTime())) {
              sermonDate = d.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
              });
            }
          }
          
          // Create download links
          let downloadLinks = '';
          if (sermon.pdf_url) {
            downloadLinks += `<a href="${sermon.pdf_url}" class="download-btn" target="_blank" download>📥 PDF</a>`;
          }
          if (sermon.ppt_url) {
            downloadLinks += `<a href="${sermon.ppt_url}" class="download-btn" target="_blank" download>📥 PPT</a>`;
          }
          
          const sermonCard = `
            <div class="sermon-card">
              <h3 class="sermon-title">${sermon.title}</h3>
              <div class="sermon-speaker">${sermon.speaker || 'Speaker'}</div>
              <div class="sermon-date">${sermon.day || ''} • ${sermonDate}</div>
              ${downloadLinks ? `<div class="download-links">${downloadLinks}</div>` : ''}
            </div>
          `;
          
          container.innerHTML += sermonCard;
        });
        
        container.style.display = 'grid';
        window.sermonsLoaded = true;
        
      } catch (error) {
        console.error('Error loading sermons:', error);
        document.getElementById('sermons-loading').innerHTML = `
          <p style="color: #dc3545;">Error loading sermons. Please try again.</p>
        `;
      }
    }
    
    // ========== LOAD ARTICLES ==========
    async function loadArticles() {
      try {
        const response = await fetch('helpers/fetch.php?action=articles');
        const articles = await response.json();
        
        document.getElementById('articles-loading').style.display = 'none';
        
        if (articles.length === 0) {
          document.getElementById('no-articles').style.display = 'block';
          return;
        }
        
        const container = document.getElementById('articles-container');
        container.innerHTML = '';
        
        articles.forEach(article => {
          const articleCard = `
            <div class="article-card">
              <h3 class="article-title">${article.title}</h3>
              <div class="article-author">By ${article.author || 'WESO Team'}</div>
              <p class="article-summary">${article.summary || 'Click to read more...'}</p>
              <a href="${article.link}" class="read-more" target="_blank">Read More →</a>
            </div>
          `;
          
          container.innerHTML += articleCard;
        });
        
        container.style.display = 'grid';
        window.articlesLoaded = true;
        
      } catch (error) {
        console.error('Error loading articles:', error);
        document.getElementById('articles-loading').innerHTML = `
          <p style="color: #dc3545;">Error loading articles. Please try again.</p>
        `;
      }
    }
    
    // ========== LOAD VIDEOS ==========
    async function loadVideos() {
      try {
        const response = await fetch('helpers/fetch.php?action=videos');
        const videos = await response.json();
        
        document.getElementById('videos-loading').style.display = 'none';
        
        if (videos.length === 0) {
          document.getElementById('no-videos').style.display = 'block';
          return;
        }
        
        const container = document.getElementById('videos-container');
        container.innerHTML = '';
        
        videos.forEach(video => {
          // Format date
          let videoDate = '';
          if (video.date) {
            const d = new Date(video.date);
            if (!isNaN(d.getTime())) {
              videoDate = d.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
              });
            }
          }
          
          // Convert YouTube/Vimeo URL to embed URL if needed
          let embedUrl = video.embed_url || video.url || '';
          if (embedUrl.includes('youtube.com/watch?v=')) {
            const videoId = embedUrl.split('v=')[1].split('&')[0];
            embedUrl = `https://www.youtube.com/embed/${videoId}`;
          } else if (embedUrl.includes('youtu.be/')) {
            const videoId = embedUrl.split('youtu.be/')[1].split('?')[0];
            embedUrl = `https://www.youtube.com/embed/${videoId}`;
          } else if (embedUrl.includes('vimeo.com/')) {
            const videoId = embedUrl.split('vimeo.com/')[1].split('?')[0];
            embedUrl = `https://player.vimeo.com/video/${videoId}`;
          }
          
          const videoCard = `
            <div class="video-card">
              <iframe 
                src="${embedUrl}" 
                class="video-iframe"
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
              </iframe>
              <div class="video-content">
                <h3 class="video-title">${video.title}</h3>
                ${videoDate ? `<p><small>${videoDate}</small></p>` : ''}
                ${video.description ? `<p class="video-description">${video.description}</p>` : ''}
              </div>
            </div>
          `;
          
          container.innerHTML += videoCard;
        });
        
        container.style.display = 'grid';
        window.videosLoaded = true;
        
      } catch (error) {
        console.error('Error loading videos:', error);
        document.getElementById('videos-loading').innerHTML = `
          <p style="color: #dc3545;">Error loading videos. Please try again.</p>
        `;
      }
    }
    
    // ========== PHOTO VIEWER ==========
    async function openAlbum(folderId, title) {
      currentAlbumId = folderId;
      currentAlbumTitle = title;
      
      // Hide albums grid
      document.getElementById('albums-container').style.display = 'none';
      
      // Show photo viewer
      document.getElementById('photo-viewer').style.display = 'block';
      document.getElementById('viewer-title').textContent = title;
      
      // Load photos
      await loadAlbumPhotos(folderId);
    }
    
    async function loadAlbumPhotos(folderId) {
      try {
        document.getElementById('photos-loading').style.display = 'block';
        document.getElementById('photos-container').style.display = 'none';
        document.getElementById('no-photos').style.display = 'none';
        
        const response = await fetch(`helpers/fetch.php?action=album_photos&folder_id=${folderId}`);
        const photos = await response.json();
        
        document.getElementById('photos-loading').style.display = 'none';
        
        if (!photos || photos.length === 0) {
          document.getElementById('no-photos').style.display = 'block';
          return;
        }
        
        const container = document.getElementById('photos-container');
        container.innerHTML = '';
        
        photos.forEach(photo => {
          const photoItem = `
            <div class="photo-item">
              <img src="${photo.url}" 
                   alt="${photo.name || 'Photo'}" 
                   onclick="openLightbox('${photo.url}', '${photo.name || ''}')"
                   loading="lazy">
            </div>
          `;
          container.innerHTML += photoItem;
        });
        
        container.style.display = 'grid';
        
      } catch (error) {
        console.error('Error loading photos:', error);
        document.getElementById('photos-loading').innerHTML = `
          <p style="color: #dc3545;">Error loading photos. Please try again.</p>
        `;
      }
    }
    
    function closePhotoViewer() {
      document.getElementById('photo-viewer').style.display = 'none';
      document.getElementById('albums-container').style.display = 'grid';
    }
    
    // ========== LIGHTBOX ==========
    function openLightbox(imageUrl, imageName) {
      // Create lightbox overlay
      const lightbox = document.createElement('div');
      lightbox.className = 'lightbox-overlay';
      lightbox.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        cursor: pointer;
      `;
      
      // Create lightbox content
      const lightboxContent = document.createElement('div');
      lightboxContent.style.cssText = `
        position: relative;
        max-width: 90%;
        max-height: 90%;
      `;
      
      const img = document.createElement('img');
      img.src = imageUrl;
      img.alt = imageName;
      img.style.cssText = `
        max-width: 100%;
        max-height: 80vh;
        object-fit: contain;
        border-radius: 5px;
      `;
      
      // Close button
      const closeBtn = document.createElement('button');
      closeBtn.innerHTML = '×';
      closeBtn.style.cssText = `
        position: absolute;
        top: -40px;
        right: 0;
        background: white;
        color: black;
        border: none;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 20px;
        font-weight: bold;
      `;
      closeBtn.onclick = (e) => {
        e.stopPropagation();
        document.body.removeChild(lightbox);
      };
      
      // Caption
      if (imageName) {
        const caption = document.createElement('p');
        caption.textContent = imageName;
        caption.style.cssText = `
          color: white;
          text-align: center;
          margin-top: 1rem;
          font-size: 1rem;
        `;
        lightboxContent.appendChild(caption);
      }
      
      lightboxContent.appendChild(img);
      lightboxContent.appendChild(closeBtn);
      lightbox.appendChild(lightboxContent);
      
      // Close on background click
      lightbox.onclick = (e) => {
        if (e.target === lightbox) {
          document.body.removeChild(lightbox);
        }
      };
      
      // Close on ESC key
      document.addEventListener('keydown', function escHandler(e) {
        if (e.key === 'Escape') {
          document.body.removeChild(lightbox);
          document.removeEventListener('keydown', escHandler);
        }
      });
      
      document.body.appendChild(lightbox);
    }
  </script>
</body>
</html>