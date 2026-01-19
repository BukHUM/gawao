<?php
/**
 * The input validation class.
 *
 * @package Agoda_Booking
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The input validation class.
 */
class Agoda_Validator {

	/**
	 * Logger instance.
	 *
	 * @var Agoda_Logger
	 */
	private $logger;

	/**
	 * Initialize the validator.
	 */
	public function __construct() {
		// Initialize logger
		if ( ! class_exists( 'Agoda_Logger' ) ) {
			require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-logger.php';
		}
		$this->logger = new Agoda_Logger();
	}

	/**
	 * Validate dates.
	 *
	 * @param string $check_in Check-in date (YYYY-MM-DD).
	 * @param string $check_out Check-out date (YYYY-MM-DD).
	 * @return bool|WP_Error True if valid, WP_Error otherwise.
	 */
	public function validate_dates( $check_in, $check_out ) {
		// Validate date format
		if ( ! $this->is_valid_date_format( $check_in ) ) {
			$this->logger->log_validation_error( 'check_in', 'Invalid date format', $check_in );
			return new WP_Error(
				'agoda_invalid_checkin_format',
				__( 'Check-in date must be in YYYY-MM-DD format.', 'agoda-booking' )
			);
		}

		if ( ! $this->is_valid_date_format( $check_out ) ) {
			$this->logger->log_validation_error( 'check_out', 'Invalid date format', $check_out );
			return new WP_Error(
				'agoda_invalid_checkout_format',
				__( 'Check-out date must be in YYYY-MM-DD format.', 'agoda-booking' )
			);
		}

		// Convert to timestamps
		$check_in_timestamp = strtotime( $check_in );
		$check_out_timestamp = strtotime( $check_out );
		$today_timestamp = strtotime( 'today' );

		// Check if dates are valid
		if ( false === $check_in_timestamp || false === $check_out_timestamp ) {
			return new WP_Error(
				'agoda_invalid_dates',
				__( 'Invalid date values. Please check your dates.', 'agoda-booking' )
			);
		}

		// Check-in date must be >= today
		if ( $check_in_timestamp < $today_timestamp ) {
			$this->logger->log_validation_error( 'check_in', 'Check-in date is in the past', $check_in );
			return new WP_Error(
				'agoda_past_checkin',
				__( 'Check-in date cannot be in the past. Please select today or a future date.', 'agoda-booking' )
			);
		}

		// Check-out date must be > check-in date
		if ( $check_out_timestamp <= $check_in_timestamp ) {
			$this->logger->log_validation_error( 'check_out', 'Check-out date must be after check-in date', array( 'check_in' => $check_in, 'check_out' => $check_out ) );
			return new WP_Error(
				'agoda_invalid_date_range',
				__( 'Check-out date must be after check-in date.', 'agoda-booking' )
			);
		}

		// Optional: Check for very long stays (>30 days)
		$days_diff = ( $check_out_timestamp - $check_in_timestamp ) / DAY_IN_SECONDS;
		if ( $days_diff > 30 ) {
			// Allow but warn (not an error, just a notice)
			// This can be handled by the calling function if needed
		}

		return true;
	}

	/**
	 * Validate occupancy.
	 *
	 * @param int   $adults Number of adults.
	 * @param int   $children Number of children.
	 * @param array $children_ages Children ages array.
	 * @return bool|WP_Error True if valid, WP_Error otherwise.
	 */
	public function validate_occupancy( $adults, $children, $children_ages = array() ) {
		// Validate adults
		$adults = absint( $adults );
		if ( $adults < 1 ) {
			$this->logger->log_validation_error( 'adults', 'Number of adults must be at least 1', $adults );
			return new WP_Error(
				'agoda_invalid_adults',
				__( 'Number of adults must be at least 1.', 'agoda-booking' )
			);
		}

		// Validate children
		$children = absint( $children );
		if ( $children < 0 ) {
			return new WP_Error(
				'agoda_invalid_children',
				__( 'Number of children cannot be negative.', 'agoda-booking' )
			);
		}

		// Validate children ages if provided
		if ( $children > 0 ) {
			if ( ! is_array( $children_ages ) ) {
				return new WP_Error(
					'agoda_invalid_children_ages',
					__( 'Children ages must be an array.', 'agoda-booking' )
				);
			}

			// Children ages array length must match number of children
			if ( count( $children_ages ) !== $children ) {
				return new WP_Error(
					'agoda_children_ages_mismatch',
					sprintf(
						/* translators: 1: Number of children, 2: Number of ages provided */
						__( 'Number of children ages (%1$d) must match number of children (%2$d).', 'agoda-booking' ),
						count( $children_ages ),
						$children
					)
				);
			}

			// Validate each age (should be between 0 and 17)
			foreach ( $children_ages as $age ) {
				$age = absint( $age );
				if ( $age < 0 || $age > 17 ) {
					return new WP_Error(
						'agoda_invalid_child_age',
						sprintf(
							/* translators: %d: Child age */
							__( 'Child age must be between 0 and 17. Invalid age: %d', 'agoda-booking' ),
							$age
						)
					);
				}
			}
		}

		return true;
	}

	/**
	 * Validate city ID.
	 *
	 * @param mixed $city_id City ID.
	 * @return bool|WP_Error True if valid, WP_Error otherwise.
	 */
	public function validate_city_id( $city_id ) {
		if ( empty( $city_id ) ) {
			return new WP_Error(
				'agoda_empty_city_id',
				__( 'City ID is required.', 'agoda-booking' )
			);
		}

		if ( ! is_numeric( $city_id ) ) {
			return new WP_Error(
				'agoda_invalid_city_id',
				__( 'City ID must be a number.', 'agoda-booking' )
			);
		}

		$city_id = absint( $city_id );
		if ( $city_id <= 0 ) {
			return new WP_Error(
				'agoda_invalid_city_id',
				__( 'City ID must be a positive number.', 'agoda-booking' )
			);
		}

		return true;
	}

	/**
	 * Validate hotel ID list.
	 *
	 * @param mixed $hotel_ids Hotel ID array.
	 * @return bool|WP_Error True if valid, WP_Error otherwise.
	 */
	public function validate_hotel_ids( $hotel_ids ) {
		if ( empty( $hotel_ids ) ) {
			return new WP_Error(
				'agoda_empty_hotel_ids',
				__( 'Hotel ID list is required.', 'agoda-booking' )
			);
		}

		if ( ! is_array( $hotel_ids ) ) {
			return new WP_Error(
				'agoda_invalid_hotel_ids',
				__( 'Hotel IDs must be an array.', 'agoda-booking' )
			);
		}

		if ( empty( $hotel_ids ) ) {
			return new WP_Error(
				'agoda_empty_hotel_ids',
				__( 'At least one hotel ID is required.', 'agoda-booking' )
			);
		}

		// Validate each hotel ID
		foreach ( $hotel_ids as $hotel_id ) {
			if ( ! is_numeric( $hotel_id ) ) {
				return new WP_Error(
					'agoda_invalid_hotel_id',
					sprintf(
						/* translators: %s: Invalid hotel ID */
						__( 'All hotel IDs must be numbers. Invalid ID: %s', 'agoda-booking' ),
						esc_html( $hotel_id )
					)
				);
			}

			$hotel_id = absint( $hotel_id );
			if ( $hotel_id <= 0 ) {
				return new WP_Error(
					'agoda_invalid_hotel_id',
					__( 'All hotel IDs must be positive numbers.', 'agoda-booking' )
				);
			}
		}

		return true;
	}

	/**
	 * Sanitize input.
	 *
	 * @param mixed $input Input to sanitize.
	 * @return mixed Sanitized input.
	 */
	public function sanitize_input( $input ) {
		if ( is_array( $input ) ) {
			return array_map( array( $this, 'sanitize_input' ), $input );
		}

		if ( is_string( $input ) ) {
			return sanitize_text_field( $input );
		}

		if ( is_int( $input ) ) {
			return absint( $input );
		}

		if ( is_float( $input ) ) {
			return floatval( $input );
		}

		if ( is_bool( $input ) ) {
			return (bool) $input;
		}

		return $input;
	}

	/**
	 * Sanitize date string.
	 *
	 * @param string $date Date string.
	 * @return string Sanitized date string.
	 */
	public function sanitize_date( $date ) {
		$date = sanitize_text_field( $date );
		
		// Validate format YYYY-MM-DD
		if ( preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ) {
			return $date;
		}

		return '';
	}

	/**
	 * Sanitize number.
	 *
	 * @param mixed $number Number to sanitize.
	 * @return int|float Sanitized number.
	 */
	public function sanitize_number( $number, $type = 'int' ) {
		if ( 'float' === $type || 'decimal' === $type ) {
			return floatval( $number );
		}

		return absint( $number );
	}

	/**
	 * Sanitize array of numbers.
	 *
	 * @param array $numbers Array of numbers.
	 * @return array Sanitized array of numbers.
	 */
	public function sanitize_number_array( $numbers ) {
		if ( ! is_array( $numbers ) ) {
			return array();
		}

		return array_map( 'absint', $numbers );
	}

	/**
	 * Check if date format is valid (YYYY-MM-DD).
	 *
	 * @param string $date Date string.
	 * @return bool True if valid format, false otherwise.
	 */
	private function is_valid_date_format( $date ) {
		if ( ! is_string( $date ) ) {
			return false;
		}

		// Check format YYYY-MM-DD
		if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ) {
			return false;
		}

		// Check if date is valid (e.g., not 2023-13-45)
		$date_parts = explode( '-', $date );
		if ( count( $date_parts ) !== 3 ) {
			return false;
		}

		$year = absint( $date_parts[0] );
		$month = absint( $date_parts[1] );
		$day = absint( $date_parts[2] );

		return checkdate( $month, $day, $year );
	}

	/**
	 * Validate and sanitize search parameters.
	 *
	 * @param array $params Search parameters.
	 * @return array|WP_Error Sanitized parameters or WP_Error.
	 */
	public function validate_and_sanitize_search_params( $params ) {
		$sanitized = array();

		// Validate and sanitize dates
		if ( isset( $params['checkInDate'] ) && isset( $params['checkOutDate'] ) ) {
			$check_in = $this->sanitize_date( $params['checkInDate'] );
			$check_out = $this->sanitize_date( $params['checkOutDate'] );

			$date_validation = $this->validate_dates( $check_in, $check_out );
			if ( is_wp_error( $date_validation ) ) {
				return $date_validation;
			}

			$sanitized['checkInDate'] = $check_in;
			$sanitized['checkOutDate'] = $check_out;
		}

		// Validate and sanitize occupancy
		if ( isset( $params['occupancy'] ) && is_array( $params['occupancy'] ) ) {
			$adults = isset( $params['occupancy']['numberOfAdult'] ) ? absint( $params['occupancy']['numberOfAdult'] ) : 2;
			$children = isset( $params['occupancy']['numberOfChildren'] ) ? absint( $params['occupancy']['numberOfChildren'] ) : 0;
			$children_ages = isset( $params['occupancy']['childrenAges'] ) ? $this->sanitize_number_array( $params['occupancy']['childrenAges'] ) : array();

			$occupancy_validation = $this->validate_occupancy( $adults, $children, $children_ages );
			if ( is_wp_error( $occupancy_validation ) ) {
				return $occupancy_validation;
			}

			$sanitized['occupancy'] = array(
				'numberOfAdult'    => $adults,
				'numberOfChildren' => $children,
			);

			if ( ! empty( $children_ages ) ) {
				$sanitized['occupancy']['childrenAges'] = $children_ages;
			}
		}

		// Validate and sanitize city ID
		if ( isset( $params['cityId'] ) ) {
			$city_validation = $this->validate_city_id( $params['cityId'] );
			if ( is_wp_error( $city_validation ) ) {
				return $city_validation;
			}
			$sanitized['cityId'] = absint( $params['cityId'] );
		}

		// Validate and sanitize hotel IDs
		if ( isset( $params['hotelId'] ) ) {
			$hotel_validation = $this->validate_hotel_ids( $params['hotelId'] );
			if ( is_wp_error( $hotel_validation ) ) {
				return $hotel_validation;
			}
			$sanitized['hotelId'] = $this->sanitize_number_array( $params['hotelId'] );
		}

		// Sanitize optional parameters
		if ( isset( $params['language'] ) ) {
			$sanitized['language'] = sanitize_text_field( $params['language'] );
		}

		if ( isset( $params['currency'] ) ) {
			$sanitized['currency'] = strtoupper( sanitize_text_field( $params['currency'] ) );
		}

		// Sanitize additional parameters (for city search)
		if ( isset( $params['additional'] ) && is_array( $params['additional'] ) ) {
			$sanitized['additional'] = $this->sanitize_input( $params['additional'] );
		}

		return $sanitized;
	}
}
