<?php
/**
 * Enqueue scripts and styles
 *
 * @package TrendToday
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue theme scripts and styles
 */
function trendtoday_enqueue_assets() {
    $version = trendtoday_get_theme_version();

    // Google Fonts (preconnect for performance)
    wp_enqueue_style(
        'trendtoday-google-fonts',
        'https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300&display=swap',
        array(),
        null
    );

    // Add preconnect for Google Fonts
    add_filter( 'style_loader_tag', 'trendtoday_add_preconnect_for_fonts', 10, 2 );

    // Font Awesome
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        array(),
        '6.4.0'
    );

    // Tailwind CSS (local build – no CDN)
    $tailwind_css = get_template_directory() . '/assets/css/tailwind.css';
    if ( file_exists( $tailwind_css ) ) {
        wp_enqueue_style(
            'trendtoday-tailwind',
            get_template_directory_uri() . '/assets/css/tailwind.css',
            array( 'trendtoday-google-fonts' ),
            filemtime( $tailwind_css )
        );
    }

    // Theme stylesheet (style.css in theme root)
    $style_uri = get_stylesheet_uri();
    $style_deps = array( 'trendtoday-google-fonts', 'font-awesome' );
    if ( file_exists( $tailwind_css ) ) {
        $style_deps[] = 'trendtoday-tailwind';
    }
    wp_enqueue_style(
        'trendtoday-style',
        $style_uri,
        $style_deps,
        $version
    );

    // Custom CSS
    $custom_css_file = get_template_directory() . '/assets/css/custom.css';
    if ( file_exists( $custom_css_file ) ) {
        wp_enqueue_style(
            'trendtoday-custom',
            get_template_directory_uri() . '/assets/css/custom.css',
            array( 'trendtoday-style' ),
            filemtime( $custom_css_file )
        );
    }

    // Print styles
    $print_css_file = get_template_directory() . '/assets/css/print.css';
    if ( file_exists( $print_css_file ) ) {
        wp_enqueue_style(
            'trendtoday-print',
            get_template_directory_uri() . '/assets/css/print.css',
            array( 'trendtoday-style' ),
            filemtime( $print_css_file ),
            'print'
        );
    }

    // Main JavaScript
    $main_js_file = get_template_directory() . '/assets/js/main.js';
    if ( file_exists( $main_js_file ) ) {
        wp_enqueue_script(
            'trendtoday-main',
            get_template_directory_uri() . '/assets/js/main.js',
            array( 'jquery' ),
            filemtime( $main_js_file ),
            true
        );

        // Custom JavaScript
        $custom_js_file = get_template_directory() . '/assets/js/custom.js';
        if ( file_exists( $custom_js_file ) ) {
            wp_enqueue_script(
                'trendtoday-custom',
                get_template_directory_uri() . '/assets/js/custom.js',
                array( 'trendtoday-main' ),
                filemtime( $custom_js_file ),
                true
            );
        }
    }
    
    // Add defer attribute for non-critical scripts
    add_filter( 'script_loader_tag', 'trendtoday_add_defer_to_scripts', 10, 2 );

    // Localize script for AJAX
    $search_enabled = get_option( 'trendtoday_search_enabled', '1' );
    $search_suggestions_enabled = get_option( 'trendtoday_search_suggestions_enabled', '1' );
    $search_live_enabled = get_option( 'trendtoday_search_live_enabled', '1' );
    $search_debounce = get_option( 'trendtoday_search_debounce', 300 );
    $search_min_length = get_option( 'trendtoday_search_min_length', 2 );
    $search_suggestions_style = get_option( 'trendtoday_search_suggestions_style', 'dropdown' );
    $search_placeholder = get_option( 'trendtoday_search_placeholder', __( 'พิมพ์คำค้นหา...', 'trendtoday' ) );
    
    wp_localize_script( 'trendtoday-main', 'trendtodayAjax', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'trendtoday-nonce' ),
        'searchUrl' => home_url( '/?s=' ),
        'search' => array(
            'enabled' => $search_enabled === '1',
            'suggestions_enabled' => $search_suggestions_enabled === '1',
            'live_enabled' => $search_live_enabled === '1',
            'debounce' => absint( $search_debounce ),
            'min_length' => absint( $search_min_length ),
            'style' => $search_suggestions_style,
            'placeholder' => $search_placeholder,
        ),
    ) );

    // Comment reply script (only on single posts with comments)
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
    
    // Conditional script loading: Only load custom.js on pages that need it
    $load_custom_js = true; // Default: load on all pages
    
    // Check if we're on a page that needs custom.js
    if ( is_front_page() || is_home() || is_archive() || is_search() || is_single() ) {
        $load_custom_js = true;
    } else {
        // Don't load on pages that don't need it
        $load_custom_js = false;
    }
    
    // Store flag for later use
    if ( ! $load_custom_js ) {
        // Remove custom.js if it was enqueued
        add_action( 'wp_print_scripts', function() {
            wp_dequeue_script( 'trendtoday-custom' );
        }, 100 );
    }

    // Lazy loading support
    wp_add_inline_script( 'trendtoday-main', '
        if ("loading" in HTMLImageElement.prototype) {
            const images = document.querySelectorAll("img[loading=\'lazy\']");
            images.forEach(img => {
                img.src = img.dataset.src || img.src;
            });
        }
    ', 'before' );
}
add_action( 'wp_enqueue_scripts', 'trendtoday_enqueue_assets' );

/**
 * Add defer attribute to non-critical scripts
 *
 * @param string $tag Script tag.
 * @param string $handle Script handle.
 * @return string Modified script tag.
 */
function trendtoday_add_defer_to_scripts( $tag, $handle ) {
    $defer_scripts = array( 'trendtoday-main', 'trendtoday-custom' );
    
    if ( in_array( $handle, $defer_scripts, true ) ) {
        // Add defer if not already present
        if ( strpos( $tag, ' defer' ) === false && strpos( $tag, 'defer' ) === false ) {
            $tag = str_replace( ' src', ' defer src', $tag );
        }
    }
    
    return $tag;
}

/**
 * Add preconnect for Google Fonts
 *
 * @param string $tag Link tag.
 * @param string $handle Style handle.
 * @return string Modified link tag.
 */
function trendtoday_add_preconnect_for_fonts( $tag, $handle ) {
    if ( 'trendtoday-google-fonts' === $handle ) {
        $preconnect = '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
        $preconnect .= '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
        return $preconnect . $tag;
    }
    return $tag;
}

/**
 * Enqueue admin styles and scripts
 */
function trendtoday_enqueue_admin_styles( $hook ) {
    // Load on post list and edit screens
    if ( in_array( $hook, array( 'post.php', 'post-new.php', 'edit.php' ) ) ) {
        wp_enqueue_style(
            'trendtoday-admin',
            get_template_directory_uri() . '/assets/css/admin.css',
            array(),
            trendtoday_get_theme_version()
        );
        
        // Add inline CSS for post list table column widths
        if ( 'edit.php' === $hook ) {
            $custom_css = '
            #posts-filter .wp-list-table,
            #posts-filter .widefat {
                table-layout: auto !important;
            }
            #posts-filter .wp-list-table th.column-title,
            #posts-filter .wp-list-table td.column-title {
                width: 50% !important;
                min-width: 400px !important;
                max-width: none !important;
            }
            .wp-list-table th.column-title,
            .wp-list-table td.column-title {
                width: 50% !important;
                min-width: 400px !important;
                max-width: none !important;
            }
            ';
            wp_add_inline_style( 'trendtoday-admin', $custom_css );
        }
    }
    
    // Load on theme settings page
    if ( strpos( $hook, 'trendtoday-settings' ) !== false ) {
        wp_enqueue_style(
            'trendtoday-admin',
            get_template_directory_uri() . '/assets/css/admin.css',
            array(),
            trendtoday_get_theme_version()
        );
        
        // Enqueue WordPress media uploader with proper dependencies
        wp_enqueue_media();
        
        // Enqueue jQuery and jQuery UI Sortable for widget order
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-ui-sortable' );
        
        // Localize script for AJAX (must be array)
        wp_localize_script( 'jquery', 'trendtodayAdmin', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'trendtoday_settings_nonce' ),
        ) );
    }
}
add_action( 'admin_enqueue_scripts', 'trendtoday_enqueue_admin_styles' );

