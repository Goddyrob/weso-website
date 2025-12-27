// Media Management System for MMUST CU
// Using MySQL backend and Google Drive for file storage

let allAlbums = [];
let allSermons = [];
let allArticles = [];
let allVideos = [];

// Default cover images for categories
const defaultCoverImages = {
  "Campus Missions": "https://images.pexels.com/photos/8468092/pexels-photo-8468092.jpeg?auto=compress&cs=tinysrgb&w=800",
  "Street Evangelism": "https://images.pexels.com/photos/5206963/pexels-photo-5206963.jpeg?auto=compress&cs=tinysrgb&w=800",
  "Hospital Visits": "https://images.pexels.com/photos/8460207/pexels-photo-8460207.jpeg?auto=compress&cs=tinysrgb&w=800",
  "Market Outreach": "https://images.pexels.com/photos/3270224/pexels-photo-3270224.jpeg?auto=compress&cs=tinysrgb&w=800",
  "Prison Ministry": "https://images.pexels.com/photos/9347693/pexels-photo-9347693.jpeg?auto=compress&cs=tinysrgb&w=800",
  "Other Events": "https://images.pexels.com/photos/7163619/pexels-photo-7163619.jpeg?auto=compress&cs=tinysrgb&w=800"
};

// DOM Elements
const mediaSections = {
    sermons: document.querySelector('.sermons'),
    articles: document.querySelector('.articles'),
    photos: document.querySelector('.photosDiv'),
    videos: document.querySelector('.videos')
};

// ========== LOAD ALL MEDIA ==========
async function loadAllMedia() {
    try {
        await Promise.all([
            loadSermons(),
            loadArticles(),
            loadPhotoAlbums(),
            loadVideos()
        ]);
        
        // Display photos by default
        display('photos');
    } catch (error) {
        console.error('Error loading media:', error);
    }
}

// ========== PHOTO ALBUMS ==========
async function loadPhotoAlbums() {
    const loadingSpinner = document.getElementById('loading-spinner');
    const albumsGrid = document.getElementById('albums-grid');

    try {
        // Fetch albums from MySQL backend
        const response = await fetch('../helpers/fetch.php?action=albums');
        if (!response.ok) throw new Error('Failed to fetch albums');
        
        const data = await response.json();
        allAlbums = Array.isArray(data) ? data : [];

        if (loadingSpinner) {
            loadingSpinner.style.display = 'none';
        }

        if (allAlbums.length === 0) {
            albumsGrid.innerHTML = '<p style="text-align: center; color: #666; grid-column: 1/-1;">No albums available yet. Check back soon!</p>';
            return;
        }

        // Render albums
        renderAlbums(allAlbums);
    } catch (error) {
        console.error('Error loading photo albums:', error);
        if (loadingSpinner) {
            loadingSpinner.style.display = 'none';
        }
        albumsGrid.innerHTML = '<p style="text-align: center; color: #dc3545; grid-column: 1/-1;">Error loading albums. Please refresh the page.</p>';
    }
}

function renderAlbums(albums) {
    const albumsGrid = document.getElementById('albums-grid');

    if (albums.length === 0) {
        albumsGrid.innerHTML = '<p style="text-align: center; color: #666; grid-column: 1/-1;">No albums found.</p>';
        return;
    }

    albumsGrid.innerHTML = albums.map(album => {
        // Use album cover if present, otherwise use default based on category
        const category = album.category || 'Other Events';
        const defaultCover = defaultCoverImages[category] || defaultCoverImages['Other Events'];
        const coverImage = (album.cover_image && album.cover_image.trim()) ? album.cover_image : defaultCover;
        
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

        // Render album card
        return `
            <div class="wing-card fade-in" style="cursor: default;">
                <img src="${coverImage}" alt="${album.title}" style="height: 250px; object-fit: cover;">
                <div class="wing-content">
                    <h3><a class="album-title-link" href="javascript:void(0)" 
                          onclick="openAlbum('${album.folder_id}', '${album.title}')">${album.title}</a></h3>
                    <p class="date" style="color: var(--gold); font-weight: 600; margin-bottom: 0.5rem;">
                        📅 ${eventDate}
                    </p>
                    <p style="margin-bottom: 1rem;">${album.description || 'Click to view photos'}</p>
                    <a class="wing-button wing-button-link" href="javascript:void(0)" 
                       onclick="openAlbum('${album.folder_id}', '${album.title}')">View Album</a>
                </div>
            </div>
        `;
    }).join('');

    // Add fade-in animation
    setTimeout(() => {
        const fadeEls = albumsGrid.querySelectorAll('.fade-in');
        fadeEls.forEach(el => el.classList.add('visible'));
    }, 100);
}

// ========== SERMONS ==========
async function loadSermons() {
    try {
        const response = await fetch('../helpers/fetch.php?action=sermons');
        if (!response.ok) throw new Error('Failed to fetch sermons');
        
        const data = await response.json();
        allSermons = Array.isArray(data) ? data : [];

        // Render sermons
        if (mediaSections.sermons) {
            renderSermons(allSermons);
        }
    } catch (error) {
        console.error('Error loading sermons:', error);
        if (mediaSections.sermons) {
            mediaSections.sermons.innerHTML = '<p style="text-align: center; color: #dc3545;">Error loading sermons.</p>';
        }
    }
}

function renderSermons(sermons) {
    if (!mediaSections.sermons) return;
    
    if (sermons.length === 0) {
        mediaSections.sermons.innerHTML = '<p style="text-align: center; color: #666;">No sermons available yet.</p>';
        return;
    }

    mediaSections.sermons.innerHTML = sermons.map(sermon => {
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
            downloadLinks += `<a href="${sermon.pdf_url}" target="_blank" download>PDF</a>`;
        }
        if (sermon.ppt_url) {
            downloadLinks += `<a href="${sermon.ppt_url}" target="_blank" download>PPT</a>`;
        }

        return `
            <div class="sermon">
                <div>
                    <h3>${sermon.title}</h3>
                    <h5>${sermon.speaker}</h5>
                    <h5>${sermon.day} • ${sermonDate}</h5>
                </div>
                <div id="button">
                    ${downloadLinks || 'No downloads available'}
                </div>
            </div>
        `;
    }).join('');
}

// ========== ARTICLES ==========
async function loadArticles() {
    try {
        const response = await fetch('../helpers/fetch.php?action=articles');
        if (!response.ok) throw new Error('Failed to fetch articles');
        
        const data = await response.json();
        allArticles = Array.isArray(data) ? data : [];

        // Render articles
        if (mediaSections.articles) {
            renderArticles(allArticles);
        }
    } catch (error) {
        console.error('Error loading articles:', error);
        if (mediaSections.articles) {
            mediaSections.articles.innerHTML = '<p style="text-align: center; color: #dc3545;">Error loading articles.</p>';
        }
    }
}

function renderArticles(articles) {
    if (!mediaSections.articles) return;
    
    if (articles.length === 0) {
        mediaSections.articles.innerHTML = '<p style="text-align: center; color: #666;">No articles available yet.</p>';
        return;
    }

    mediaSections.articles.innerHTML = articles.map(article => {
        return `
            <div class="article">
                <h3>${article.title}</h3>
                <h4>${article.summary || ''}</h4>
                <h4><i>By ${article.author}</i></h4>
                <a href="${article.link}" target="_blank">Read More</a>
            </div>
        `;
    }).join('');
}

// ========== VIDEOS ==========
async function loadVideos() {
    try {
        const response = await fetch('../helpers/fetch.php?action=videos');
        if (!response.ok) throw new Error('Failed to fetch videos');
        
        const data = await response.json();
        allVideos = Array.isArray(data) ? data : [];

        // Render videos
        if (mediaSections.videos) {
            renderVideos(allVideos);
        }
    } catch (error) {
        console.error('Error loading videos:', error);
        if (mediaSections.videos) {
            mediaSections.videos.innerHTML = '<p style="text-align: center; color: #dc3545;">Error loading videos.</p>';
        }
    }
}

function renderVideos(videos) {
    if (!mediaSections.videos) return;
    
    if (videos.length === 0) {
        mediaSections.videos.innerHTML = '<p style="text-align: center; color: #666;">No videos available yet.</p>';
        return;
    }

    mediaSections.videos.innerHTML = videos.map(video => {
        return `
            <div class="video-item">
                <h3>${video.title}</h3>
                <div class="iframe">
                    <iframe src="${video.embed_url || video.url}" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen>
                    </iframe>
                </div>
                ${video.description ? `<p>${video.description}</p>` : ''}
                <p><small>${video.date || ''}</small></p>
            </div>
        `;
    }).join('');
}

// ========== PHOTO ALBUM VIEWER ==========
async function openAlbum(folderId, albumTitle) {
    try {
        // Hide all other media sections
        hideAllMediaSections();
        
        // Show loading state
        document.getElementById('albums-grid').style.display = 'none';
        
        // Create or get photo viewer container
        let viewer = document.getElementById('photo-viewer');
        if (!viewer) {
            viewer = document.createElement('div');
            viewer.id = 'photo-viewer';
            viewer.className = 'photo-viewer';
            document.querySelector('.photosDiv').appendChild(viewer);
        }
        
        viewer.innerHTML = `
            <div class="viewer-header">
                <h2>${albumTitle}</h2>
                <button onclick="closeAlbumViewer()" class="close-btn">×</button>
            </div>
            <div class="viewer-content">
                <div id="photos-container" class="photos-grid"></div>
                <div id="loading-photos" class="loading">Loading photos...</div>
            </div>
        `;
        
        viewer.style.display = 'block';
        
        // Fetch photos from the Google Drive folder
        await loadAlbumPhotos(folderId);
        
    } catch (error) {
        console.error('Error opening album:', error);
        alert('Failed to load album photos. Please try again.');
    }
}

async function loadAlbumPhotos(folderId) {
    try {
        // Fetch photos from backend (which will access Google Drive)
        const response = await fetch(`../helpers/fetch.php?action=album_photos&folder_id=${folderId}`);
        if (!response.ok) throw new Error('Failed to fetch photos');
        
        const photos = await response.json();
        const container = document.getElementById('photos-container');
        const loading = document.getElementById('loading-photos');
        
        if (!photos || photos.length === 0) {
            container.innerHTML = '<p style="text-align: center; color: #666; grid-column: 1/-1;">No photos found in this album.</p>';
            if (loading) loading.style.display = 'none';
            return;
        }
        
        // Display photos
        container.innerHTML = photos.map(photo => {
            // Use thumbnail for preview, full image for lightbox
            return `
                <div class="photo-item">
                    <img src="${photo.thumbnail_url || photo.url}" 
                         alt="${photo.name || 'Photo'}" 
                         onclick="openLightbox('${photo.url}', '${photo.name || ''}')"
                         loading="lazy">
                    ${photo.name ? `<p class="photo-name">${photo.name}</p>` : ''}
                </div>
            `;
        }).join('');
        
        if (loading) loading.style.display = 'none';
        
    } catch (error) {
        console.error('Error loading album photos:', error);
        const container = document.getElementById('photos-container');
        container.innerHTML = '<p style="text-align: center; color: #dc3545;">Error loading photos.</p>';
    }
}

function closeAlbumViewer() {
    const viewer = document.getElementById('photo-viewer');
    if (viewer) {
        viewer.style.display = 'none';
        viewer.innerHTML = '';
    }
    
    // Show albums grid again
    document.getElementById('albums-grid').style.display = 'grid';
}

function openLightbox(imageUrl, imageName) {
    // Create lightbox modal
    const lightbox = document.createElement('div');
    lightbox.className = 'lightbox';
    lightbox.innerHTML = `
        <div class="lightbox-content">
            <button class="lightbox-close" onclick="closeLightbox()">×</button>
            <img src="${imageUrl}" alt="${imageName}">
            ${imageName ? `<p class="lightbox-caption">${imageName}</p>` : ''}
        </div>
    `;
    
    document.body.appendChild(lightbox);
    
    // Close on background click
    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox) {
            closeLightbox();
        }
    });
}

function closeLightbox() {
    const lightbox = document.querySelector('.lightbox');
    if (lightbox) {
        lightbox.remove();
    }
}

// ========== MEDIA NAVIGATION ==========
function display(section) {
    // Hide all sections first
    hideAllMediaSections();
    
    // Show selected section
    switch(section) {
        case 'sermons':
            if (mediaSections.sermons) mediaSections.sermons.style.display = 'grid';
            break;
        case 'articles':
            if (mediaSections.articles) mediaSections.articles.style.display = 'grid';
            break;
        case 'photos':
            if (mediaSections.photos) mediaSections.photos.style.display = 'block';
            // Also show albums grid if it exists
            const albumsGrid = document.getElementById('albums-grid');
            if (albumsGrid) albumsGrid.style.display = 'grid';
            break;
        case 'videos':
            if (mediaSections.videos) mediaSections.videos.style.display = 'grid';
            break;
    }
    
    // Update active tab
    updateActiveTab(section);
}

function hideAllMediaSections() {
    // Hide main media sections
    Object.values(mediaSections).forEach(section => {
        if (section) section.style.display = 'none';
    });
    
    // Hide photo viewer
    const viewer = document.getElementById('photo-viewer');
    if (viewer) viewer.style.display = 'none';
    
    // Hide albums grid
    const albumsGrid = document.getElementById('albums-grid');
    if (albumsGrid) albumsGrid.style.display = 'none';
}

function updateActiveTab(section) {
    const tabs = document.querySelectorAll('.media-tab');
    tabs.forEach(tab => {
        tab.classList.remove('active');
        if (tab.dataset.section === section) {
            tab.classList.add('active');
        }
    });
}

// ========== INITIALIZATION ==========
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tab click handlers
    const tabs = document.querySelectorAll('.media-tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const section = this.dataset.section;
            display(section);
        });
    });
    
    // Load all media
    loadAllMedia();
});