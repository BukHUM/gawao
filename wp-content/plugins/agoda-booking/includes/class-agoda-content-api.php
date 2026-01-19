<?php
/**
 * Content API class for fetching hotel content data.
 *
 * @package Agoda_Booking
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Content API class.
 */
class Agoda_Content_API {

	/**
	 * Base URL for Content API.
	 *
	 * @var string
	 */
	private $base_url;

	/**
	 * API token.
	 *
	 * @var string
	 */
	private $token;

	/**
	 * Site ID for Content API.
	 *
	 * @var string
	 */
	private $site_id;

	/**
	 * Logger instance.
	 *
	 * @var Agoda_Logger
	 */
	private $logger;

	/**
	 * Initialize the Content API.
	 */
	public function __construct() {
		$this->base_url = get_option( 'agoda_content_api_base_url', 'https://affiliateapi7643.agoda.com' );
		$this->token = get_option( 'agoda_content_api_token', '' );
		$this->site_id = get_option( 'agoda_content_api_site_id', '' );

		if ( ! class_exists( 'Agoda_Logger' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-logger.php';
		}
		$this->logger = new Agoda_Logger();
	}

	/**
	 * Validate Content API credentials.
	 *
	 * @return bool True if valid, false otherwise.
	 */
	public function validate_credentials() {
		return ! empty( $this->token ) && ! empty( $this->site_id );
	}

	/**
	 * Get feed data from Content API.
	 *
	 * @param int $feed_id Feed ID (1=Continents, 2=Countries, 3=Cities, 4=Areas, 5=Hotels, etc.).
	 * @return array|WP_Error Feed data or error.
	 */
	public function get_feed( $feed_id ) {
		if ( ! $this->validate_credentials() ) {
			return new WP_Error(
				'agoda_content_api_invalid_credentials',
				__( 'Content API credentials are not configured. Please check your settings.', 'agoda-booking' )
			);
		}

		// Check cache first
		$cache_key = 'content_api_feed_' . $feed_id;
		$cached = get_transient( 'agoda_' . $cache_key );
		if ( false !== $cached ) {
			return $cached;
		}

		// Build URL
		$url = $this->base_url . '/datafeeds/feed/getfeed';
		$url = add_query_arg(
			array(
				'feed_id' => $feed_id,
				'token'   => $this->token,
				'site_id' => $this->site_id,
			),
			$url
		);

		// Send request
		$response = wp_remote_get(
			$url,
			array(
				'timeout' => 30,
				'headers' => array(
					'Accept-Encoding' => 'gzip,deflate',
				),
			)
		);

		// Check for errors
		if ( is_wp_error( $response ) ) {
			$this->logger->log_api_error(
				'agoda_content_api_network_error',
				$response->get_error_message(),
				array(
					'feed_id' => $feed_id,
					'url'     => $url,
				)
			);
			return $response;
		}

		$status_code = wp_remote_retrieve_response_code( $response );
		$body = wp_remote_retrieve_body( $response );

		// Check HTTP status
		if ( $status_code < 200 || $status_code >= 300 ) {
			$this->logger->log_api_error(
				'agoda_content_api_error',
				sprintf( 'HTTP %d: %s', $status_code, $body ),
				array(
					'feed_id'     => $feed_id,
					'status_code' => $status_code,
				)
			);
			return new WP_Error(
				'agoda_content_api_error',
				sprintf( __( 'Content API request failed with status code %d.', 'agoda-booking' ), $status_code )
			);
		}

		// Parse response (Content API returns CSV or JSON depending on feed)
		$data = $this->parse_feed_response( $body, $feed_id );

		if ( is_wp_error( $data ) ) {
			return $data;
		}

		// Cache for 24 hours
		set_transient( 'agoda_' . $cache_key, $data, DAY_IN_SECONDS );

		return $data;
	}

	/**
	 * Parse feed response.
	 *
	 * @param string $body Response body.
	 * @param int    $feed_id Feed ID.
	 * @return array|WP_Error Parsed data or error.
	 */
	private function parse_feed_response( $body, $feed_id ) {
		// Try JSON first
		$json_data = json_decode( $body, true );
		if ( json_last_error() === JSON_ERROR_NONE ) {
			return $json_data;
		}

		// Try CSV (Content API often returns CSV)
		if ( $this->is_csv( $body ) ) {
			return $this->parse_csv( $body );
		}

		// If neither, return error
		return new WP_Error(
			'agoda_content_api_parse_error',
			__( 'Failed to parse Content API response.', 'agoda-booking' )
		);
	}

	/**
	 * Check if string is CSV format.
	 *
	 * @param string $data Data to check.
	 * @return bool True if CSV, false otherwise.
	 */
	private function is_csv( $data ) {
		// Simple check: if it contains commas and newlines, likely CSV
		return strpos( $data, ',' ) !== false && strpos( $data, "\n" ) !== false;
	}

	/**
	 * Parse CSV data.
	 *
	 * @param string $csv CSV string.
	 * @return array Parsed data.
	 */
	private function parse_csv( $csv ) {
		$lines = explode( "\n", trim( $csv ) );
		if ( empty( $lines ) ) {
			return array();
		}

		// First line is header
		$headers = str_getcsv( $lines[0] );
		$headers = array_map( 'trim', $headers );

		$data = array();
		for ( $i = 1; $i < count( $lines ); $i++ ) {
			if ( empty( trim( $lines[ $i ] ) ) ) {
				continue;
			}
			$row = str_getcsv( $lines[ $i ] );
			if ( count( $row ) === count( $headers ) ) {
				$data[] = array_combine( $headers, $row );
			}
		}

		return $data;
	}

	/**
	 * Get cities list (Feed 3).
	 *
	 * @return array|WP_Error Cities list or error.
	 */
	public function get_cities() {
		return $this->get_feed( 3 );
	}

	/**
	 * Get countries list (Feed 2).
	 *
	 * @return array|WP_Error Countries list or error.
	 */
	public function get_countries() {
		return $this->get_feed( 2 );
	}

	/**
	 * Search cities by name.
	 *
	 * @param string $search_term Search term.
	 * @param string $country_code Country code (optional).
	 * @return array|WP_Error Filtered cities or error.
	 */
	public function search_cities( $search_term = '', $country_code = '' ) {
		$cities = $this->get_cities();
		if ( is_wp_error( $cities ) ) {
			return $cities;
		}

		if ( empty( $cities ) || ! is_array( $cities ) ) {
			return array();
		}

		$filtered = array();

		foreach ( $cities as $city ) {
			// Skip invalid entries
			if ( ! is_array( $city ) ) {
				continue;
			}

			// Filter by country if specified
			if ( ! empty( $country_code ) ) {
				$city_country = isset( $city['CountryCode'] ) ? $city['CountryCode'] : ( isset( $city['country_code'] ) ? $city['country_code'] : '' );
				if ( strtolower( $city_country ) !== strtolower( $country_code ) ) {
					continue;
				}
			}

			// Filter by search term if specified
			if ( ! empty( $search_term ) ) {
				$city_name = isset( $city['CityName'] ) ? $city['CityName'] : ( isset( $city['city_name'] ) ? $city['city_name'] : '' );
				if ( empty( $city_name ) ) {
					continue;
				}
				$city_name_lower = strtolower( $city_name );
				$search_lower = strtolower( trim( $search_term ) );

				if ( strpos( $city_name_lower, $search_lower ) === false ) {
					continue;
				}
			}

			$filtered[] = $city;
		}

		return $filtered;
	}

	/**
	 * Get city by ID.
	 *
	 * @param int $city_id City ID.
	 * @return array|false City data or false if not found.
	 */
	public function get_city_by_id( $city_id ) {
		$cities = $this->get_cities();
		if ( is_wp_error( $cities ) || empty( $cities ) ) {
			return false;
		}

		foreach ( $cities as $city ) {
			$id = isset( $city['CityId'] ) ? $city['CityId'] : ( isset( $city['city_id'] ) ? $city['city_id'] : null );
			if ( (int) $id === (int) $city_id ) {
				return $city;
			}
		}

		return false;
	}
}
