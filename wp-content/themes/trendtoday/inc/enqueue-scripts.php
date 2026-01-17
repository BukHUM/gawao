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

    // Tailwind CSS is now loaded directly in header.php before wp_head()
    // This ensures it loads before all other styles

    // Theme stylesheet (style.css in theme root)
    $style_uri = get_stylesheet_uri();
    wp_enqueue_style(
        'trendtoday-style',
        $style_uri,
        array( 'trendtoday-google-fonts', 'font-awesome' ),
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

    // Localize script for AJAX
    wp_localize_script( 'trendtoday-main', 'trendtodayAjax', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'trendtoday-nonce' ),
    ) );

    // Comment reply script
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
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
 * Enqueue admin styles
 */
function trendtoday_enqueue_admin_styles( $hook ) {
    // Load on post list and edit screens
    if ( ! in_array( $hook, array( 'post.php', 'post-new.php', 'edit.php' ) ) ) {
        return;
    }

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
add_action( 'admin_enqueue_scripts', 'trendtoday_enqueue_admin_styles' );

/**
 * Add Tailwind CSS CDN to head
 * Must be added early in head before other styles
 */
function trendtoday_add_tailwind_cdn() {
    // Only add once
    static $added = false;
    if ( $added ) {
        return;
    }
    $added = true;
    ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ["Prompt", "sans-serif"],
                },
                colors: {
                    primary: "#1a1a1a",
                    accent: "#FF4500",
                    "news-tech": "#3B82F6",
                    "news-ent": "#EC4899",
                    "news-fin": "#10B981",
                    "news-sport": "#F59E0B",
                }
            }
        }
    }
    </script>
    <?php
}
