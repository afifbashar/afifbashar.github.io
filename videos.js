// YouTube API configuration
const YOUTUBE_CHANNEL_ID = '@drafifbashar'; // Replace with your actual channel ID
const YOUTUBE_API_KEY = 'YOUR_API_KEY'; // Replace with your actual API key
const MAX_RESULTS = 6;

// Function to fetch latest videos from YouTube channel
async function fetchLatestVideos() {
    try {
        const response = await fetch(
            `https://www.googleapis.com/youtube/v3/search?key=${YOUTUBE_API_KEY}&channelId=${YOUTUBE_CHANNEL_ID}&part=snippet,id&order=date&maxResults=${MAX_RESULTS}`
        );
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        return data.items || [];
    } catch (error) {
        console.error('Error fetching videos:', error);
        return [];
    }
}

// Function to show loading state
function showLoading() {
    const videoGrid = document.getElementById('videoGrid');
    videoGrid.innerHTML = `
        <div class="text-center w-100">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading videos...</p>
        </div>
    `;
}

// Function to show error state
function showError() {
    const videoGrid = document.getElementById('videoGrid');
    videoGrid.innerHTML = `
        <div class="alert alert-warning text-center" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Unable to load videos at the moment. Please try again later.
        </div>
    `;
}

// Function to create video card HTML
function createVideoCard(video) {
    if (!video || !video.id || !video.id.videoId || !video.snippet) {
        return '';
    }

    const videoId = video.id.videoId;
    const title = video.snippet.title;
    const publishedAt = new Date(video.snippet.publishedAt).toLocaleDateString();

    return `
        <div class="video-card" data-aos="fade-up">
            <div class="video-thumbnail">
                <iframe
                    src="https://www.youtube.com/embed/${videoId}"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                ></iframe>
            </div>
            <div class="video-info">
                <h3 class="video-title">${title}</h3>
                <p class="video-date">Published on ${publishedAt}</p>
            </div>
        </div>
    `;
}

// Function to display videos in the grid
async function displayVideos() {
    const videoGrid = document.getElementById('videoGrid');
    
    if (!videoGrid) {
        console.error('Video grid element not found');
        return;
    }

    try {
        showLoading();
        const videos = await fetchLatestVideos();
        
        if (videos && videos.length > 0) {
            const videoCardsHTML = videos
                .map(video => createVideoCard(video))
                .filter(card => card) // Remove empty strings
                .join('');
            
            if (videoCardsHTML) {
                videoGrid.innerHTML = videoCardsHTML;
            } else {
                videoGrid.innerHTML = '<p class="text-center">No videos available at the moment.</p>';
            }
        } else {
            videoGrid.innerHTML = '<p class="text-center">No videos available at the moment.</p>';
        }
    } catch (error) {
        console.error('Error displaying videos:', error);
        showError();
    }
}

// Initialize videos when the page loads
document.addEventListener('DOMContentLoaded', displayVideos);