<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @package Agoda_Booking
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The public-facing functionality of the plugin.
 */
class Agoda_Frontend {

	/**
	 * Logger instance.
	 *
	 * @var Agoda_Logger
	 */
	private $logger;

	/**
	 * Initialize the frontend.
	 */
	public function __construct() {
		// Initialize logger
		if ( ! class_exists( 'Agoda_Logger' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-logger.php';
		}
		$this->logger = new Agoda_Logger();
	}

	/**
	 * Render search form.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string Search form HTML.
	 */
	public function render_search_form( $atts ) {
		ob_start();
		require_once AGODA_BOOKING_PLUGIN_DIR . 'public/views/search-form.php';
		return ob_get_clean();
	}

	/**
	 * Render search results.
	 *
	 * @param array $results Search results.
	 * @return string Results HTML.
	 */
	public function render_results( $results ) {
		ob_start();
		require_once AGODA_BOOKING_PLUGIN_DIR . 'public/views/results.php';
		return ob_get_clean();
	}

	/**
	 * Handle AJAX search request.
	 *
	 * Validates nonce, sanitizes input, calls API, and returns JSON response.
	 */
	public function ajax_search() {
		// Load security class
		if ( ! class_exists( 'Agoda_Security' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-security.php';
		}

		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! Agoda_Security::verify_ajax_nonce( 'agoda_booking_search', $nonce ) ) {
			$this->logger->error( 'AJAX search: Security check failed (nonce verification)', array(
				'ip' => Agoda_Security::get_user_identifier(),
			) );
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'agoda-booking' ) ) );
			return;
		}

		// Check rate limit
		$user_identifier = Agoda_Security::get_user_identifier();
		$rate_limit = get_option( 'agoda_rate_limit_enabled', false );
		if ( $rate_limit ) {
			$max_requests = get_option( 'agoda_rate_limit_max', 10 );
			$time_window = get_option( 'agoda_rate_limit_window', 60 );
			$rate_check = Agoda_Security::check_rate_limit( $user_identifier, $max_requests, $time_window );
			if ( is_wp_error( $rate_check ) ) {
				$this->logger->warning( 'AJAX search: Rate limit exceeded', array(
					'identifier' => $user_identifier,
				) );
				wp_send_json_error( array(
					'message' => $rate_check->get_error_message(),
					'code'    => $rate_check->get_error_code(),
				) );
				return;
			}
		}

		// Load required classes
		if ( ! class_exists( 'Agoda_Validator' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-validator.php';
		}
		if ( ! class_exists( 'Agoda_API' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-api.php';
		}
		if ( ! class_exists( 'Agoda_Cache' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-cache.php';
		}

		// Prepare search parameters
		$params = array();

		// Dates
		if ( isset( $_POST['check_in'] ) && isset( $_POST['check_out'] ) ) {
			$params['checkInDate'] = sanitize_text_field( wp_unslash( $_POST['check_in'] ) );
			$params['checkOutDate'] = sanitize_text_field( wp_unslash( $_POST['check_out'] ) );
		}

		// City ID or Hotel IDs
		if ( isset( $_POST['city_id'] ) && ! empty( $_POST['city_id'] ) ) {
			$params['cityId'] = absint( $_POST['city_id'] );
		} elseif ( isset( $_POST['hotel_ids'] ) && ! empty( $_POST['hotel_ids'] ) ) {
			$hotel_ids = is_array( $_POST['hotel_ids'] ) ? $_POST['hotel_ids'] : explode( ',', $_POST['hotel_ids'] );
			$params['hotelId'] = array_map( 'absint', $hotel_ids );
		}

		// Occupancy
		$occupancy = array();
		if ( isset( $_POST['adults'] ) ) {
			$occupancy['numberOfAdult'] = absint( $_POST['adults'] );
		} else {
			$occupancy['numberOfAdult'] = 2; // Default
		}

		if ( isset( $_POST['children'] ) ) {
			$occupancy['numberOfChildren'] = absint( $_POST['children'] );
		} else {
			$occupancy['numberOfChildren'] = 0; // Default
		}

		// Children ages
		if ( isset( $_POST['children_ages'] ) && ! empty( $_POST['children_ages'] ) ) {
			$children_ages = is_array( $_POST['children_ages'] ) ? $_POST['children_ages'] : explode( ',', $_POST['children_ages'] );
			$occupancy['childrenAges'] = array_map( 'absint', $children_ages );
		}

		if ( ! empty( $occupancy ) ) {
			$params['occupancy'] = $occupancy;
		}

		// Language and currency
		if ( isset( $_POST['language'] ) ) {
			$params['language'] = sanitize_text_field( wp_unslash( $_POST['language'] ) );
		}

		if ( isset( $_POST['currency'] ) ) {
			$params['currency'] = strtoupper( sanitize_text_field( wp_unslash( $_POST['currency'] ) ) );
		}

		// Validate and sanitize parameters
		$validator = new Agoda_Validator();
		$validated_params = $validator->validate_and_sanitize_search_params( $params );

		if ( is_wp_error( $validated_params ) ) {
			// Log validation error
			$this->logger->log_validation_error(
				'search_params',
				$validated_params->get_error_message(),
				array(
					'error_code' => $validated_params->get_error_code(),
					'params' => $params,
				)
			);

			wp_send_json_error( array(
				'message' => $validated_params->get_error_message(),
				'code'    => $validated_params->get_error_code(),
			) );
			return;
		}

		// Check cache
		$cache = new Agoda_Cache();
		$cache_key = $cache->generate_cache_key( $validated_params );
		$cached_results = $cache->get_cache( $cache_key );

		$start_time = microtime( true );
		$response_time = 0;
		$is_cached = false;

		if ( false !== $cached_results ) {
			// Return cached results
			$response_time = microtime( true ) - $start_time;
			$is_cached = true;
			$html = $this->render_results( $cached_results['results'] );
			
			// Track statistics
			$this->track_search_statistics( $validated_params, $cached_results, $response_time, true );
			
			wp_send_json_success( array(
				'html'  => $html,
				'count' => $cached_results['count'],
				'cached' => true,
			) );
			return;
		}

		// Call API
		$api = new Agoda_API();

		// Determine search type
		if ( isset( $validated_params['cityId'] ) ) {
			$results = $api->search_city( $validated_params );
		} elseif ( isset( $validated_params['hotelId'] ) ) {
			$results = $api->search_hotels( $validated_params );
		} else {
			wp_send_json_error( array( 'message' => __( 'Please provide either City ID or Hotel IDs.', 'agoda-booking' ) ) );
			return;
		}

		$response_time = microtime( true ) - $start_time;

		// Handle API response
		if ( is_wp_error( $results ) ) {
			// Error is already logged by Agoda_API class
			// Track statistics for error
			$this->track_search_statistics( $validated_params, $results, $response_time, false );
			
			wp_send_json_error( array(
				'message' => $results->get_error_message(),
				'code'    => $results->get_error_code(),
			) );
			return;
		}

		// Cache results
		$cache_duration = get_option( 'agoda_cache_duration', 3600 );
		if ( $cache_duration > 0 ) {
			$cache->set_cache( $cache_key, $results, $cache_duration );
		}

		// Render results HTML
		$html = $this->render_results( $results['results'] );

		// Track statistics
		$this->track_search_statistics( $validated_params, $results, $response_time, false );

		// Return success response
		wp_send_json_success( array(
			'html'  => $html,
			'count' => isset( $results['count'] ) ? $results['count'] : count( $results['results'] ),
			'cached' => false,
		) );
	}

	/**
	 * Enqueue public styles.
	 */
	public function enqueue_styles() {
		wp_enqueue_style(
			'agoda-booking-frontend',
			AGODA_BOOKING_PLUGIN_URL . 'public/css/frontend.css',
			array(),
			AGODA_BOOKING_VERSION,
			'all'
		);
	}

	/**
	 * Enqueue public scripts.
	 */
	public function enqueue_scripts() {
		wp_enqueue_script(
			'agoda-booking-frontend',
			AGODA_BOOKING_PLUGIN_URL . 'public/js/frontend.js',
			array( 'jquery' ),
			AGODA_BOOKING_VERSION,
			true
		);

		// Ensure credentials are not exposed in frontend
		$localize_data = array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'agoda_booking_search' ),
		);

		// Never expose API credentials in frontend
		// Security check: ensure credentials are not accidentally included
		if ( class_exists( 'Agoda_Security' ) ) {
			$is_safe = Agoda_Security::check_credentials_exposure();
			if ( ! $is_safe ) {
				// Log warning if credentials might be exposed
				$this->logger->warning( 'Potential credentials exposure detected in frontend scripts' );
			}
		}

		wp_localize_script(
			'agoda-booking-frontend',
			'agodaBooking',
			$localize_data
		);
	}

	/**
	 * Track search statistics.
	 *
	 * @param array       $params Search parameters.
	 * @param array|WP_Error $result Search result.
	 * @param float       $response_time Response time in seconds.
	 * @param bool        $cached Whether result was cached.
	 * @return void
	 */
	private function track_search_statistics( $params, $result, $response_time, $cached ) {
		if ( ! class_exists( 'Agoda_Statistics' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-statistics.php';
		}

		$statistics = new Agoda_Statistics();
		
		// Prepare result data for tracking
		$result_data = $result;
		if ( is_wp_error( $result ) ) {
			$result_data = array(
				'count' => 0,
				'results' => array(),
				'error' => true,
			);
		} else {
			$result_data['cached'] = $cached;
		}
		
		$statistics->track_search( $params, $result_data, $response_time );
	}
}
