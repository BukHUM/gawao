// Recently Viewed Functions
function addToRecentlyViewed(title) {
    let viewed = JSON.parse(localStorage.getItem('recentlyViewed') || '[]');
    
    // Remove if already exists
    viewed = viewed.filter(item => item.title !== title);
    
    // Add to beginning
    viewed.unshift({
        title: title,
        timestamp: new Date().toISOString(),
        date: new Date().toLocaleDateString('th-TH', { day: 'numeric', month: 'short', year: 'numeric' })
    });
    
    // Keep only last 5 items
    viewed = viewed.slice(0, 5);
    
    localStorage.setItem('recentlyViewed', JSON.stringify(viewed));
    updateRecentlyViewed();
}

function updateRecentlyViewed() {
    const viewed = JSON.parse(localStorage.getItem('recentlyViewed') || '[]');
    
    // Update in news detail page
    const newsList = document.getElementById('recently-viewed-list');
    if (newsList) {
        if (viewed.length === 0) {
            newsList.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">ยังไม่มีประวัติการดู</p>';
        } else {
            newsList.innerHTML = viewed.map(item => `
                <div class="flex gap-3 cursor-pointer group" onclick="navigateTo('news')">
                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="ph ph-clock-clockwise text-gray-400 text-xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h5 class="font-semibold text-sm text-dark group-hover:text-green-600 leading-tight line-clamp-2">${item.title}</h5>
                        <span class="text-xs text-gray-400 mt-1 block">${item.date}</span>
                    </div>
                </div>
            `).join('');
        }
    }
    
    // Update in single post page
    const singleList = document.getElementById('recently-viewed-list-single');
    if (singleList) {
        if (viewed.length === 0) {
            singleList.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">ยังไม่มีประวัติการดู</p>';
        } else {
            singleList.innerHTML = viewed.map(item => `
                <div class="flex gap-3 cursor-pointer group" onclick="navigateTo('single')">
                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="ph ph-clock-clockwise text-gray-400 text-xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h5 class="font-semibold text-sm text-dark group-hover:text-primary leading-tight line-clamp-2">${item.title}</h5>
                        <span class="text-xs text-gray-400 mt-1 block">${item.date}</span>
                    </div>
                </div>
            `).join('');
        }
    }
}

// Track page views for AI recommendations (mockup)
function trackPageView(pageType, title) {
    let pageViews = JSON.parse(localStorage.getItem('pageViews') || '{}');
    if (!pageViews[pageType]) {
        pageViews[pageType] = [];
    }
    pageViews[pageType].push({
        title: title,
        timestamp: new Date().toISOString()
    });
    localStorage.setItem('pageViews', JSON.stringify(pageViews));
}

// Load recently viewed on page load
document.addEventListener('DOMContentLoaded', function() {
    updateRecentlyViewed();
});

// Update when pages are loaded
document.addEventListener('pageLoaded', function() {
    setTimeout(updateRecentlyViewed, 100);
});
