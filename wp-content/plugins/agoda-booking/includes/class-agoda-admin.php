<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package Agoda_Booking
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The admin-specific functionality of the plugin.
 */
class Agoda_Admin {

	/**
	 * Add settings page to WordPress admin menu.
	 */
	public function add_settings_page() {
		// Check user capability
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Add as top-level menu instead of under Settings
		add_menu_page(
			__( 'Agoda Booking Dashboard', 'agoda-booking' ),
			__( 'Agoda Booking', 'agoda-booking' ),
			'manage_options',
			'agoda-booking',
			array( $this, 'render_dashboard_page' ),
			'dashicons-calendar-alt',
			30
		);

		// Add Dashboard submenu (same as main menu, but with different title)
		add_submenu_page(
			'agoda-booking',
			__( 'Dashboard', 'agoda-booking' ),
			__( 'Dashboard', 'agoda-booking' ),
			'manage_options',
			'agoda-booking',
			array( $this, 'render_dashboard_page' )
		);

		// Add Settings submenu
		add_submenu_page(
			'agoda-booking',
			__( 'Settings', 'agoda-booking' ),
			__( 'Settings', 'agoda-booking' ),
			'manage_options',
			'agoda-booking-settings',
			array( $this, 'render_settings_page' )
		);

		// Add Hotel Search Preview submenu
		add_submenu_page(
			'agoda-booking',
			__( 'Hotel Search Preview', 'agoda-booking' ),
			__( 'Hotel Search', 'agoda-booking' ),
			'manage_options',
			'agoda-booking-hotel-search',
			array( $this, 'render_hotel_search_page' )
		);

		// Add Cache Management submenu
		add_submenu_page(
			'agoda-booking',
			__( 'Cache Management', 'agoda-booking' ),
			__( 'Cache', 'agoda-booking' ),
			'manage_options',
			'agoda-booking-cache',
			array( $this, 'render_cache_page' )
		);

		// Add API Logs submenu
		add_submenu_page(
			'agoda-booking',
			__( 'API Logs', 'agoda-booking' ),
			__( 'API Logs', 'agoda-booking' ),
			'manage_options',
			'agoda-booking-logs',
			array( $this, 'render_logs_page' )
		);

		// Add Statistics submenu
		add_submenu_page(
			'agoda-booking',
			__( 'Statistics & Analytics', 'agoda-booking' ),
			__( 'Statistics', 'agoda-booking' ),
			'manage_options',
			'agoda-booking-statistics',
			array( $this, 'render_statistics_page' )
		);

		// Add Bulk Operations submenu
		add_submenu_page(
			'agoda-booking',
			__( 'Bulk Operations', 'agoda-booking' ),
			__( 'Bulk Operations', 'agoda-booking' ),
			'manage_options',
			'agoda-booking-bulk-operations',
			array( $this, 'render_bulk_operations_page' )
		);

		// Add City Management submenu
		add_submenu_page(
			'agoda-booking',
			__( 'City Management', 'agoda-booking' ),
			__( 'City Management', 'agoda-booking' ),
			'manage_options',
			'agoda-booking-city-management',
			array( $this, 'render_city_management_page' )
		);
	}

	/**
	 * Register plugin settings.
	 */
	public function register_settings() {
		register_setting( 'agoda_booking_settings', 'agoda_site_id', array( 
			'sanitize_callback' => array( $this, 'sanitize_site_id' ),
			'validate_callback' => array( $this, 'validate_site_id' ),
		) );
		register_setting( 'agoda_booking_settings', 'agoda_api_key', array( 
			'sanitize_callback' => array( $this, 'sanitize_api_key' ),
			'validate_callback' => array( $this, 'validate_api_key' ),
		) );
		register_setting( 'agoda_booking_settings', 'agoda_cid', array( 'sanitize_callback' => 'sanitize_text_field' ) );
		register_setting( 'agoda_booking_settings', 'agoda_default_language', array( 'sanitize_callback' => 'sanitize_text_field' ) );
		register_setting( 'agoda_booking_settings', 'agoda_default_currency', array( 'sanitize_callback' => 'sanitize_text_field' ) );
		register_setting( 'agoda_booking_settings', 'agoda_cache_duration', array( 'sanitize_callback' => 'absint' ) );
		
		// Rate limiting settings
		register_setting( 'agoda_booking_settings', 'agoda_rate_limit_enabled', array( 'sanitize_callback' => 'rest_sanitize_boolean' ) );
		register_setting( 'agoda_booking_settings', 'agoda_rate_limit_max', array( 'sanitize_callback' => 'absint' ) );
		register_setting( 'agoda_booking_settings', 'agoda_rate_limit_window', array( 'sanitize_callback' => 'absint' ) );
		
		// Content API settings
		register_setting( 'agoda_booking_settings', 'agoda_content_api_token', array( 'sanitize_callback' => 'sanitize_text_field' ) );
		register_setting( 'agoda_booking_settings', 'agoda_content_api_site_id', array( 'sanitize_callback' => 'sanitize_text_field' ) );
		register_setting( 'agoda_booking_settings', 'agoda_content_api_base_url', array( 'sanitize_callback' => 'esc_url_raw' ) );

		// Clear cache when settings are updated
		add_action( 'update_option_agoda_site_id', array( $this, 'clear_cache_on_update' ) );
		add_action( 'update_option_agoda_api_key', array( $this, 'clear_cache_on_update' ) );
		add_action( 'update_option_agoda_cid', array( $this, 'clear_cache_on_update' ) );
	}

	/**
	 * Sanitize Site ID.
	 *
	 * @param string $value Site ID value.
	 * @return string Sanitized Site ID.
	 */
	public function sanitize_site_id( $value ) {
		return sanitize_text_field( $value );
	}

	/**
	 * Validate Site ID format.
	 *
	 * @param string $value Site ID value.
	 * @return bool|WP_Error True if valid, WP_Error otherwise.
	 */
	public function validate_site_id( $value ) {
		if ( empty( $value ) ) {
			return new WP_Error( 'agoda_site_id_empty', __( 'Site ID is required.', 'agoda-booking' ) );
		}

		// Site ID should be numeric
		if ( ! is_numeric( $value ) ) {
			return new WP_Error( 'agoda_site_id_invalid', __( 'Site ID must be numeric.', 'agoda-booking' ) );
		}

		return true;
	}

	/**
	 * Sanitize API Key.
	 *
	 * @param string $value API Key value.
	 * @return string Sanitized API Key.
	 */
	public function sanitize_api_key( $value ) {
		return sanitize_text_field( $value );
	}

	/**
	 * Validate API Key format.
	 *
	 * @param string $value API Key value.
	 * @return bool|WP_Error True if valid, WP_Error otherwise.
	 */
	public function validate_api_key( $value ) {
		if ( empty( $value ) ) {
			return new WP_Error( 'agoda_api_key_empty', __( 'API Key is required.', 'agoda-booking' ) );
		}

		// API Key should be a UUID format (basic check)
		// Format: 00000000-0000-0000-0000-000000000000
		if ( ! preg_match( '/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i', $value ) ) {
			// Allow non-UUID format as well (some API keys might be different)
			// Just check minimum length
			if ( strlen( $value ) < 10 ) {
				return new WP_Error( 'agoda_api_key_invalid', __( 'API Key format appears to be invalid.', 'agoda-booking' ) );
			}
		}

		return true;
	}

	/**
	 * Clear cache when settings are updated.
	 */
	public function clear_cache_on_update() {
		if ( ! class_exists( 'Agoda_Cache' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-cache.php';
		}
		$cache = new Agoda_Cache();
		$cache->clear_cache();
	}

	/**
	 * Render dashboard page.
	 */
	public function render_dashboard_page() {
		// Get dashboard statistics
		$stats = $this->get_dashboard_stats();
		
		// Make stats available to template
		$dashboard_stats = $stats;
		
		require_once AGODA_BOOKING_PLUGIN_DIR . 'admin/views/dashboard.php';
	}

	/**
	 * Render settings page.
	 */
	public function render_settings_page() {
		require_once AGODA_BOOKING_PLUGIN_DIR . 'admin/views/settings.php';
	}

	/**
	 * Render hotel search preview page.
	 */
	public function render_hotel_search_page() {
		require_once AGODA_BOOKING_PLUGIN_DIR . 'admin/views/hotel-search.php';
	}

	/**
	 * Handle AJAX hotel search request (admin).
	 */
	public function ajax_admin_hotel_search() {
		// Load security class
		if ( ! class_exists( 'Agoda_Security' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-security.php';
		}

		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! Agoda_Security::verify_ajax_nonce( 'agoda_booking_admin_search', $nonce ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'agoda-booking' ) ) );
			return;
		}

		// Check user capabilities
		if ( ! Agoda_Security::verify_capability( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'agoda-booking' ) ) );
			return;
		}

		// Load required classes
		if ( ! class_exists( 'Agoda_API' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-api.php';
		}
		if ( ! class_exists( 'Agoda_Validator' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-validator.php';
		}

		$api = new Agoda_API();
		$validator = new Agoda_Validator();

		// Get and sanitize search parameters
		$search_type = isset( $_POST['search_type'] ) ? sanitize_text_field( wp_unslash( $_POST['search_type'] ) ) : 'city';
		$check_in = isset( $_POST['check_in'] ) ? sanitize_text_field( wp_unslash( $_POST['check_in'] ) ) : '';
		$check_out = isset( $_POST['check_out'] ) ? sanitize_text_field( wp_unslash( $_POST['check_out'] ) ) : '';
		$adults = isset( $_POST['adults'] ) ? absint( $_POST['adults'] ) : 2;
		$children = isset( $_POST['children'] ) ? absint( $_POST['children'] ) : 0;
		$language = isset( $_POST['language'] ) ? sanitize_text_field( wp_unslash( $_POST['language'] ) ) : get_option( 'agoda_default_language', 'en-us' );
		$currency = isset( $_POST['currency'] ) ? sanitize_text_field( wp_unslash( $_POST['currency'] ) ) : get_option( 'agoda_default_currency', 'USD' );

		// Validate dates
		if ( empty( $check_in ) || empty( $check_out ) ) {
			wp_send_json_error( array( 'message' => __( 'Check-in and check-out dates are required.', 'agoda-booking' ) ) );
			return;
		}

		if ( ! $validator->validate_date( $check_in ) || ! $validator->validate_date( $check_out ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid date format. Please use YYYY-MM-DD format.', 'agoda-booking' ) ) );
			return;
		}

		if ( ! $validator->validate_date_range( $check_in, $check_out ) ) {
			wp_send_json_error( array( 'message' => __( 'Check-out date must be after check-in date.', 'agoda-booking' ) ) );
			return;
		}

		// Prepare search parameters
		$params = array(
			'checkInDate'  => $check_in,
			'checkOutDate' => $check_out,
			'language'     => $language,
			'currency'     => $currency,
			'occupancy'     => array(
				'numberOfAdult' => $adults,
				'numberOfChildren' => $children,
			),
		);

		// Add children ages if provided
		if ( $children > 0 && isset( $_POST['children_ages'] ) && is_array( $_POST['children_ages'] ) ) {
			$children_ages = array_map( 'absint', $_POST['children_ages'] );
			if ( count( $children_ages ) === $children ) {
				$params['occupancy']['childrenAges'] = $children_ages;
			}
		}

		// Add search type specific parameters
		if ( 'city' === $search_type ) {
			$city_id = isset( $_POST['city_id'] ) ? absint( $_POST['city_id'] ) : 0;
			if ( empty( $city_id ) ) {
				wp_send_json_error( array( 'message' => __( 'City ID is required for city search.', 'agoda-booking' ) ) );
				return;
			}
			$params['cityId'] = $city_id;
		} else {
			$hotel_ids_str = isset( $_POST['hotel_ids'] ) ? sanitize_text_field( wp_unslash( $_POST['hotel_ids'] ) ) : '';
			if ( empty( $hotel_ids_str ) ) {
				wp_send_json_error( array( 'message' => __( 'Hotel IDs are required for hotel list search.', 'agoda-booking' ) ) );
				return;
			}
			$hotel_ids = array_map( 'absint', explode( ',', $hotel_ids_str ) );
			$hotel_ids = array_filter( $hotel_ids );
			if ( empty( $hotel_ids ) ) {
				wp_send_json_error( array( 'message' => __( 'Invalid hotel IDs format.', 'agoda-booking' ) ) );
				return;
			}
			$params['hotelId'] = $hotel_ids;
		}

		// Add filters
		if ( isset( $_POST['min_price'] ) && ! empty( $_POST['min_price'] ) ) {
			$params['minPrice'] = floatval( $_POST['min_price'] );
		}
		if ( isset( $_POST['max_price'] ) && ! empty( $_POST['max_price'] ) ) {
			$params['maxPrice'] = floatval( $_POST['max_price'] );
		}
		if ( isset( $_POST['min_rating'] ) && ! empty( $_POST['min_rating'] ) ) {
			$params['minStarRating'] = absint( $_POST['min_rating'] );
		}
		if ( isset( $_POST['min_review'] ) && ! empty( $_POST['min_review'] ) ) {
			$params['minReviewScore'] = floatval( $_POST['min_review'] );
		}
		if ( isset( $_POST['discount_only'] ) && '1' === $_POST['discount_only'] ) {
			$params['discountOnly'] = true;
		}
		if ( isset( $_POST['sort_by'] ) && ! empty( $_POST['sort_by'] ) ) {
			$params['sortBy'] = sanitize_text_field( wp_unslash( $_POST['sort_by'] ) );
		}
		if ( isset( $_POST['max_results'] ) && ! empty( $_POST['max_results'] ) ) {
			$params['maxResult'] = absint( $_POST['max_results'] );
		}

		// Perform search
		if ( 'city' === $search_type ) {
			$result = $api->search_city( $params );
		} else {
			$result = $api->search_hotels( $params );
		}

		// Handle result
		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array(
				'message' => $result->get_error_message(),
				'code'    => $result->get_error_code(),
			) );
			return;
		}

		// Return success with results
		wp_send_json_success( array(
			'count'   => isset( $result['count'] ) ? $result['count'] : 0,
			'results' => isset( $result['results'] ) ? $result['results'] : array(),
		) );
	}

	/**
	 * Render cache management page.
	 */
	public function render_cache_page() {
		require_once AGODA_BOOKING_PLUGIN_DIR . 'admin/views/cache-management.php';
	}

	/**
	 * Render API logs page.
	 */
	public function render_logs_page() {
		require_once AGODA_BOOKING_PLUGIN_DIR . 'admin/views/api-logs.php';
	}

	/**
	 * Render statistics page.
	 */
	public function render_statistics_page() {
		require_once AGODA_BOOKING_PLUGIN_DIR . 'admin/views/statistics.php';
	}

	/**
	 * Render bulk operations page.
	 */
	public function render_bulk_operations_page() {
		require_once AGODA_BOOKING_PLUGIN_DIR . 'admin/views/bulk-operations.php';
	}

	/**
	 * Render city management page.
	 */
	public function render_city_management_page() {
		require_once AGODA_BOOKING_PLUGIN_DIR . 'admin/views/city-management.php';
	}

	/**
	 * Enqueue admin styles.
	 */
	public function enqueue_styles() {
		wp_enqueue_style(
			'agoda-booking-admin',
			AGODA_BOOKING_PLUGIN_URL . 'admin/css/admin.css',
			array(),
			AGODA_BOOKING_VERSION,
			'all'
		);
	}

	/**
	 * Enqueue admin scripts.
	 */
	public function enqueue_scripts( $hook ) {
		// Load on all Agoda Booking admin pages
		$agoda_pages = array(
			'toplevel_page_agoda-booking',
			'agoda-booking_page_agoda-booking-settings',
			'agoda-booking_page_agoda-booking-hotel-search',
			'agoda-booking_page_agoda-booking-cache',
			'agoda-booking_page_agoda-booking-logs',
			'agoda-booking_page_agoda-booking-statistics',
			'agoda-booking_page_agoda-booking-bulk-operations',
			'agoda-booking_page_agoda-booking-city-management',
		);

		if ( ! in_array( $hook, $agoda_pages, true ) ) {
			return;
		}

		wp_enqueue_script(
			'agoda-booking-admin',
			AGODA_BOOKING_PLUGIN_URL . 'admin/js/admin.js',
			array( 'jquery' ),
			AGODA_BOOKING_VERSION,
			true
		);

		// Load hotel search script only on hotel search page
		if ( 'agoda-booking_page_agoda-booking-hotel-search' === $hook ) {
			wp_enqueue_script(
				'agoda-booking-hotel-search',
				AGODA_BOOKING_PLUGIN_URL . 'admin/js/hotel-search.js',
				array( 'jquery', 'agoda-booking-admin' ),
				AGODA_BOOKING_VERSION,
				true
			);
		}

		// Load cache management script only on cache management page
		if ( 'agoda-booking_page_agoda-booking-cache' === $hook ) {
			wp_enqueue_script(
				'agoda-booking-cache-management',
				AGODA_BOOKING_PLUGIN_URL . 'admin/js/cache-management.js',
				array( 'jquery', 'agoda-booking-admin' ),
				AGODA_BOOKING_VERSION,
				true
			);
		}

		// Load API logs script only on API logs page
		if ( 'agoda-booking_page_agoda-booking-logs' === $hook ) {
			wp_enqueue_script(
				'agoda-booking-api-logs',
				AGODA_BOOKING_PLUGIN_URL . 'admin/js/api-logs.js',
				array( 'jquery', 'agoda-booking-admin' ),
				AGODA_BOOKING_VERSION,
				true
			);
		}

		// Load statistics script only on statistics page
		if ( 'agoda-booking_page_agoda-booking-statistics' === $hook ) {
			wp_enqueue_script(
				'agoda-booking-statistics',
				AGODA_BOOKING_PLUGIN_URL . 'admin/js/statistics.js',
				array( 'jquery', 'agoda-booking-admin' ),
				AGODA_BOOKING_VERSION,
				true
			);
		}

		// Load bulk operations script only on bulk operations page
		if ( 'agoda-booking_page_agoda-booking-bulk-operations' === $hook ) {
			wp_enqueue_script(
				'agoda-booking-bulk-operations',
				AGODA_BOOKING_PLUGIN_URL . 'admin/js/bulk-operations.js',
				array( 'jquery', 'agoda-booking-admin' ),
				AGODA_BOOKING_VERSION,
				true
			);
		}

		// Load city management script only on city management page
		if ( 'agoda-booking_page_agoda-booking-city-management' === $hook ) {
			wp_enqueue_script(
				'agoda-booking-city-management',
				AGODA_BOOKING_PLUGIN_URL . 'admin/js/city-management.js',
				array( 'jquery', 'agoda-booking-admin' ),
				AGODA_BOOKING_VERSION,
				true
			);
		}

		// Localize script for AJAX
		wp_localize_script(
			'agoda-booking-admin',
			'agodaBookingAdmin',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'agoda_booking_test_connection' ),
				'nonceClearCache' => wp_create_nonce( 'agoda_booking_clear_cache' ),
				'nonceDashboard' => wp_create_nonce( 'agoda_booking_dashboard' ),
				'nonceAdminSearch' => wp_create_nonce( 'agoda_booking_admin_search' ),
				'nonceCacheManagement' => wp_create_nonce( 'agoda_booking_cache_management' ),
				'nonceLogs' => wp_create_nonce( 'agoda_booking_logs' ),
				'nonceStatistics' => wp_create_nonce( 'agoda_booking_statistics' ),
				'nonceBulkOperations' => wp_create_nonce( 'agoda_booking_bulk_operations' ),
				'nonceCityManagement' => wp_create_nonce( 'agoda_booking_city_management' ),
				'strings' => array(
					'testing'     => __( 'Testing connection...', 'agoda-booking' ),
					'success'     => __( 'Connection successful!', 'agoda-booking' ),
					'error'       => __( 'Connection failed.', 'agoda-booking' ),
					'invalidCred' => __( 'Invalid credentials. Please check your Site ID and API Key.', 'agoda-booking' ),
					'clearingCache' => __( 'Clearing cache...', 'agoda-booking' ),
					'cacheCleared' => __( 'Cache cleared successfully.', 'agoda-booking' ),
				),
			)
		);
	}

	/**
	 * Handle AJAX test connection request.
	 */
	public function ajax_test_connection() {
		// Load security class
		if ( ! class_exists( 'Agoda_Security' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-security.php';
		}

		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! Agoda_Security::verify_ajax_nonce( 'agoda_booking_test_connection', $nonce ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'agoda-booking' ) ) );
			return;
		}

		// Check user capabilities
		if ( ! Agoda_Security::verify_capability( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'agoda-booking' ) ) );
			return;
		}

		// Get Site ID and API Key from POST or options
		$site_id = isset( $_POST['site_id'] ) ? sanitize_text_field( wp_unslash( $_POST['site_id'] ) ) : get_option( 'agoda_site_id', '' );
		$api_key = isset( $_POST['api_key'] ) ? sanitize_text_field( wp_unslash( $_POST['api_key'] ) ) : get_option( 'agoda_api_key', '' );

		// Validate credentials
		if ( empty( $site_id ) || empty( $api_key ) ) {
			wp_send_json_error( array( 'message' => __( 'Site ID and API Key are required.', 'agoda-booking' ) ) );
			return;
		}

		// Ensure API class is loaded
		if ( ! class_exists( 'Agoda_API' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-api.php';
		}
		
		// Temporarily set credentials for testing
		$original_site_id = get_option( 'agoda_site_id', '' );
		$original_api_key = get_option( 'agoda_api_key', '' );
		
		// Update options temporarily for test
		update_option( 'agoda_site_id', $site_id );
		update_option( 'agoda_api_key', $api_key );
		
		// Create new instance with updated credentials
		$test_api = new Agoda_API();
		
		// Test with a simple search
		$test_params = array(
			'cityId'      => 9395, // Bangkok
			'checkInDate' => date( 'Y-m-d', strtotime( '+7 days' ) ),
			'checkOutDate' => date( 'Y-m-d', strtotime( '+8 days' ) ),
		);
		
		$result = $test_api->search_city( $test_params );
		
		// Restore original credentials
		update_option( 'agoda_site_id', $original_site_id );
		update_option( 'agoda_api_key', $original_api_key );
		
		// Check result
		if ( is_wp_error( $result ) ) {
			$error_code = $result->get_error_code();
			$error_message = $result->get_error_message();
			
			// Provide user-friendly messages
			if ( 'agoda_unauthorized' === $error_code || 'agoda_invalid_credentials' === $error_code ) {
				wp_send_json_error( array( 
					'message' => __( 'Invalid credentials. Please check your Site ID and API Key.', 'agoda-booking' ),
					'code'    => $error_code,
				) );
			} else {
				wp_send_json_error( array( 
					'message' => sprintf( __( 'Connection test failed: %s', 'agoda-booking' ), $error_message ),
					'code'    => $error_code,
				) );
			}
		} else {
			wp_send_json_success( array( 
				'message' => __( 'Connection successful! Your API credentials are valid.', 'agoda-booking' ),
				'count'   => isset( $result['count'] ) ? $result['count'] : 0,
			) );
		}
	}

	/**
	 * Get dashboard statistics.
	 *
	 * @return array Dashboard statistics.
	 */
	public function get_dashboard_stats() {
		// Check API connection status
		$site_id = get_option( 'agoda_site_id', '' );
		$api_key = get_option( 'agoda_api_key', '' );
		$api_connected = ! empty( $site_id ) && ! empty( $api_key );

		// Get cache statistics
		if ( ! class_exists( 'Agoda_Cache' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-cache.php';
		}
		$cache = new Agoda_Cache();
		$cache_stats = $cache->get_cache_stats();

		// Get rate limit info
		$rate_limit_enabled = get_option( 'agoda_rate_limit_enabled', false );
		$rate_limit_max = get_option( 'agoda_rate_limit_max', 100 );
		$rate_limit_window = get_option( 'agoda_rate_limit_window', 3600 );

		// Get API call count (today) - this would need to be tracked
		// For now, we'll use a simple option to track
		$api_calls_today = get_option( 'agoda_api_calls_today', 0 );
		$api_calls_date = get_option( 'agoda_api_calls_date', '' );
		
		// Reset counter if it's a new day
		if ( $api_calls_date !== current_time( 'Y-m-d' ) ) {
			$api_calls_today = 0;
			update_option( 'agoda_api_calls_date', current_time( 'Y-m-d' ) );
			update_option( 'agoda_api_calls_today', 0 );
		}

		return array(
			'api_connected'      => $api_connected,
			'api_calls_today'   => $api_calls_today,
			'rate_limit_enabled' => $rate_limit_enabled,
			'rate_limit_max'    => $rate_limit_max,
			'rate_limit_window' => $rate_limit_window,
			'cache_enabled'     => $cache_stats['cache_enabled'],
			'cache_count'       => $cache_stats['cache_count'],
			'cache_duration'    => $cache_stats['cache_duration'],
		);
	}

	/**
	 * Handle AJAX clear cache request.
	 */
	public function ajax_clear_cache() {
		// Load security class
		if ( ! class_exists( 'Agoda_Security' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-security.php';
		}

		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! Agoda_Security::verify_ajax_nonce( 'agoda_booking_clear_cache', $nonce ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'agoda-booking' ) ) );
			return;
		}

		// Check user capabilities
		if ( ! Agoda_Security::verify_capability( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'agoda-booking' ) ) );
			return;
		}

		// Clear cache
		if ( ! class_exists( 'Agoda_Cache' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-cache.php';
		}
		$cache = new Agoda_Cache();
		$result = $cache->clear_cache();

		if ( $result ) {
			wp_send_json_success( array( 'message' => __( 'Cache cleared successfully.', 'agoda-booking' ) ) );
		} else {
			wp_send_json_error( array( 'message' => __( 'Failed to clear cache.', 'agoda-booking' ) ) );
		}
	}

	/**
	 * Handle AJAX get dashboard stats request.
	 */
	public function ajax_get_dashboard_stats() {
		// Load security class
		if ( ! class_exists( 'Agoda_Security' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-security.php';
		}

		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! Agoda_Security::verify_ajax_nonce( 'agoda_booking_dashboard', $nonce ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'agoda-booking' ) ) );
			return;
		}

		// Check user capabilities
		if ( ! Agoda_Security::verify_capability( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'agoda-booking' ) ) );
			return;
		}

		$stats = $this->get_dashboard_stats();
		wp_send_json_success( $stats );
	}

	/**
	 * Handle AJAX clear cache by pattern request.
	 */
	public function ajax_clear_cache_by_pattern() {
		// Load security class
		if ( ! class_exists( 'Agoda_Security' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-security.php';
		}

		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! Agoda_Security::verify_ajax_nonce( 'agoda_booking_cache_management', $nonce ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'agoda-booking' ) ) );
			return;
		}

		// Check user capabilities
		if ( ! Agoda_Security::verify_capability( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'agoda-booking' ) ) );
			return;
		}

		$pattern = isset( $_POST['pattern'] ) ? sanitize_text_field( wp_unslash( $_POST['pattern'] ) ) : '';

		if ( ! class_exists( 'Agoda_Cache' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-cache.php';
		}
		$cache = new Agoda_Cache();
		$count = $cache->clear_cache_by_pattern( $pattern );

		wp_send_json_success( array(
			'message' => sprintf( __( 'Cleared %d cache entry(ies).', 'agoda-booking' ), $count ),
			'count'   => $count,
		) );
	}

	/**
	 * Handle AJAX clear expired cache request.
	 */
	public function ajax_clear_expired_cache() {
		// Load security class
		if ( ! class_exists( 'Agoda_Security' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-security.php';
		}

		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! Agoda_Security::verify_ajax_nonce( 'agoda_booking_cache_management', $nonce ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'agoda-booking' ) ) );
			return;
		}

		// Check user capabilities
		if ( ! Agoda_Security::verify_capability( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'agoda-booking' ) ) );
			return;
		}

		if ( ! class_exists( 'Agoda_Cache' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-cache.php';
		}
		$cache = new Agoda_Cache();
		$count = $cache->clear_expired_cache();

		wp_send_json_success( array(
			'message' => sprintf( __( 'Cleared %d expired cache entry(ies).', 'agoda-booking' ), $count ),
			'count'   => $count,
		) );
	}

	/**
	 * Handle AJAX get cache entry request.
	 */
	public function ajax_get_cache_entry() {
		// Load security class
		if ( ! class_exists( 'Agoda_Security' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-security.php';
		}

		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! Agoda_Security::verify_ajax_nonce( 'agoda_booking_cache_management', $nonce ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'agoda-booking' ) ) );
			return;
		}

		// Check user capabilities
		if ( ! Agoda_Security::verify_capability( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'agoda-booking' ) ) );
			return;
		}

		$key = isset( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : '';

		if ( empty( $key ) ) {
			wp_send_json_error( array( 'message' => __( 'Cache key is required.', 'agoda-booking' ) ) );
			return;
		}

		if ( ! class_exists( 'Agoda_Cache' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-cache.php';
		}
		$cache = new Agoda_Cache();
		$entry = $cache->get_cache_entry( $key );

		if ( false === $entry ) {
			wp_send_json_error( array( 'message' => __( 'Cache entry not found.', 'agoda-booking' ) ) );
			return;
		}

		wp_send_json_success( array(
			'key'   => $key,
			'value' => $entry,
		) );
	}

	/**
	 * Handle AJAX delete cache entry request.
	 */
	public function ajax_delete_cache_entry() {
		// Load security class
		if ( ! class_exists( 'Agoda_Security' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-security.php';
		}

		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! Agoda_Security::verify_ajax_nonce( 'agoda_booking_cache_management', $nonce ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'agoda-booking' ) ) );
			return;
		}

		// Check user capabilities
		if ( ! Agoda_Security::verify_capability( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'agoda-booking' ) ) );
			return;
		}

		$key = isset( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : '';

		if ( empty( $key ) ) {
			wp_send_json_error( array( 'message' => __( 'Cache key is required.', 'agoda-booking' ) ) );
			return;
		}

		if ( ! class_exists( 'Agoda_Cache' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-cache.php';
		}
		$cache = new Agoda_Cache();
		$result = $cache->clear_cache( $key );

		if ( $result ) {
			wp_send_json_success( array( 'message' => __( 'Cache entry deleted successfully.', 'agoda-booking' ) ) );
		} else {
			wp_send_json_error( array( 'message' => __( 'Failed to delete cache entry.', 'agoda-booking' ) ) );
		}
	}

	/**
	 * Handle AJAX get logs request.
	 */
	public function ajax_get_logs() {
		// Load security class
		if ( ! class_exists( 'Agoda_Security' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-security.php';
		}

		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! Agoda_Security::verify_ajax_nonce( 'agoda_booking_logs', $nonce ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'agoda-booking' ) ) );
			return;
		}

		// Check user capabilities
		if ( ! Agoda_Security::verify_capability( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'agoda-booking' ) ) );
			return;
		}

		if ( ! class_exists( 'Agoda_Logger' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-logger.php';
		}
		$logger = new Agoda_Logger();

		$args = array(
			'level'     => isset( $_POST['level'] ) ? sanitize_text_field( wp_unslash( $_POST['level'] ) ) : '',
			'date_from' => isset( $_POST['date_from'] ) ? sanitize_text_field( wp_unslash( $_POST['date_from'] ) ) : '',
			'date_to'   => isset( $_POST['date_to'] ) ? sanitize_text_field( wp_unslash( $_POST['date_to'] ) ) : '',
			'search'    => isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '',
			'page'      => isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1,
			'per_page'  => isset( $_POST['per_page'] ) ? absint( $_POST['per_page'] ) : 50,
		);

		$result = $logger->read_logs( $args );
		wp_send_json_success( $result );
	}

	/**
	 * Handle AJAX save log settings request.
	 */
	public function ajax_save_log_settings() {
		// Load security class
		if ( ! class_exists( 'Agoda_Security' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-security.php';
		}

		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! Agoda_Security::verify_ajax_nonce( 'agoda_booking_logs', $nonce ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'agoda-booking' ) ) );
			return;
		}

		// Check user capabilities
		if ( ! Agoda_Security::verify_capability( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'agoda-booking' ) ) );
			return;
		}

		$enable_logging = isset( $_POST['enable_logging'] ) && '1' === $_POST['enable_logging'];
		$log_level = isset( $_POST['log_level'] ) ? sanitize_text_field( wp_unslash( $_POST['log_level'] ) ) : 'error';
		$log_retention = isset( $_POST['log_retention'] ) ? absint( $_POST['log_retention'] ) : 30;

		// Validate log level
		$valid_levels = array( 'error', 'warning', 'info', 'debug' );
		if ( ! in_array( $log_level, $valid_levels, true ) ) {
			$log_level = 'error';
		}

		update_option( 'agoda_enable_logging', $enable_logging );
		update_option( 'agoda_log_level', $log_level );
		update_option( 'agoda_log_retention_days', $log_retention );

		wp_send_json_success( array( 'message' => __( 'Log settings saved successfully.', 'agoda-booking' ) ) );
	}

	/**
	 * Handle AJAX clear old logs request.
	 */
	public function ajax_clear_old_logs() {
		// Load security class
		if ( ! class_exists( 'Agoda_Security' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-security.php';
		}

		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! Agoda_Security::verify_ajax_nonce( 'agoda_booking_logs', $nonce ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'agoda-booking' ) ) );
			return;
		}

		// Check user capabilities
		if ( ! Agoda_Security::verify_capability( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'agoda-booking' ) ) );
			return;
		}

		$days = isset( $_POST['days'] ) ? absint( $_POST['days'] ) : get_option( 'agoda_log_retention_days', 30 );

		if ( ! class_exists( 'Agoda_Logger' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-logger.php';
		}
		$logger = new Agoda_Logger();
		$count = $logger->clear_old_logs( $days );

		wp_send_json_success( array(
			'message' => sprintf( __( 'Cleared %d old log entry(ies).', 'agoda-booking' ), $count ),
			'count'   => $count,
		) );
	}

	/**
	 * Handle AJAX export logs request.
	 */
	public function ajax_export_logs() {
		// Load security class
		if ( ! class_exists( 'Agoda_Security' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-security.php';
		}

		// Verify nonce
		$nonce = isset( $_GET['nonce'] ) ? sanitize_text_field( wp_unslash( $_GET['nonce'] ) ) : '';
		if ( ! Agoda_Security::verify_ajax_nonce( 'agoda_booking_logs', $nonce ) ) {
			wp_die( __( 'Security check failed.', 'agoda-booking' ) );
		}

		// Check user capabilities
		if ( ! Agoda_Security::verify_capability( 'manage_options' ) ) {
			wp_die( __( 'You do not have permission to perform this action.', 'agoda-booking' ) );
		}

		if ( ! class_exists( 'Agoda_Logger' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-logger.php';
		}
		$logger = new Agoda_Logger();

		$args = array(
			'level'     => isset( $_GET['level'] ) ? sanitize_text_field( wp_unslash( $_GET['level'] ) ) : '',
			'date_from' => isset( $_GET['date_from'] ) ? sanitize_text_field( wp_unslash( $_GET['date_from'] ) ) : '',
			'date_to'   => isset( $_GET['date_to'] ) ? sanitize_text_field( wp_unslash( $_GET['date_to'] ) ) : '',
			'search'    => isset( $_GET['search'] ) ? sanitize_text_field( wp_unslash( $_GET['search'] ) ) : '',
			'per_page'  => 999999,
		);

		$result = $logger->read_logs( $args );
		$logs = $result['logs'];

		// Generate CSV
		$filename = 'agoda-logs-' . date( 'Y-m-d' ) . '.csv';
		header( 'Content-Type: text/csv' );
		header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

		$output = fopen( 'php://output', 'w' );
		fputcsv( $output, array( 'Timestamp', 'Level', 'Message', 'Context' ) );

		foreach ( $logs as $log ) {
			fputcsv( $output, array(
				$log['timestamp'],
				$log['level'],
				$log['message'],
				$log['context'],
			) );
		}

		fclose( $output );
		exit;
	}

	/**
	 * Handle AJAX get statistics request.
	 */
	public function ajax_get_statistics() {
		// Load security class
		if ( ! class_exists( 'Agoda_Security' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-security.php';
		}

		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! Agoda_Security::verify_ajax_nonce( 'agoda_booking_statistics', $nonce ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'agoda-booking' ) ) );
			return;
		}

		// Check user capabilities
		if ( ! Agoda_Security::verify_capability( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'agoda-booking' ) ) );
			return;
		}

		if ( ! class_exists( 'Agoda_Statistics' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-statistics.php';
		}
		$statistics = new Agoda_Statistics();

		$period = isset( $_POST['period'] ) ? sanitize_text_field( wp_unslash( $_POST['period'] ) ) : 'all';
		$stats = $statistics->get_search_statistics( $period );
		$popular_cities = $statistics->get_popular_cities( 10 );
		$popular_hotels = $statistics->get_popular_hotels( 10 );
		$performance = $statistics->get_performance_metrics();

		wp_send_json_success( array(
			'stats'           => $stats,
			'popular_cities'  => $popular_cities,
			'popular_hotels'  => $popular_hotels,
			'performance'     => $performance,
		) );
	}

	/**
	 * Handle AJAX clear statistics request.
	 */
	public function ajax_clear_statistics() {
		// Load security class
		if ( ! class_exists( 'Agoda_Security' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-security.php';
		}

		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! Agoda_Security::verify_ajax_nonce( 'agoda_booking_statistics', $nonce ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'agoda-booking' ) ) );
			return;
		}

		// Check user capabilities
		if ( ! Agoda_Security::verify_capability( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'agoda-booking' ) ) );
			return;
		}

		if ( ! class_exists( 'Agoda_Statistics' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-statistics.php';
		}
		$statistics = new Agoda_Statistics();
		$result = $statistics->clear_statistics();

		if ( $result ) {
			wp_send_json_success( array( 'message' => __( 'Statistics cleared successfully.', 'agoda-booking' ) ) );
		} else {
			wp_send_json_error( array( 'message' => __( 'Failed to clear statistics.', 'agoda-booking' ) ) );
		}
	}

	/**
	 * Handle AJAX export statistics request.
	 */
	public function ajax_export_statistics() {
		// Load security class
		if ( ! class_exists( 'Agoda_Security' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-security.php';
		}

		// Verify nonce
		$nonce = isset( $_GET['nonce'] ) ? sanitize_text_field( wp_unslash( $_GET['nonce'] ) ) : '';
		if ( ! Agoda_Security::verify_ajax_nonce( 'agoda_booking_statistics', $nonce ) ) {
			wp_die( __( 'Security check failed.', 'agoda-booking' ) );
		}

		// Check user capabilities
		if ( ! Agoda_Security::verify_capability( 'manage_options' ) ) {
			wp_die( __( 'You do not have permission to perform this action.', 'agoda-booking' ) );
		}

		if ( ! class_exists( 'Agoda_Statistics' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-statistics.php';
		}
		$statistics = new Agoda_Statistics();

		$period = isset( $_GET['period'] ) ? sanitize_text_field( wp_unslash( $_GET['period'] ) ) : 'all';
		$stats = $statistics->get_search_statistics( $period );
		$popular_cities = $statistics->get_popular_cities( 100 );
		$popular_hotels = $statistics->get_popular_hotels( 100 );
		$performance = $statistics->get_performance_metrics();

		// Generate CSV
		$filename = 'agoda-statistics-' . date( 'Y-m-d' ) . '.csv';
		header( 'Content-Type: text/csv' );
		header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

		$output = fopen( 'php://output', 'w' );
		
		// Write summary
		fputcsv( $output, array( 'Statistics Summary', '' ) );
		fputcsv( $output, array( 'Period', $period ) );
		fputcsv( $output, array( 'Total Searches', $stats['total_searches'] ) );
		fputcsv( $output, array( 'Successful Searches', $stats['successful_searches'] ) );
		fputcsv( $output, array( 'Failed Searches', $stats['failed_searches'] ) );
		fputcsv( $output, array( 'Cached Searches', $stats['cached_searches'] ) );
		fputcsv( $output, array( 'Average Hotels Found', $stats['average_hotels_found'] ) );
		fputcsv( $output, array( 'Average Response Time', $performance['average_response_time'] . 's' ) );
		fputcsv( $output, array( 'Cache Hit Rate', $performance['cache_hit_rate'] . '%' ) );
		fputcsv( $output, array( 'Error Rate', $performance['error_rate'] . '%' ) );
		fputcsv( $output, array( '', '' ) );

		// Write popular cities
		fputcsv( $output, array( 'Popular Cities', '' ) );
		fputcsv( $output, array( 'City ID', 'Search Count' ) );
		foreach ( $popular_cities as $city ) {
			fputcsv( $output, array( $city['city_id'], $city['search_count'] ) );
		}
		fputcsv( $output, array( '', '' ) );

		// Write popular hotels
		fputcsv( $output, array( 'Popular Hotels', '' ) );
		fputcsv( $output, array( 'Hotel ID', 'Hotel Name', 'Search Count' ) );
		foreach ( $popular_hotels as $hotel ) {
			fputcsv( $output, array( $hotel['hotel_id'], $hotel['hotel_name'], $hotel['search_count'] ) );
		}

		fclose( $output );
		exit;
	}

	/**
	 * Handle AJAX bulk hotel search request.
	 */
	public function ajax_bulk_hotel_search() {
		// Load security class
		if ( ! class_exists( 'Agoda_Security' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-security.php';
		}

		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! Agoda_Security::verify_ajax_nonce( 'agoda_booking_bulk_operations', $nonce ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'agoda-booking' ) ) );
			return;
		}

		// Check user capabilities
		if ( ! Agoda_Security::verify_capability( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'agoda-booking' ) ) );
			return;
		}

		// Get and validate parameters
		$hotel_ids_str = isset( $_POST['hotel_ids'] ) ? sanitize_text_field( wp_unslash( $_POST['hotel_ids'] ) ) : '';
		$check_in = isset( $_POST['check_in'] ) ? sanitize_text_field( wp_unslash( $_POST['check_in'] ) ) : '';
		$check_out = isset( $_POST['check_out'] ) ? sanitize_text_field( wp_unslash( $_POST['check_out'] ) ) : '';
		$adults = isset( $_POST['adults'] ) ? absint( $_POST['adults'] ) : 2;
		$currency = isset( $_POST['currency'] ) ? sanitize_text_field( wp_unslash( $_POST['currency'] ) ) : get_option( 'agoda_default_currency', 'USD' );

		if ( empty( $hotel_ids_str ) ) {
			wp_send_json_error( array( 'message' => __( 'Hotel IDs are required.', 'agoda-booking' ) ) );
			return;
		}

		// Parse hotel IDs (support comma and newline separated)
		$hotel_ids_str = str_replace( array( "\r\n", "\n", "\r" ), ',', $hotel_ids_str );
		$hotel_ids = array_map( 'absint', explode( ',', $hotel_ids_str ) );
		$hotel_ids = array_filter( $hotel_ids );
		$hotel_ids = array_unique( $hotel_ids );

		if ( empty( $hotel_ids ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid hotel IDs format.', 'agoda-booking' ) ) );
			return;
		}

		// Limit to 50 hotels per search
		if ( count( $hotel_ids ) > 50 ) {
			wp_send_json_error( array( 'message' => __( 'Maximum 50 hotels per search. Please reduce the number of hotel IDs.', 'agoda-booking' ) ) );
			return;
		}

		// Validate dates
		if ( empty( $check_in ) || empty( $check_out ) ) {
			wp_send_json_error( array( 'message' => __( 'Check-in and check-out dates are required.', 'agoda-booking' ) ) );
			return;
		}

		if ( ! class_exists( 'Agoda_Validator' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-validator.php';
		}
		$validator = new Agoda_Validator();

		if ( ! $validator->validate_date_range( $check_in, $check_out ) ) {
			wp_send_json_error( array( 'message' => __( 'Check-out date must be after check-in date.', 'agoda-booking' ) ) );
			return;
		}

		// Load API class
		if ( ! class_exists( 'Agoda_API' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-api.php';
		}
		$api = new Agoda_API();

		// Prepare search parameters
		$params = array(
			'hotelId'      => $hotel_ids,
			'checkInDate'  => $check_in,
			'checkOutDate' => $check_out,
			'currency'     => $currency,
			'occupancy'    => array(
				'numberOfAdult' => $adults,
				'numberOfChildren' => 0,
			),
		);

		// Perform search
		$result = $api->search_hotels( $params );

		// Handle result
		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array(
				'message' => $result->get_error_message(),
				'code'    => $result->get_error_code(),
			) );
			return;
		}

		// Return success with results
		wp_send_json_success( array(
			'count'   => isset( $result['count'] ) ? $result['count'] : 0,
			'results' => isset( $result['results'] ) ? $result['results'] : array(),
		) );
	}

	/**
	 * Handle AJAX bulk cache clear request.
	 */
	public function ajax_bulk_cache_clear() {
		// Load security class
		if ( ! class_exists( 'Agoda_Security' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-security.php';
		}

		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! Agoda_Security::verify_ajax_nonce( 'agoda_booking_bulk_operations', $nonce ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'agoda-booking' ) ) );
			return;
		}

		// Check user capabilities
		if ( ! Agoda_Security::verify_capability( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'agoda-booking' ) ) );
			return;
		}

		$method = isset( $_POST['method'] ) ? sanitize_text_field( wp_unslash( $_POST['method'] ) ) : 'cities';

		if ( ! class_exists( 'Agoda_Cache' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-cache.php';
		}
		$cache = new Agoda_Cache();

		$count = 0;

		if ( 'cities' === $method ) {
			$city_ids_str = isset( $_POST['city_ids'] ) ? sanitize_text_field( wp_unslash( $_POST['city_ids'] ) ) : '';
			if ( empty( $city_ids_str ) ) {
				wp_send_json_error( array( 'message' => __( 'City IDs are required.', 'agoda-booking' ) ) );
				return;
			}

			// Parse city IDs
			$city_ids = array_map( 'absint', explode( ',', $city_ids_str ) );
			$city_ids = array_filter( $city_ids );

			// Clear cache for each city
			foreach ( $city_ids as $city_id ) {
				$pattern = 'search_' . md5( wp_json_encode( array( 'cityId' => $city_id ) ) );
				$count += $cache->clear_cache_by_pattern( $pattern );
			}
		} else {
			// Date range method - clear all cache (simplified)
			// In a real implementation, you'd need to track which cache entries match which dates
			$result = $cache->clear_cache();
			$count = 1; // Simplified
		}

		wp_send_json_success( array(
			'message' => sprintf( __( 'Cleared %d cache entry(ies).', 'agoda-booking' ), $count ),
			'count'   => $count,
		) );
	}

	/**
	 * Handle AJAX get cities request.
	 */
	public function ajax_get_cities() {
		// Load security class
		if ( ! class_exists( 'Agoda_Security' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-security.php';
		}

		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! Agoda_Security::verify_ajax_nonce( 'agoda_booking_city_management', $nonce ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'agoda-booking' ) ) );
			return;
		}

		// Check user capabilities
		if ( ! Agoda_Security::verify_capability( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'agoda-booking' ) ) );
			return;
		}

		if ( ! class_exists( 'Agoda_Content_API' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-content-api.php';
		}
		$content_api = new Agoda_Content_API();

		if ( ! $content_api->validate_credentials() ) {
			wp_send_json_error( array( 'message' => __( 'Content API credentials are not configured.', 'agoda-booking' ) ) );
			return;
		}

		$search = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '';
		$country = isset( $_POST['country'] ) ? sanitize_text_field( wp_unslash( $_POST['country'] ) ) : '';
		$page = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
		$per_page = isset( $_POST['per_page'] ) ? absint( $_POST['per_page'] ) : 50;

		try {
			$cities = $content_api->search_cities( $search, $country );

			if ( is_wp_error( $cities ) ) {
				wp_send_json_error( array( 'message' => $cities->get_error_message() ) );
				return;
			}

			// Ensure cities is an array
			if ( ! is_array( $cities ) ) {
				$cities = array();
			}

			// Paginate
			$total = count( $cities );
			$total_pages = ceil( $total / $per_page );
			$offset = ( $page - 1 ) * $per_page;
			$paginated_cities = array_slice( $cities, $offset, $per_page );

			wp_send_json_success( array(
				'cities'      => $paginated_cities,
				'total'       => $total,
				'page'        => $page,
				'per_page'    => $per_page,
				'total_pages' => $total_pages,
			) );
		} catch ( Exception $e ) {
			wp_send_json_error( array( 
				'message' => __( 'Error searching cities: ', 'agoda-booking' ) . $e->getMessage() 
			) );
		}
	}

	/**
	 * Handle AJAX get city details request.
	 */
	public function ajax_get_city_details() {
		// Load security class
		if ( ! class_exists( 'Agoda_Security' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-security.php';
		}

		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! Agoda_Security::verify_ajax_nonce( 'agoda_booking_city_management', $nonce ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'agoda-booking' ) ) );
			return;
		}

		// Check user capabilities
		if ( ! Agoda_Security::verify_capability( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'agoda-booking' ) ) );
			return;
		}

		if ( ! class_exists( 'Agoda_Content_API' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-content-api.php';
		}
		$content_api = new Agoda_Content_API();

		if ( ! $content_api->validate_credentials() ) {
			wp_send_json_error( array( 'message' => __( 'Content API credentials are not configured.', 'agoda-booking' ) ) );
			return;
		}

		$city_id = isset( $_POST['city_id'] ) ? absint( $_POST['city_id'] ) : 0;
		if ( empty( $city_id ) ) {
			wp_send_json_error( array( 'message' => __( 'City ID is required.', 'agoda-booking' ) ) );
			return;
		}

		$city = $content_api->get_city_by_id( $city_id );
		if ( false === $city ) {
			wp_send_json_error( array( 'message' => __( 'City not found.', 'agoda-booking' ) ) );
			return;
		}

		wp_send_json_success( array( 'city' => $city ) );
	}
}
