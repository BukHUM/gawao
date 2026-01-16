/**
 * Custom JavaScript for Trend Today Theme
 * 
 * @package TrendToday
 * @since 1.0.0
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // Mobile Menu Toggle
        window.toggleMobileMenu = function() {
            const menu = document.getElementById('mobile-menu');
            const button = document.getElementById('mobile-menu-button');
            const icon = document.getElementById('menu-icon');
            
            if (!menu || !button || !icon) return;
            
            const isHidden = menu.classList.contains('hidden');
            
            if (isHidden) {
                menu.classList.remove('hidden');
                button.setAttribute('aria-expanded', 'true');
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                menu.classList.add('hidden');
                button.setAttribute('aria-expanded', 'false');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        };

        // Category Filtering
        $('.category-filter').on('click', function() {
            const category = $(this).data('category');
            
            // Remove active class from all filters
            $('.category-filter').removeClass('active bg-accent text-white').addClass('bg-gray-100 text-gray-700');
            
            // Add active class to clicked filter
            $(this).addClass('active bg-accent text-white').removeClass('bg-gray-100 text-gray-700');
            
            // Filter posts via AJAX
            if (typeof trendtodayAjax !== 'undefined') {
                $.ajax({
                    url: trendtodayAjax.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'filter_posts',
                        category: category,
                        nonce: trendtodayAjax.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#news-grid').html(response.data.html);
                        }
                    }
                });
            }
        });

        // Load More Posts
        $('#load-more-btn').on('click', function() {
            const button = $(this);
            const currentPage = parseInt(button.data('page')) || 1;
            const nextPage = currentPage + 1;
            
            button.prop('disabled', true);
            button.html('<i class="fas fa-spinner fa-spin mr-2"></i>กำลังโหลด...');
            
            if (typeof trendtodayAjax !== 'undefined') {
                $.ajax({
                    url: trendtodayAjax.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'load_more_posts',
                        page: nextPage,
                        nonce: trendtodayAjax.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#news-grid').append(response.data.html);
                            button.data('page', nextPage);
                            
                            if (!response.data.has_more) {
                                button.hide();
                            } else {
                                button.prop('disabled', false);
                                button.html('<span class="relative z-10">โหลดข่าวเพิ่มเติม</span><i class="fas fa-arrow-down ml-2 relative z-10"></i>');
                            }
                        }
                    },
                    error: function() {
                        button.prop('disabled', false);
                        button.html('<span class="relative z-10">โหลดข่าวเพิ่มเติม</span><i class="fas fa-arrow-down ml-2 relative z-10"></i>');
                    }
                });
            }
        });

        // Back to Top Button
        const backToTopBtn = $('#back-to-top');
        if (backToTopBtn.length) {
            $(window).scroll(function() {
                if ($(this).scrollTop() > 300) {
                    backToTopBtn.removeClass('opacity-0 invisible').addClass('opacity-100 visible');
                } else {
                    backToTopBtn.addClass('opacity-0 invisible').removeClass('opacity-100 visible');
                }
            });
        }

        // Newsletter Form
        $('form[onsubmit*="handleNewsletterSubmit"]').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const email = form.find('input[type="email"]').val();
            const button = form.find('button[type="submit"]');
            const originalText = button.html();
            
            if (email) {
                button.html('<i class="fas fa-spinner fa-spin mr-2"></i>กำลังส่ง...');
                button.prop('disabled', true);
                
                // Simulate API call
                setTimeout(function() {
                    alert('ขอบคุณที่สมัครรับข่าวสาร! เราจะส่งอีเมลยืนยันให้คุณเร็วๆ นี้');
                    form[0].reset();
                    button.html(originalText);
                    button.prop('disabled', false);
                }, 1500);
            }
        });
    });

    // Footer Toggle Function (for mobile accordion)
    window.toggleFooter = function(button) {
        if (!button) return;
        
        const $button = $(button);
        const $icon = $button.find('i.fa-chevron-down');
        const $links = $button.next('.footer-links');
        
        if ($links.length) {
            const isHidden = $links.hasClass('hidden');
            
            if (isHidden) {
                $links.removeClass('hidden').slideDown(300);
                $icon.addClass('rotate-180');
            } else {
                $links.slideUp(300, function() {
                    $(this).addClass('hidden');
                });
                $icon.removeClass('rotate-180');
            }
        }
    };

})(jQuery);
