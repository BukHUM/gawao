<?php
/**
 * Register Custom Widgets
 *
 * @package TrendToday
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register custom widgets
 */
function trendtoday_register_widgets() {
    register_widget( 'TrendToday_Popular_Posts_Widget' );
    register_widget( 'TrendToday_Recent_Posts_Widget' );
    register_widget( 'TrendToday_Newsletter_Widget' );
    register_widget( 'TrendToday_Trending_Tags_Widget' );
}
add_action( 'widgets_init', 'trendtoday_register_widgets' );

/**
 * Handle newsletter subscription
 */
function trendtoday_handle_newsletter_subscription() {
    if ( ! isset( $_POST['newsletter_nonce'] ) || ! wp_verify_nonce( $_POST['newsletter_nonce'], 'trendtoday_newsletter' ) ) {
        wp_die( __( 'Security check failed', 'trendtoday' ) );
    }

    $email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';

    if ( ! is_email( $email ) ) {
        wp_redirect( add_query_arg( 'newsletter', 'invalid', wp_get_referer() ) );
        exit;
    }

    // Store email (you can integrate with Mailchimp, etc. here)
    $subscribers = get_option( 'trendtoday_newsletter_subscribers', array() );
    if ( ! in_array( $email, $subscribers, true ) ) {
        $subscribers[] = $email;
        update_option( 'trendtoday_newsletter_subscribers', $subscribers );
    }

    // Redirect with success message
    wp_redirect( add_query_arg( 'newsletter', 'success', wp_get_referer() ) );
    exit;
}
add_action( 'admin_post_trendtoday_newsletter_subscribe', 'trendtoday_handle_newsletter_subscription' );
add_action( 'admin_post_nopriv_trendtoday_newsletter_subscribe', 'trendtoday_handle_newsletter_subscription' );
