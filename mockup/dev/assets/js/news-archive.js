// News Archive Functions
function handleNewsSearch(event) {
    event.preventDefault();
    const query = document.getElementById('news-search-input').value.trim();
    if (query) {
        // In real app, this would filter/search news
        console.log('Searching for:', query);
    }
}

function clearNewsFilters() {
    // Reset all filters
    document.querySelectorAll('#view-news-archive input[type="checkbox"]').forEach(cb => cb.checked = false);
    document.querySelectorAll('#view-news-archive input[type="radio"]').forEach(radio => {
        if (radio.value === 'all') radio.checked = true;
        else radio.checked = false;
    });
    const searchInput = document.getElementById('news-search-input');
    if (searchInput) searchInput.value = '';
    const activeFilters = document.getElementById('active-filters');
    if (activeFilters) activeFilters.innerHTML = '';
}

function updateActiveFilters() {
    const activeFilters = document.getElementById('active-filters');
    if (!activeFilters) return;
    
    activeFilters.innerHTML = '';
    const checkedCategories = document.querySelectorAll('#view-news-archive input[name="category"]:checked');
    const selectedDate = document.querySelector('#view-news-archive input[name="date-filter"]:checked');
    
    checkedCategories.forEach(cb => {
        const label = cb.nextElementSibling.textContent.trim();
        const span = document.createElement('span');
        span.className = 'bg-primary/10 text-primary px-3 py-1 rounded-full text-sm flex items-center gap-2';
        span.innerHTML = `${label} <button onclick="removeFilter('${cb.value}')" class="hover:bg-primary/20 rounded-full p-0.5"><i class="ph ph-x text-xs"></i></button>`;
        activeFilters.appendChild(span);
    });
    
    if (selectedDate && selectedDate.value !== 'all') {
        const label = selectedDate.nextElementSibling.textContent.trim();
        const span = document.createElement('span');
        span.className = 'bg-secondary/10 text-secondary px-3 py-1 rounded-full text-sm flex items-center gap-2';
        span.innerHTML = `${label} <button onclick="removeDateFilter()" class="hover:bg-secondary/20 rounded-full p-0.5"><i class="ph ph-x text-xs"></i></button>`;
        activeFilters.appendChild(span);
    }
}

function removeFilter(value) {
    const checkbox = document.querySelector(`#view-news-archive input[name="category"][value="${value}"]`);
    if (checkbox) checkbox.checked = false;
    updateActiveFilters();
}

function removeDateFilter() {
    const allRadio = document.querySelector('#view-news-archive input[name="date-filter"][value="all"]');
    if (allRadio) allRadio.checked = true;
    updateActiveFilters();
}

// Initialize news archive filters when page is loaded
function initNewsArchiveFilters() {
    const filterInputs = document.querySelectorAll('#view-news-archive input[type="checkbox"], #view-news-archive input[type="radio"]');
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            updateActiveFilters();
        });
    });
}

document.addEventListener('pageLoaded', function(event) {
    if (event.detail.pageName === 'news-archive') {
        setTimeout(initNewsArchiveFilters, 100);
    }
});
