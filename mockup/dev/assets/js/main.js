// Main Navigation and Core Functions

// Breadcrumb Management
function updateBreadcrumb(pageName) {
    const breadcrumb = document.getElementById('breadcrumb');
    const breadcrumbCurrent = document.getElementById('breadcrumb-current');
    const breadcrumbSeparator = document.getElementById('breadcrumb-separator');
    
    if (!breadcrumb || !breadcrumbCurrent) return;
    
    const config = breadcrumbConfig[pageName];
    
    if (config && config.show) {
        breadcrumb.classList.remove('hidden');
        breadcrumbCurrent.textContent = config.text;
        breadcrumbSeparator.classList.remove('hidden');
    } else {
        breadcrumb.classList.add('hidden');
    }
}

// Main Navigation Function
function navigateTo(pageName) {
    // Scroll to top
    window.scrollTo(0, 0);
    
    // Hide all views
    const views = document.querySelectorAll('.page-view');
    views.forEach(view => {
        view.classList.remove('active');
    });

    // Show target view
    const target = document.getElementById('view-' + pageName);
    if (target) {
        target.classList.add('active');
        
        // Update breadcrumb
        updateBreadcrumb(pageName);
        
        // Focus search input if search page
        if (pageName === 'search') {
            setTimeout(() => {
                const searchInput = document.getElementById('search-input');
                if (searchInput) {
                    searchInput.focus();
                }
            }, 100);
        }
    }
}

// Mobile Submenu Toggle
function toggleMobileSubmenu(submenuId) {
    const submenu = document.getElementById(submenuId);
    const icon = document.getElementById(submenuId + '-icon');
    
    if (submenu && icon) {
        submenu.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateBreadcrumb('home');
});
