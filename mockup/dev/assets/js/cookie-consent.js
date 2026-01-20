// Cookie Consent Functions
function checkCookieConsent() {
    const consent = localStorage.getItem('cookieConsent');
    if (!consent) {
        // Show cookie banner after 1 second
        setTimeout(() => {
            const banner = document.getElementById('cookie-consent');
            if (banner) {
                banner.classList.remove('translate-y-full');
            }
        }, 1000);
    }
}

function acceptCookies() {
    localStorage.setItem('cookieConsent', 'accepted');
    localStorage.setItem('cookieNecessary', 'true');
    localStorage.setItem('cookieAnalytics', 'true');
    localStorage.setItem('cookieMarketing', 'true');
    localStorage.setItem('cookieFunctional', 'true');
    hideCookieBanner();
}

function declineCookies() {
    localStorage.setItem('cookieConsent', 'declined');
    localStorage.setItem('cookieNecessary', 'true');
    localStorage.setItem('cookieAnalytics', 'false');
    localStorage.setItem('cookieMarketing', 'false');
    localStorage.setItem('cookieFunctional', 'false');
    hideCookieBanner();
}

function hideCookieBanner() {
    const banner = document.getElementById('cookie-consent');
    if (banner) {
        banner.classList.add('translate-y-full');
    }
    closeCookiePreferences();
}

function openCookiePreferences() {
    const modal = document.getElementById('cookie-preferences-modal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Load saved preferences
        const analytics = localStorage.getItem('cookieAnalytics') === 'true';
        const marketing = localStorage.getItem('cookieMarketing') === 'true';
        const functional = localStorage.getItem('cookieFunctional') === 'true';
        
        const analyticsEl = document.getElementById('cookie-analytics');
        const marketingEl = document.getElementById('cookie-marketing');
        const functionalEl = document.getElementById('cookie-functional');
        
        if (analyticsEl) analyticsEl.checked = analytics;
        if (marketingEl) marketingEl.checked = marketing;
        if (functionalEl) functionalEl.checked = functional;
    }
}

function closeCookiePreferences() {
    const modal = document.getElementById('cookie-preferences-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

function saveCookiePreferences() {
    const analyticsEl = document.getElementById('cookie-analytics');
    const marketingEl = document.getElementById('cookie-marketing');
    const functionalEl = document.getElementById('cookie-functional');
    
    const analytics = analyticsEl ? analyticsEl.checked : false;
    const marketing = marketingEl ? marketingEl.checked : false;
    const functional = functionalEl ? functionalEl.checked : false;
    
    localStorage.setItem('cookieConsent', 'custom');
    localStorage.setItem('cookieNecessary', 'true');
    localStorage.setItem('cookieAnalytics', analytics ? 'true' : 'false');
    localStorage.setItem('cookieMarketing', marketing ? 'true' : 'false');
    localStorage.setItem('cookieFunctional', functional ? 'true' : 'false');
    
    hideCookieBanner();
    alert('บันทึกการตั้งค่าคุกกี้เรียบร้อยแล้ว');
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('cookie-preferences-modal');
    if (modal && event.target === modal) {
        closeCookiePreferences();
    }
});

// Initialize cookie consent check on page load
document.addEventListener('DOMContentLoaded', function() {
    checkCookieConsent();
});
