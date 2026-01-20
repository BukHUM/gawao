// Search Functionality
function handleSearch(event) {
    event.preventDefault();
    const searchInput = document.getElementById('search-input');
    const query = searchInput.value.trim();
    
    if (query) {
        // Hide suggestions
        document.getElementById('search-suggestions').classList.add('hidden');
        
        // Update results count (in real app, this would fetch from API)
        const resultsCount = document.querySelector('#view-search .text-gray-600');
        if (resultsCount) {
            resultsCount.innerHTML = `พบผลลัพธ์ <span class="font-bold text-dark">24</span> รายการ สำหรับ "<span class="font-semibold text-dark">${query}</span>"`;
        }
        
        // Show results, hide no-results
        document.getElementById('search-results').classList.remove('hidden');
        document.getElementById('no-results').classList.add('hidden');
    }
}

function setSearchQuery(query) {
    const searchInput = document.getElementById('search-input');
    searchInput.value = query;
    document.getElementById('search-suggestions').classList.add('hidden');
    handleSearch(new Event('submit'));
}

// Show/hide search suggestions
function initSearchSuggestions() {
    const searchInput = document.getElementById('search-input');
    const suggestions = document.getElementById('search-suggestions');
    
    if (searchInput && suggestions) {
        searchInput.addEventListener('focus', function() {
            if (this.value.length === 0) {
                suggestions.classList.remove('hidden');
            }
        });
        
        searchInput.addEventListener('blur', function() {
            // Delay to allow click on suggestion
            setTimeout(() => {
                suggestions.classList.add('hidden');
            }, 200);
        });
        
        searchInput.addEventListener('input', function() {
            if (this.value.length > 0) {
                suggestions.classList.add('hidden');
            } else {
                suggestions.classList.remove('hidden');
            }
        });
    }
}

// Initialize search suggestions when search page is loaded
document.addEventListener('pageLoaded', function(event) {
    if (event.detail.pageName === 'search') {
        setTimeout(initSearchSuggestions, 100);
    }
});
