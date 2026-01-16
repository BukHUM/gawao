<?php
/**
 * Custom Post Types
 *
 * @package TrendToday
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Custom Post Types
 */
function trendtoday_register_post_types() {
    // Video News Post Type
    register_post_type(
        'video_news',
        array(
            'labels'              => array(
                'name'               => __( 'Video News', 'trendtoday' ),
                'singular_name'      => __( 'Video News', 'trendtoday' ),
                'menu_name'          => __( 'Video News', 'trendtoday' ),
                'add_new'            => __( 'Add New', 'trendtoday' ),
                'add_new_item'       => __( 'Add New Video News', 'trendtoday' ),
                'edit_item'          => __( 'Edit Video News', 'trendtoday' ),
                'new_item'           => __( 'New Video News', 'trendtoday' ),
                'view_item'          => __( 'View Video News', 'trendtoday' ),
                'search_items'       => __( 'Search Video News', 'trendtoday' ),
                'not_found'          => __( 'No video news found', 'trendtoday' ),
                'not_found_in_trash' => __( 'No video news found in Trash', 'trendtoday' ),
                'all_items'          => __( 'All Video News', 'trendtoday' ),
            ),
            'public'              => true,
            'has_archive'         => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-video-alt3',
            'capability_type'     => 'post',
            'hierarchical'        => false,
            'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields', 'revisions' ),
            'rewrite'             => array( 'slug' => 'video-news', 'with_front' => false ),
            'query_var'           => true,
            'can_export'          => true,
            'show_in_rest'        => true,
            'rest_base'           => 'video-news',
        )
    );

    // Gallery Post Type
    register_post_type(
        'gallery',
        array(
            'labels'              => array(
                'name'               => __( 'Photo Galleries', 'trendtoday' ),
                'singular_name'      => __( 'Photo Gallery', 'trendtoday' ),
                'menu_name'          => __( 'Photo Galleries', 'trendtoday' ),
                'add_new'            => __( 'Add New', 'trendtoday' ),
                'add_new_item'       => __( 'Add New Gallery', 'trendtoday' ),
                'edit_item'          => __( 'Edit Gallery', 'trendtoday' ),
                'new_item'           => __( 'New Gallery', 'trendtoday' ),
                'view_item'          => __( 'View Gallery', 'trendtoday' ),
                'search_items'       => __( 'Search Galleries', 'trendtoday' ),
                'not_found'          => __( 'No galleries found', 'trendtoday' ),
                'not_found_in_trash' => __( 'No galleries found in Trash', 'trendtoday' ),
                'all_items'          => __( 'All Galleries', 'trendtoday' ),
            ),
            'public'              => true,
            'has_archive'         => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-format-gallery',
            'capability_type'     => 'post',
            'hierarchical'        => false,
            'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields', 'revisions' ),
            'rewrite'             => array( 'slug' => 'gallery', 'with_front' => false ),
            'query_var'           => true,
            'can_export'          => true,
            'show_in_rest'        => true,
            'rest_base'           => 'galleries',
        )
    );

    // Featured Story Post Type (for special featured content)
    register_post_type(
        'featured_story',
        array(
            'labels'              => array(
                'name'               => __( 'Featured Stories', 'trendtoday' ),
                'singular_name'      => __( 'Featured Story', 'trendtoday' ),
                'menu_name'          => __( 'Featured Stories', 'trendtoday' ),
                'add_new'            => __( 'Add New', 'trendtoday' ),
                'add_new_item'       => __( 'Add New Featured Story', 'trendtoday' ),
                'edit_item'          => __( 'Edit Featured Story', 'trendtoday' ),
                'new_item'           => __( 'New Featured Story', 'trendtoday' ),
                'view_item'          => __( 'View Featured Story', 'trendtoday' ),
                'search_items'       => __( 'Search Featured Stories', 'trendtoday' ),
                'not_found'          => __( 'No featured stories found', 'trendtoday' ),
                'not_found_in_trash' => __( 'No featured stories found in Trash', 'trendtoday' ),
                'all_items'          => __( 'All Featured Stories', 'trendtoday' ),
            ),
            'public'              => true,
            'has_archive'         => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-star-filled',
            'capability_type'     => 'post',
            'hierarchical'        => false,
            'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields', 'revisions' ),
            'rewrite'             => array( 'slug' => 'featured', 'with_front' => false ),
            'query_var'           => true,
            'can_export'          => true,
            'show_in_rest'        => true,
            'rest_base'           => 'featured-stories',
        )
    );
}
add_action( 'init', 'trendtoday_register_post_types' );

/**
 * Flush rewrite rules on theme activation
 */
function trendtoday_flush_rewrite_rules() {
    trendtoday_register_post_types();
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'trendtoday_flush_rewrite_rules' );

/**
 * Register Custom Taxonomies for Custom Post Types
 */
function trendtoday_register_taxonomies() {
    // Video Category Taxonomy
    register_taxonomy(
        'video_category',
        array( 'video_news' ),
        array(
            'labels'            => array(
                'name'              => __( 'Video Categories', 'trendtoday' ),
                'singular_name'     => __( 'Video Category', 'trendtoday' ),
                'search_items'      => __( 'Search Video Categories', 'trendtoday' ),
                'all_items'         => __( 'All Video Categories', 'trendtoday' ),
                'parent_item'       => __( 'Parent Video Category', 'trendtoday' ),
                'parent_item_colon' => __( 'Parent Video Category:', 'trendtoday' ),
                'edit_item'         => __( 'Edit Video Category', 'trendtoday' ),
                'update_item'       => __( 'Update Video Category', 'trendtoday' ),
                'add_new_item'      => __( 'Add New Video Category', 'trendtoday' ),
                'new_item_name'     => __( 'New Video Category Name', 'trendtoday' ),
                'menu_name'         => __( 'Video Categories', 'trendtoday' ),
            ),
            'hierarchical'      => true,
            'show_ui'            => true,
            'show_admin_column'  => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'video-category' ),
            'show_in_rest'        => true,
        )
    );

    // Gallery Category Taxonomy
    register_taxonomy(
        'gallery_category',
        array( 'gallery' ),
        array(
            'labels'            => array(
                'name'              => __( 'Gallery Categories', 'trendtoday' ),
                'singular_name'     => __( 'Gallery Category', 'trendtoday' ),
                'search_items'      => __( 'Search Gallery Categories', 'trendtoday' ),
                'all_items'         => __( 'All Gallery Categories', 'trendtoday' ),
                'parent_item'       => __( 'Parent Gallery Category', 'trendtoday' ),
                'parent_item_colon' => __( 'Parent Gallery Category:', 'trendtoday' ),
                'edit_item'         => __( 'Edit Gallery Category', 'trendtoday' ),
                'update_item'       => __( 'Update Gallery Category', 'trendtoday' ),
                'add_new_item'      => __( 'Add New Gallery Category', 'trendtoday' ),
                'new_item_name'     => __( 'New Gallery Category Name', 'trendtoday' ),
                'menu_name'         => __( 'Gallery Categories', 'trendtoday' ),
            ),
            'hierarchical'      => true,
            'show_ui'            => true,
            'show_admin_column'  => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'gallery-category' ),
            'show_in_rest'        => true,
        )
    );
}
add_action( 'init', 'trendtoday_register_taxonomies' );

/**
 * Add custom columns to post type admin lists
 */
function trendtoday_add_custom_columns( $columns ) {
    // Add custom columns for posts
    if ( ! isset( $_GET['post_type'] ) || $_GET['post_type'] === 'post' ) {
        $columns['breaking_news'] = __( 'Breaking', 'trendtoday' );
        $columns['reading_time']  = __( 'Reading Time', 'trendtoday' );
        $columns['post_views']    = __( 'Views', 'trendtoday' );
    }

    // Add custom columns for video_news
    if ( isset( $_GET['post_type'] ) && $_GET['post_type'] === 'video_news' ) {
        $columns['video_url'] = __( 'Video URL', 'trendtoday' );
        $columns['duration']  = __( 'Duration', 'trendtoday' );
    }

    // Add custom columns for gallery
    if ( isset( $_GET['post_type'] ) && $_GET['post_type'] === 'gallery' ) {
        $columns['image_count'] = __( 'Images', 'trendtoday' );
    }

    return $columns;
}
add_filter( 'manage_posts_columns', 'trendtoday_add_custom_columns' );
add_filter( 'manage_pages_columns', 'trendtoday_add_custom_columns' );

/**
 * Display custom column content
 */
function trendtoday_custom_column_content( $column, $post_id ) {
    switch ( $column ) {
        case 'breaking_news':
            $breaking = get_post_meta( $post_id, 'breaking_news', true );
            echo $breaking === '1' ? '<span style="color: #dc2626;">●</span>' : '—';
            break;

        case 'reading_time':
            $reading_time = get_post_meta( $post_id, 'reading_time', true );
            echo $reading_time ? esc_html( $reading_time ) . ' min' : '—';
            break;

        case 'post_views':
            $views = get_post_meta( $post_id, 'post_views', true );
            echo $views ? number_format_i18n( $views ) : '0';
            break;

        case 'video_url':
            $video_url = get_post_meta( $post_id, 'video_url', true );
            echo $video_url ? esc_html( $video_url ) : '—';
            break;

        case 'duration':
            $duration = get_post_meta( $post_id, 'video_duration', true );
            echo $duration ? esc_html( $duration ) : '—';
            break;

        case 'image_count':
            $gallery_images = get_post_meta( $post_id, 'gallery_images', true );
            $count = is_array( $gallery_images ) ? count( $gallery_images ) : 0;
            echo $count > 0 ? esc_html( $count ) : '—';
            break;
    }
}
add_action( 'manage_posts_custom_column', 'trendtoday_custom_column_content', 10, 2 );
add_action( 'manage_pages_custom_column', 'trendtoday_custom_column_content', 10, 2 );

/**
 * Make custom columns sortable
 */
function trendtoday_make_columns_sortable( $columns ) {
    if ( isset( $_GET['post_type'] ) && $_GET['post_type'] === 'video_news' ) {
        $columns['duration'] = 'duration';
    }
    if ( isset( $_GET['post_type'] ) && $_GET['post_type'] === 'gallery' ) {
        $columns['image_count'] = 'image_count';
    }
    return $columns;
}
add_filter( 'manage_edit-video_news_sortable_columns', 'trendtoday_make_columns_sortable' );
add_filter( 'manage_edit-gallery_sortable_columns', 'trendtoday_make_columns_sortable' );
