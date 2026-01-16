<?php
/**
 * AJAX Handlers
 *
 * @package TrendToday
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Load more posts via AJAX
 */
function trendtoday_load_more_posts() {
    check_ajax_referer( 'trendtoday-nonce', 'nonce' );

    $page = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
    $posts_per_page = isset( $_POST['posts_per_page'] ) ? absint( $_POST['posts_per_page'] ) : get_option( 'posts_per_page' );
    $category = isset( $_POST['category'] ) ? sanitize_text_field( $_POST['category'] ) : '';

    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $posts_per_page,
        'paged'          => $page,
        'post_status'    => 'publish',
    );

    if ( ! empty( $category ) && $category !== 'all' ) {
        $args['cat'] = absint( $category );
    }

    $query = new WP_Query( $args );

    ob_start();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            get_template_part( 'template-parts/news-card' );
        }
        wp_reset_postdata();
    }

    $html = ob_get_clean();

    wp_send_json_success( array(
        'html' => $html,
        'has_more' => $query->max_num_pages > $page,
        'next_page' => $page + 1,
    ) );
}
add_action( 'wp_ajax_load_more_posts', 'trendtoday_load_more_posts' );
add_action( 'wp_ajax_nopriv_load_more_posts', 'trendtoday_load_more_posts' );

/**
 * Filter posts by category via AJAX
 */
function trendtoday_filter_posts() {
    check_ajax_referer( 'trendtoday-nonce', 'nonce' );

    $category = isset( $_POST['category'] ) ? sanitize_text_field( $_POST['category'] ) : 'all';
    $posts_per_page = isset( $_POST['posts_per_page'] ) ? absint( $_POST['posts_per_page'] ) : get_option( 'posts_per_page' );

    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $posts_per_page,
        'post_status'    => 'publish',
    );

    if ( ! empty( $category ) && $category !== 'all' ) {
        $args['cat'] = absint( $category );
    }

    $query = new WP_Query( $args );

    ob_start();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            get_template_part( 'template-parts/news-card' );
        }
        wp_reset_postdata();
    } else {
        get_template_part( 'template-parts/content', 'none' );
    }

    $html = ob_get_clean();

    wp_send_json_success( array(
        'html' => $html,
        'found_posts' => $query->found_posts,
        'max_pages' => $query->max_num_pages,
    ) );
}
add_action( 'wp_ajax_filter_posts', 'trendtoday_filter_posts' );
add_action( 'wp_ajax_nopriv_filter_posts', 'trendtoday_filter_posts' );

/**
 * Search suggestions via AJAX
 */
function trendtoday_search_suggestions() {
    check_ajax_referer( 'trendtoday-nonce', 'nonce' );

    $search_term = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';

    if ( strlen( $search_term ) < 2 ) {
        wp_send_json_success( array( 'suggestions' => array() ) );
    }

    $args = array(
        'post_type'      => array( 'post', 'video_news', 'gallery' ),
        'posts_per_page' => 5,
        's'              => $search_term,
        'post_status'    => 'publish',
    );

    $query = new WP_Query( $args );

    $suggestions = array();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $suggestions[] = array(
                'title' => get_the_title(),
                'url'   => get_permalink(),
                'type'  => get_post_type(),
            );
        }
        wp_reset_postdata();
    }

    wp_send_json_success( array( 'suggestions' => $suggestions ) );
}
add_action( 'wp_ajax_search_suggestions', 'trendtoday_search_suggestions' );
add_action( 'wp_ajax_nopriv_search_suggestions', 'trendtoday_search_suggestions' );

/**
 * Increment post views via AJAX
 */
function trendtoday_increment_views() {
    check_ajax_referer( 'trendtoday-nonce', 'nonce' );

    $post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;

    if ( ! $post_id ) {
        wp_send_json_error( array( 'message' => __( 'Invalid post ID', 'trendtoday' ) ) );
    }

    trendtoday_increment_post_views( $post_id );
    $views = trendtoday_get_post_views( $post_id );

    wp_send_json_success( array( 'views' => $views ) );
}
add_action( 'wp_ajax_increment_views', 'trendtoday_increment_views' );
add_action( 'wp_ajax_nopriv_increment_views', 'trendtoday_increment_views' );
