<?php
/**
 * Theme setup functions
 *
 * @package TrendToday
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Setup theme defaults and register support for various WordPress features
 *
 * @package TrendToday
 * @since 1.0.0
 */
function trendtoday_theme_setup() {
    /*
     * Make theme available for translation.
     * Translations can be filed in the /languages/ directory.
     */
    load_theme_textdomain( 'trendtoday', get_template_directory() . '/languages' );

    /*
     * On Theme Settings admin page, use site language so labels/descriptions
     * follow Site Language (Settings → General) instead of user profile language.
     */
    add_filter( 'determine_locale', 'trendtoday_locale_on_settings_page', 5 );
    function trendtoday_locale_on_settings_page( $locale ) {
        if ( is_admin() && isset( $_GET['page'] ) && 'trendtoday-settings' === $_GET['page'] ) {
            $site_locale = get_option( 'WPLANG' );
            return $site_locale ? $site_locale : 'en_US';
        }
        return $locale;
    }

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support( 'title-tag' );

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support( 'post-thumbnails' );

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );

    /*
     * Enable support for Post Formats.
     *
     * @link https://developer.wordpress.org/themes/functionality/post-formats/
     */
    add_theme_support( 'post-formats', array(
        'aside',
        'gallery',
        'quote',
        'image',
        'video',
        'audio',
    ) );

    /*
     * Enable support for Custom Logo.
     *
     * @link https://developer.wordpress.org/themes/functionality/custom-logo/
     */
    add_theme_support( 'custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => array( 'site-title', 'site-description' ),
    ) );

    /*
     * Add default posts and comments RSS feed links to head.
     */
    add_theme_support( 'automatic-feed-links' );

    /*
     * Enable support for responsive embedded content.
     *
     * @link https://wordpress.org/gutenberg/handbook/designers-developers/developers/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support( 'responsive-embeds' );

    /*
     * Enable support for wide and full alignment for Gutenberg blocks.
     */
    add_theme_support( 'align-wide' );

    /*
     * Enable support for editor styles.
     */
    add_theme_support( 'editor-styles' );

    /*
     * Enable selective refresh for widgets in Customizer.
     */
    add_theme_support( 'customize-selective-refresh-widgets' );

    /*
     * Enable support for custom background.
     */
    add_theme_support( 'custom-background', apply_filters( 'trendtoday_custom_background_args', array(
        'default-color' => 'ffffff',
        'default-image' => '',
    ) ) );

    /*
     * Enable support for custom header.
     */
    add_theme_support( 'custom-header', apply_filters( 'trendtoday_custom_header_args', array(
        'default-image'      => '',
        'default-text-color' => '000000',
        'width'              => 1920,
        'height'             => 400,
        'flex-height'        => true,
        'wp-head-callback'   => 'trendtoday_header_style',
    ) ) );

    /*
     * Register navigation menu locations.
     */
    register_nav_menus( array(
        'primary'          => esc_html__( 'Primary Menu', 'trendtoday' ),
        'footer'           => esc_html__( 'Footer Menu', 'trendtoday' ),
        'footer_copyright' => esc_html__( 'Footer Copyright Menu', 'trendtoday' ),
    ) );

    /*
     * Set the content width in pixels, based on the theme's design and stylesheet.
     *
     * Priority 0 to make it available to lower priority callbacks.
     *
     * @global int $content_width
     */
    $GLOBALS['content_width'] = apply_filters( 'trendtoday_content_width', 1200 );

    /*
     * Add custom image sizes for theme.
     */
    add_image_size( 'trendtoday-hero', 1920, 800, true );
    add_image_size( 'trendtoday-card', 600, 400, true );
    add_image_size( 'trendtoday-thumbnail', 300, 200, true );
    add_image_size( 'trendtoday-featured', 1200, 600, true );
}
add_action( 'after_setup_theme', 'trendtoday_theme_setup' );

/**
 * Set up the WordPress core custom header feature.
 *
 * @package TrendToday
 * @since 1.0.0
 */
function trendtoday_header_style() {
    $header_text_color = get_header_textcolor();

    /*
     * If no custom options for text are set, let's bail.
     * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
     */
    if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
        return;
    }

    // If we get this far, we have custom styles. Let's do this.
    ?>
    <style type="text/css">
    <?php
    // Has the text been hidden?
    if ( ! display_header_text() ) :
        ?>
        .site-title,
        .site-description {
            position: absolute;
            clip: rect(1px, 1px, 1px, 1px);
        }
        <?php
        // If the user has set a custom color for the text use that.
    else :
        ?>
        .site-title a,
        .site-description {
            color: #<?php echo esc_attr( $header_text_color ); ?>;
        }
    <?php endif; ?>
    </style>
    <?php
}

/**
 * Register widget areas.
 *
 * @package TrendToday
 * @since 1.0.0
 */
function trendtoday_widgets_init() {
    // Main Sidebar
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'trendtoday' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'trendtoday' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300 mb-6">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title font-bold text-xl mb-5 flex items-center gap-2">',
        'after_title'   => '</h3>',
    ) );

    // After Content Widget Area (after post content)
    register_sidebar( array(
        'name'          => esc_html__( 'After Content', 'trendtoday' ),
        'id'            => 'after-content',
        'description'   => esc_html__( 'Add widgets here to appear after post content.', 'trendtoday' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s my-8">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title font-bold text-xl mb-4 flex items-center gap-2">',
        'after_title'   => '</h3>',
    ) );

    // Footer1 (first footer column)
    register_sidebar( array(
        'name'          => esc_html__( 'Footer1', 'trendtoday' ),
        'id'            => 'footer-1',
        'description'   => esc_html__( 'Footer column 1. Add widgets (Custom HTML, menu, social, etc.) or choose display type in Theme Settings > Footer.', 'trendtoday' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h4 class="widget-title font-bold text-gray-900 mb-4 flex items-center gap-2">',
        'after_title'   => '</h4>',
    ) );

    // Footer2 (second footer column)
    register_sidebar( array(
        'name'          => esc_html__( 'Footer2', 'trendtoday' ),
        'id'            => 'footer-2',
        'description'   => esc_html__( 'Footer column 2. Add widgets or choose display type in Theme Settings > Footer.', 'trendtoday' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h4 class="widget-title font-bold text-gray-900 mb-4 flex items-center gap-2">',
        'after_title'   => '</h4>',
    ) );

    // Footer3 (third footer column)
    register_sidebar( array(
        'name'          => esc_html__( 'Footer3', 'trendtoday' ),
        'id'            => 'footer-3',
        'description'   => esc_html__( 'Footer column 3. Add widgets or choose display type in Theme Settings > Footer.', 'trendtoday' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h4 class="widget-title font-bold text-gray-900 mb-4 flex items-center gap-2">',
        'after_title'   => '</h4>',
    ) );

    // Footer4 (fourth footer column)
    register_sidebar( array(
        'name'          => esc_html__( 'Footer4', 'trendtoday' ),
        'id'            => 'footer-4',
        'description'   => esc_html__( 'Footer column 4. Add widgets or choose display type in Theme Settings > Footer.', 'trendtoday' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h4 class="widget-title font-bold text-gray-900 mb-4 flex items-center gap-2">',
        'after_title'   => '</h4>',
    ) );
}
add_action( 'widgets_init', 'trendtoday_widgets_init' );

/**
 * Add custom image size names to media library.
 *
 * @package TrendToday
 * @since 1.0.0
 *
 * @param array $sizes Array of image size names.
 * @return array Modified array of image size names.
 */
function trendtoday_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'trendtoday-hero'      => __( 'Hero Image', 'trendtoday' ),
        'trendtoday-card'      => __( 'Card Image', 'trendtoday' ),
        'trendtoday-thumbnail' => __( 'Thumbnail', 'trendtoday' ),
        'trendtoday-featured'  => __( 'Featured Image', 'trendtoday' ),
    ) );
}
add_filter( 'image_size_names_choose', 'trendtoday_custom_image_sizes' );
