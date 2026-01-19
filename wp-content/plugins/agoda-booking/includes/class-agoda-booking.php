<?php
/**
 * The core plugin class.
 *
 * @package Agoda_Booking
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The core plugin class.
 */
class Agoda_Booking {

	/**
	 * The loader that's responsible for maintaining and registering all hooks.
	 *
	 * @var Agoda_Booking_Loader
	 */
	protected $loader;

	/**
	 * Define the core functionality of the plugin.
	 */
	public function __construct() {
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-logger.php';
		require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-security.php';
		require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-api.php';
		require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-admin.php';
		require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-frontend.php';
		require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-validator.php';
		require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-cache.php';
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 */
	private function set_locale() {
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
	}

	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'agoda-booking',
			false,
			dirname( AGODA_BOOKING_PLUGIN_BASENAME ) . '/languages/'
		);
	}

	/**
	 * Register all of the hooks related to the admin area functionality.
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Agoda_Admin();

		add_action( 'admin_menu', array( $plugin_admin, 'add_settings_page' ) );
		add_action( 'admin_init', array( $plugin_admin, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_scripts' ) );
		add_action( 'wp_ajax_agoda_test_connection', array( $plugin_admin, 'ajax_test_connection' ) );
		add_action( 'wp_ajax_agoda_clear_cache', array( $plugin_admin, 'ajax_clear_cache' ) );
		add_action( 'wp_ajax_agoda_get_dashboard_stats', array( $plugin_admin, 'ajax_get_dashboard_stats' ) );
		add_action( 'wp_ajax_agoda_admin_hotel_search', array( $plugin_admin, 'ajax_admin_hotel_search' ) );
		add_action( 'wp_ajax_agoda_clear_cache_by_pattern', array( $plugin_admin, 'ajax_clear_cache_by_pattern' ) );
		add_action( 'wp_ajax_agoda_clear_expired_cache', array( $plugin_admin, 'ajax_clear_expired_cache' ) );
		add_action( 'wp_ajax_agoda_get_cache_entry', array( $plugin_admin, 'ajax_get_cache_entry' ) );
		add_action( 'wp_ajax_agoda_delete_cache_entry', array( $plugin_admin, 'ajax_delete_cache_entry' ) );
		add_action( 'wp_ajax_agoda_get_logs', array( $plugin_admin, 'ajax_get_logs' ) );
		add_action( 'wp_ajax_agoda_save_log_settings', array( $plugin_admin, 'ajax_save_log_settings' ) );
		add_action( 'wp_ajax_agoda_clear_old_logs', array( $plugin_admin, 'ajax_clear_old_logs' ) );
		add_action( 'wp_ajax_agoda_export_logs', array( $plugin_admin, 'ajax_export_logs' ) );
		add_action( 'wp_ajax_agoda_get_statistics', array( $plugin_admin, 'ajax_get_statistics' ) );
		add_action( 'wp_ajax_agoda_clear_statistics', array( $plugin_admin, 'ajax_clear_statistics' ) );
		add_action( 'wp_ajax_agoda_export_statistics', array( $plugin_admin, 'ajax_export_statistics' ) );
		add_action( 'wp_ajax_agoda_bulk_hotel_search', array( $plugin_admin, 'ajax_bulk_hotel_search' ) );
		add_action( 'wp_ajax_agoda_bulk_cache_clear', array( $plugin_admin, 'ajax_bulk_cache_clear' ) );
		add_action( 'wp_ajax_agoda_get_cities', array( $plugin_admin, 'ajax_get_cities' ) );
		add_action( 'wp_ajax_agoda_get_city_details', array( $plugin_admin, 'ajax_get_city_details' ) );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality.
	 */
	private function define_public_hooks() {
		$plugin_public = new Agoda_Frontend();

		add_action( 'wp_enqueue_scripts', array( $plugin_public, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $plugin_public, 'enqueue_scripts' ) );
		add_action( 'wp_ajax_agoda_search', array( $plugin_public, 'ajax_search' ) );
		add_action( 'wp_ajax_nopriv_agoda_search', array( $plugin_public, 'ajax_search' ) );
		add_shortcode( 'agoda_search', array( $plugin_public, 'render_search_form' ) );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 */
	public function run() {
		// Plugin is ready
	}
}
