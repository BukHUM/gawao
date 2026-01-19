<?php
/**
 * Plugin Name: Agoda Booking
 * Plugin URI: https://konderntang.com
 * Description: Search and book hotels through Agoda Affiliate API. Integrate Agoda hotel search functionality into your WordPress site.
 * Version: 1.0.0
 * Author: ไพฑูรย์ ไพเราะ
 * Author URI: https://konderntang.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: agoda-booking
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 *
 * @package Agoda_Booking
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Check minimum requirements.
 */
function agoda_booking_check_requirements() {
	global $wp_version;
	
	$min_wp_version = '5.8';
	$min_php_version = '7.4';
	
	// Check WordPress version
	if ( version_compare( $wp_version, $min_wp_version, '<' ) ) {
		add_action( 'admin_notices', 'agoda_booking_wordpress_version_notice' );
		return false;
	}
	
	// Check PHP version
	if ( version_compare( PHP_VERSION, $min_php_version, '<' ) ) {
		add_action( 'admin_notices', 'agoda_booking_php_version_notice' );
		return false;
	}
	
	return true;
}

/**
 * Display WordPress version notice.
 */
function agoda_booking_wordpress_version_notice() {
	?>
	<div class="notice notice-error">
		<p>
			<?php
			printf(
				/* translators: 1: Plugin name, 2: Required WordPress version */
				esc_html__( '%1$s requires WordPress version %2$s or higher. Please update WordPress.', 'agoda-booking' ),
				'<strong>Agoda Booking</strong>',
				'5.8'
			);
			?>
		</p>
	</div>
	<?php
}

/**
 * Display PHP version notice.
 */
function agoda_booking_php_version_notice() {
	?>
	<div class="notice notice-error">
		<p>
			<?php
			printf(
				/* translators: 1: Plugin name, 2: Required PHP version, 3: Current PHP version */
				esc_html__( '%1$s requires PHP version %2$s or higher. You are running PHP %3$s. Please update PHP.', 'agoda-booking' ),
				'<strong>Agoda Booking</strong>',
				'7.4',
				PHP_VERSION
			);
			?>
		</p>
	</div>
	<?php
}

// Check requirements before proceeding
if ( ! agoda_booking_check_requirements() ) {
	return;
}

/**
 * Currently plugin version.
 * 
 * @var string
 */
define( 'AGODA_BOOKING_VERSION', '1.0.0' );

/**
 * Plugin directory path.
 * 
 * @var string
 */
define( 'AGODA_BOOKING_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Plugin directory URL.
 * 
 * @var string
 */
define( 'AGODA_BOOKING_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Plugin basename.
 * 
 * @var string
 */
define( 'AGODA_BOOKING_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * 
 * Sets default options and performs activation tasks.
 * 
 * @return void
 */
function agoda_booking_activate() {
	// Check requirements before activation
	if ( ! agoda_booking_check_requirements() ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die(
			esc_html__( 'Agoda Booking plugin could not be activated. Please check the requirements.', 'agoda-booking' ),
			esc_html__( 'Plugin Activation Error', 'agoda-booking' ),
			array( 'back_link' => true )
		);
	}
	
	// Set default options (only if they don't exist)
	if ( ! get_option( 'agoda_default_language' ) ) {
		add_option( 'agoda_default_language', 'en-us' );
	}
	
	if ( ! get_option( 'agoda_default_currency' ) ) {
		add_option( 'agoda_default_currency', 'USD' );
	}
	
	if ( ! get_option( 'agoda_api_endpoint' ) ) {
		add_option( 'agoda_api_endpoint', 'http://affiliateapi7643.agoda.com/affiliateservice/lt_v1' );
	}
	
	if ( ! get_option( 'agoda_cache_duration' ) ) {
		add_option( 'agoda_cache_duration', 3600 ); // 1 hour in seconds
	}
	
	// CID is optional, no default value needed
	
	// Flush rewrite rules if needed (for future features)
	flush_rewrite_rules();
	
	// Set activation flag
	add_option( 'agoda_booking_activated', time() );
}

/**
 * The code that runs during plugin deactivation.
 * 
 * Cleans up transients and performs deactivation tasks.
 * 
 * @return void
 */
function agoda_booking_deactivate() {
	// Clear all Agoda transients
	global $wpdb;
	
	// Delete transients
	$wpdb->query(
		$wpdb->prepare(
			"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
			$wpdb->esc_like( '_transient_agoda_' ) . '%'
		)
	);
	
	// Delete transient timeouts
	$wpdb->query(
		$wpdb->prepare(
			"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
			$wpdb->esc_like( '_transient_timeout_agoda_' ) . '%'
		)
	);
	
	// Flush rewrite rules
	flush_rewrite_rules();
	
	// Remove activation flag
	delete_option( 'agoda_booking_activated' );
}

register_activation_hook( __FILE__, 'agoda_booking_activate' );
register_deactivation_hook( __FILE__, 'agoda_booking_deactivate' );

/**
 * Load the core plugin class.
 * 
 * This class orchestrates all plugin functionality.
 */
if ( ! class_exists( 'Agoda_Booking' ) ) {
	require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-booking.php';
}

/**
 * Begins execution of the plugin.
 * 
 * Creates an instance of the main plugin class and runs it.
 * 
 * @return void
 */
function agoda_booking_run() {
	// Check if class exists before instantiating
	if ( ! class_exists( 'Agoda_Booking' ) ) {
		add_action( 'admin_notices', 'agoda_booking_class_not_found_notice' );
		return;
	}
	
	$plugin = new Agoda_Booking();
	$plugin->run();
}

/**
 * Display notice if core class is not found.
 */
function agoda_booking_class_not_found_notice() {
	?>
	<div class="notice notice-error">
		<p>
			<?php
			esc_html_e( 'Agoda Booking plugin core class not found. Please reinstall the plugin.', 'agoda-booking' );
			?>
		</p>
	</div>
	<?php
}

// Initialize the plugin
agoda_booking_run();
