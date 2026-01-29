<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package Agoda_Booking
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete plugin options
delete_option( 'agoda_site_id' );
delete_option( 'agoda_api_key' );
delete_option( 'agoda_cid' );
delete_option( 'agoda_default_language' );
delete_option( 'agoda_default_currency' );
delete_option( 'agoda_api_endpoint' );
delete_option( 'agoda_cache_duration' );

// Delete all transients
global $wpdb;
$wpdb->query(
	$wpdb->prepare(
		"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
		$wpdb->esc_like( '_transient_agoda_' ) . '%'
	)
);
$wpdb->query(
	$wpdb->prepare(
		"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
		$wpdb->esc_like( '_transient_timeout_agoda_' ) . '%'
	)
);
