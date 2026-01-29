<?php
/**
 * The API integration class.
 *
 * @package Agoda_Booking
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The API integration class.
 */
class Agoda_API {

	/**
	 * API endpoint URL.
	 *
	 * @var string
	 */
	private $endpoint;

	/**
	 * Site ID.
	 *
	 * @var string
	 */
	private $site_id;

	/**
	 * API Key.
	 *
	 * @var string
	 */
	private $api_key;

	/**
	 * CID (Customer ID) for affiliate tracking.
	 *
	 * @var string
	 */
	private $cid;

	/**
	 * Request timeout in seconds.
	 *
	 * @var int
	 */
	private $timeout = 30;

	/**
	 * Logger instance.
	 *
	 * @var Agoda_Logger
	 */
	private $logger;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct() {
		$this->endpoint = get_option( 'agoda_api_endpoint', 'http://affiliateapi7643.agoda.com/affiliateservice/lt_v1' );
		$this->site_id  = get_option( 'agoda_site_id', '' );
		$this->api_key  = get_option( 'agoda_api_key', '' );
		$this->cid      = get_option( 'agoda_cid', '' );

		// Initialize logger
		if ( ! class_exists( 'Agoda_Logger' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-logger.php';
		}
		$this->logger = new Agoda_Logger();
	}

	/**
	 * Get CID for affiliate tracking.
	 * Returns CID if set, otherwise returns Site ID.
	 *
	 * @return string CID or Site ID.
	 */
	public function get_cid() {
		return ! empty( $this->cid ) ? $this->cid : $this->site_id;
	}

	/**
	 * Search hotels by city ID.
	 *
	 * @param array $params Search parameters including:
	 *                      - cityId (int) - City ID
	 *                      - checkInDate (string) - Check-in date (YYYY-MM-DD)
	 *                      - checkOutDate (string) - Check-out date (YYYY-MM-DD)
	 *                      - language (string) - Language code (optional)
	 *                      - currency (string) - Currency code (optional)
	 *                      - occupancy (array) - Occupancy details (optional)
	 *                      - additional (array) - Additional filters (optional)
	 * @return array|WP_Error Response data with hotels list or error.
	 */
	public function search_city( $params ) {
		// Validate credentials
		if ( ! $this->validate_credentials() ) {
			return new WP_Error( 'agoda_invalid_credentials', __( 'API credentials are not configured. Please check your settings.', 'agoda-booking' ) );
		}

		// Validate required parameters
		if ( empty( $params['cityId'] ) || ! is_numeric( $params['cityId'] ) ) {
			return new WP_Error( 'agoda_invalid_city_id', __( 'Invalid city ID.', 'agoda-booking' ) );
		}

		if ( empty( $params['checkInDate'] ) || empty( $params['checkOutDate'] ) ) {
			return new WP_Error( 'agoda_missing_dates', __( 'Check-in and check-out dates are required.', 'agoda-booking' ) );
		}

		// Prepare request body
		$request_body = $this->prepare_request( $params, 'city' );

		// Send request
		$response = $this->send_request( $request_body );

		// Handle and return response
		return $this->handle_response( $response );
	}

	/**
	 * Search hotels by hotel ID list.
	 *
	 * @param array $params Search parameters including:
	 *                      - hotelId (array) - Array of hotel IDs
	 *                      - checkInDate (string) - Check-in date (YYYY-MM-DD)
	 *                      - checkOutDate (string) - Check-out date (YYYY-MM-DD)
	 *                      - language (string) - Language code (optional)
	 *                      - currency (string) - Currency code (optional)
	 *                      - occupancy (array) - Occupancy details (optional)
	 * @return array|WP_Error Response data with hotels list or error.
	 */
	public function search_hotels( $params ) {
		// Validate credentials
		if ( ! $this->validate_credentials() ) {
			return new WP_Error( 'agoda_invalid_credentials', __( 'API credentials are not configured. Please check your settings.', 'agoda-booking' ) );
		}

		// Validate required parameters
		if ( empty( $params['hotelId'] ) || ! is_array( $params['hotelId'] ) ) {
			return new WP_Error( 'agoda_invalid_hotel_id', __( 'Invalid hotel ID list. Must be an array of hotel IDs.', 'agoda-booking' ) );
		}

		// Validate hotel IDs are integers
		foreach ( $params['hotelId'] as $hotel_id ) {
			if ( ! is_numeric( $hotel_id ) ) {
				return new WP_Error( 'agoda_invalid_hotel_id', __( 'All hotel IDs must be numeric.', 'agoda-booking' ) );
			}
		}

		if ( empty( $params['checkInDate'] ) || empty( $params['checkOutDate'] ) ) {
			return new WP_Error( 'agoda_missing_dates', __( 'Check-in and check-out dates are required.', 'agoda-booking' ) );
		}

		// Prepare request body
		$request_body = $this->prepare_request( $params, 'hotels' );

		// Send request
		$response = $this->send_request( $request_body );

		// Handle and return response
		return $this->handle_response( $response );
	}

	/**
	 * Validate API credentials.
	 *
	 * @return bool True if valid, false otherwise.
	 */
	public function validate_credentials() {
		return ! empty( $this->site_id ) && ! empty( $this->api_key );
	}

	/**
	 * Prepare API request body.
	 *
	 * @param array  $params Search parameters.
	 * @param string $type Request type: 'city' or 'hotels'.
	 * @return array Request body array.
	 */
	private function prepare_request( $params, $type = 'city' ) {
		$default_language = get_option( 'agoda_default_language', 'en-us' );
		$default_currency = get_option( 'agoda_default_currency', 'USD' );

		// Base criteria structure
		$criteria = array(
			'checkInDate'  => sanitize_text_field( $params['checkInDate'] ),
			'checkOutDate' => sanitize_text_field( $params['checkOutDate'] ),
		);

		// Add cityId or hotelId based on type
		if ( 'city' === $type ) {
			$criteria['cityId'] = absint( $params['cityId'] );
		} else {
			$criteria['hotelId'] = array_map( 'absint', $params['hotelId'] );
		}

		// Prepare additional parameters
		$additional = array(
			'language' => isset( $params['language'] ) ? sanitize_text_field( $params['language'] ) : $default_language,
			'currency' => isset( $params['currency'] ) ? sanitize_text_field( $params['currency'] ) : $default_currency,
		);

		// Add occupancy if provided
		if ( isset( $params['occupancy'] ) && is_array( $params['occupancy'] ) ) {
			$occupancy = array();

			if ( isset( $params['occupancy']['numberOfAdult'] ) ) {
				$occupancy['numberOfAdult'] = absint( $params['occupancy']['numberOfAdult'] );
			} else {
				$occupancy['numberOfAdult'] = 2; // Default
			}

			if ( isset( $params['occupancy']['numberOfChildren'] ) ) {
				$occupancy['numberOfChildren'] = absint( $params['occupancy']['numberOfChildren'] );
			} else {
				$occupancy['numberOfChildren'] = 0; // Default
			}

			if ( isset( $params['occupancy']['childrenAges'] ) && is_array( $params['occupancy']['childrenAges'] ) ) {
				$occupancy['childrenAges'] = array_map( 'absint', $params['occupancy']['childrenAges'] );
			}

			$additional['occupancy'] = $occupancy;
		} else {
			// Default occupancy
			$additional['occupancy'] = array(
				'numberOfAdult'    => 2,
				'numberOfChildren' => 0,
			);
		}

		// Add additional filters for city search only
		if ( 'city' === $type && isset( $params['additional'] ) && is_array( $params['additional'] ) ) {
			$additional_params = $params['additional'];

			// Sort by
			if ( isset( $additional_params['sortBy'] ) ) {
				$additional['sortBy'] = sanitize_text_field( $additional_params['sortBy'] );
			}

			// Max results
			if ( isset( $additional_params['maxResult'] ) ) {
				$max_result = absint( $additional_params['maxResult'] );
				$additional['maxResult'] = min( max( 1, $max_result ), 30 ); // Between 1 and 30
			} else {
				$additional['maxResult'] = 10; // Default
			}

			// Discount only
			if ( isset( $additional_params['discountOnly'] ) ) {
				$additional['discountOnly'] = (bool) $additional_params['discountOnly'];
			}

			// Minimum star rating
			if ( isset( $additional_params['minimumStarRating'] ) ) {
				$additional['minimumStarRating'] = floatval( $additional_params['minimumStarRating'] );
			}

			// Minimum review score
			if ( isset( $additional_params['minimumReviewScore'] ) ) {
				$additional['minimumReviewScore'] = floatval( $additional_params['minimumReviewScore'] );
			}

			// Daily rate range
			if ( isset( $additional_params['dailyRate'] ) && is_array( $additional_params['dailyRate'] ) ) {
				$daily_rate = array();
				if ( isset( $additional_params['dailyRate']['minimum'] ) ) {
					$daily_rate['minimum'] = floatval( $additional_params['dailyRate']['minimum'] );
				}
				if ( isset( $additional_params['dailyRate']['maximum'] ) ) {
					$daily_rate['maximum'] = floatval( $additional_params['dailyRate']['maximum'] );
				}
				if ( ! empty( $daily_rate ) ) {
					$additional['dailyRate'] = $daily_rate;
				}
			}
		}

		$criteria['additional'] = $additional;

		return array( 'criteria' => $criteria );
	}

	/**
	 * Send API request.
	 *
	 * @param array $body Request body.
	 * @return array|WP_Error API response or error.
	 */
	private function send_request( $body ) {
		// Prepare headers
		// Note: If api_key already contains site_id (format: "site_id:key"), use it as is
		// Otherwise, combine site_id and api_key
		$auth_value = $this->api_key;
		if ( strpos( $this->api_key, ':' ) === false ) {
			// API key doesn't contain site_id, combine them
			$auth_value = $this->site_id . ':' . $this->api_key;
		}
		// If api_key already starts with site_id, use it directly
		elseif ( strpos( $this->api_key, $this->site_id . ':' ) === 0 ) {
			$auth_value = $this->api_key;
		}
		
		$headers = array(
			'Accept-Encoding' => 'gzip,deflate',
			'Content-Type'    => 'application/json',
			'Authorization'   => $auth_value,
		);

		// Prepare request arguments
		$args = array(
			'method'  => 'POST',
			'headers' => $headers,
			'body'    => wp_json_encode( $body ),
			'timeout' => $this->timeout,
		);

		// Log API request (debug level)
		$this->logger->log_api_request( $this->endpoint, $body );

		// Send request
		$response = wp_remote_post( $this->endpoint, $args );

		// Check for WP_Error (network errors, timeouts, etc.)
		if ( is_wp_error( $response ) ) {
			$error_code = $response->get_error_code();
			$error_message = $response->get_error_message();

			// Log network error
			$this->logger->log_api_error(
				'agoda_network_error',
				sprintf( 'Network error: %s (%s)', $error_message, $error_code ),
				array(
					'error_code' => $error_code,
					'endpoint'   => $this->endpoint,
				)
			);

			// Return user-friendly error
			return new WP_Error(
				'agoda_network_error',
				__( 'Network error. Please check your internet connection and try again.', 'agoda-booking' ),
				array(
					'error_code' => $error_code,
					'error_message' => $error_message,
				)
			);
		}

		// Get response code and body
		$status_code = wp_remote_retrieve_response_code( $response );
		$response_body = wp_remote_retrieve_body( $response );

		// Log API response (debug level)
		$response_data = array();
		if ( ! empty( $response_body ) ) {
			$decoded = json_decode( $response_body, true );
			if ( json_last_error() === JSON_ERROR_NONE ) {
				$response_data = $decoded;
			}
		}
		$this->logger->log_api_response( $status_code, $response_data );

		// Check for HTTP errors
		if ( $status_code < 200 || $status_code >= 300 ) {
			return $this->handle_errors( $status_code, $response_body );
		}

		// Track successful API call
		$this->track_api_call();

		// Return response array
		return array(
			'status_code' => $status_code,
			'body'        => $response_body,
		);
	}

	/**
	 * Parse API response.
	 *
	 * @param array|WP_Error $response Raw API response.
	 * @return array|WP_Error Parsed response data or error.
	 */
	private function parse_response( $response ) {
		// If it's already a WP_Error, return it
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		// Decode JSON response
		$data = json_decode( $response['body'], true );

		// Check for JSON decode errors
		if ( json_last_error() !== JSON_ERROR_NONE ) {
			$json_error = json_last_error_msg();
			
			// Log JSON decode error
			$this->logger->log_api_error(
				'agoda_json_decode_error',
				sprintf( 'Failed to parse JSON response: %s', $json_error ),
				array(
					'json_error' => $json_error,
					'response_body' => substr( $response['body'], 0, 500 ), // First 500 chars
				)
			);

			return new WP_Error(
				'agoda_json_decode_error',
				__( 'Failed to parse API response.', 'agoda-booking' ),
				array( 'json_error' => $json_error )
			);
		}

		// Check if results exist
		if ( ! isset( $data['results'] ) ) {
			return new WP_Error(
				'agoda_no_results',
				__( 'No results found in API response.', 'agoda-booking' )
			);
		}

		// Return parsed results
		return array(
			'results' => $data['results'],
			'count'   => count( $data['results'] ),
		);
	}

	/**
	 * Handle API errors.
	 *
	 * @param int    $status_code HTTP status code.
	 * @param string $response_body Response body.
	 * @return WP_Error Error object with appropriate message.
	 */
	private function handle_errors( $status_code, $response_body = '' ) {
		$error_message = '';
		$error_code = 'agoda_api_error';

		switch ( $status_code ) {
			case 400:
				$error_code = 'agoda_bad_request';
				$error_message = __( 'Invalid request. Please check your search parameters.', 'agoda-booking' );
				break;

			case 401:
				$error_code = 'agoda_unauthorized';
				$error_message = __( 'Invalid API credentials. Please check your Site ID and API Key.', 'agoda-booking' );
				break;

			case 403:
				$error_code = 'agoda_forbidden';
				$error_message = __( 'Access forbidden. You may have exceeded your API quota or violated terms of use.', 'agoda-booking' );
				break;

			case 404:
				$error_code = 'agoda_not_found';
				$error_message = __( 'Service or resource not found.', 'agoda-booking' );
				break;

			case 410:
				$error_code = 'agoda_gone';
				$error_message = __( 'The requested resource is no longer available.', 'agoda-booking' );
				break;

			case 500:
				$error_code = 'agoda_internal_error';
				$error_message = __( 'Internal server error. Please try again later.', 'agoda-booking' );
				break;

			case 503:
				$error_code = 'agoda_service_unavailable';
				$error_message = __( 'Service temporarily unavailable. Please try again later.', 'agoda-booking' );
				break;

			case 506:
				$error_code = 'agoda_partial_confirm';
				$error_message = __( 'Cannot process the entire request. Please contact customer service.', 'agoda-booking' );
				break;

			default:
				$error_message = sprintf(
					/* translators: %d: HTTP status code */
					__( 'API request failed with status code %d.', 'agoda-booking' ),
					$status_code
				);
		}

		// Try to parse error response body for additional details
		$error_data = array(
			'status_code' => $status_code,
		);

		if ( ! empty( $response_body ) ) {
			$error_body = json_decode( $response_body, true );
			if ( json_last_error() === JSON_ERROR_NONE && isset( $error_body['message'] ) ) {
				$error_message = $error_body['message'];
			} else {
				$error_data['response_body'] = $response_body;
			}
		}

		// Log error
		$this->logger->log_api_error( $error_code, $error_message, $error_data );

		return new WP_Error( $error_code, $error_message, $error_data );
	}

	/**
	 * Handle API response.
	 *
	 * @param array|WP_Error $response API response.
	 * @return array|WP_Error Parsed response or error.
	 */
	private function handle_response( $response ) {
		// If it's already a WP_Error, return it
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		// Parse response
		$parsed = $this->parse_response( $response );

		// If parsing resulted in error, return it
		if ( is_wp_error( $parsed ) ) {
			return $parsed;
		}

		// Handle empty results
		if ( empty( $parsed['results'] ) ) {
			return new WP_Error(
				'agoda_no_hotels',
				__( 'No hotels found for your search criteria.', 'agoda-booking' )
			);
		}

		// Return parsed results
		return $parsed;
	}

	/**
	 * Track API call for statistics.
	 *
	 * @return void
	 */
	private function track_api_call() {
		$today = current_time( 'Y-m-d' );
		$stored_date = get_option( 'agoda_api_calls_date', '' );
		
		// Reset counter if it's a new day
		if ( $stored_date !== $today ) {
			update_option( 'agoda_api_calls_date', $today );
			update_option( 'agoda_api_calls_today', 1 );
		} else {
			// Increment counter
			$current_count = get_option( 'agoda_api_calls_today', 0 );
			update_option( 'agoda_api_calls_today', $current_count + 1 );
		}
	}
}
