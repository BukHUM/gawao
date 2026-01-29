<?php
/**
 * The security helper class.
 *
 * @package Agoda_Booking
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The security helper class.
 */
class Agoda_Security {

	/**
	 * Encrypt sensitive data (if encryption is available).
	 *
	 * @param string $data Data to encrypt.
	 * @return string Encrypted data or original data if encryption not available.
	 */
	public static function encrypt( $data ) {
		// Check if encryption functions are available
		if ( ! function_exists( 'openssl_encrypt' ) ) {
			// If OpenSSL is not available, return original data
			// In production, you might want to use a different encryption method
			return $data;
		}

		// Get encryption key from WordPress salts
		$key = self::get_encryption_key();

		// Encrypt data
		$iv_length = openssl_cipher_iv_length( 'AES-256-CBC' );
		$iv = openssl_random_pseudo_bytes( $iv_length );
		$encrypted = openssl_encrypt( $data, 'AES-256-CBC', $key, 0, $iv );

		// Return base64 encoded IV + encrypted data
		return base64_encode( $iv . $encrypted );
	}

	/**
	 * Decrypt sensitive data.
	 *
	 * @param string $encrypted_data Encrypted data.
	 * @return string Decrypted data or original data if decryption fails.
	 */
	public static function decrypt( $encrypted_data ) {
		// Check if encryption functions are available
		if ( ! function_exists( 'openssl_decrypt' ) ) {
			return $encrypted_data;
		}

		// Get encryption key
		$key = self::get_encryption_key();

		// Decode base64
		$data = base64_decode( $encrypted_data, true );
		if ( false === $data ) {
			return $encrypted_data; // Return original if decode fails
		}

		// Extract IV and encrypted data
		$iv_length = openssl_cipher_iv_length( 'AES-256-CBC' );
		$iv = substr( $data, 0, $iv_length );
		$encrypted = substr( $data, $iv_length );

		// Decrypt
		$decrypted = openssl_decrypt( $encrypted, 'AES-256-CBC', $key, 0, $iv );

		return false !== $decrypted ? $decrypted : $encrypted_data;
	}

	/**
	 * Get encryption key from WordPress salts.
	 *
	 * @return string Encryption key.
	 */
	private static function get_encryption_key() {
		// Use WordPress AUTH_KEY and SECURE_AUTH_KEY if available
		$key = '';
		if ( defined( 'AUTH_KEY' ) && defined( 'SECURE_AUTH_KEY' ) ) {
			$key = AUTH_KEY . SECURE_AUTH_KEY;
		} else {
			// Fallback to a site-specific key
			$key = get_option( 'agoda_encryption_key', '' );
			if ( empty( $key ) ) {
				$key = wp_generate_password( 64, true, true );
				update_option( 'agoda_encryption_key', $key );
			}
		}

		// Hash to get 32-byte key for AES-256
		return hash( 'sha256', $key, true );
	}

	/**
	 * Verify nonce for AJAX requests.
	 *
	 * @param string $action Action name.
	 * @param string $nonce Nonce value.
	 * @return bool True if valid, false otherwise.
	 */
	public static function verify_ajax_nonce( $action, $nonce ) {
		if ( empty( $nonce ) ) {
			return false;
		}

		return wp_verify_nonce( sanitize_text_field( wp_unslash( $nonce ) ), $action );
	}

	/**
	 * Verify user capability.
	 *
	 * @param string $capability Capability to check.
	 * @return bool True if user has capability, false otherwise.
	 */
	public static function verify_capability( $capability = 'manage_options' ) {
		return current_user_can( $capability );
	}

	/**
	 * Sanitize and validate input array.
	 *
	 * @param array  $input Input array.
	 * @param array  $schema Schema defining validation rules.
	 * @return array|WP_Error Sanitized array or WP_Error.
	 */
	public static function sanitize_input_array( $input, $schema ) {
		$sanitized = array();

		foreach ( $schema as $field => $rules ) {
			$value = isset( $input[ $field ] ) ? $input[ $field ] : null;

			// Check required
			if ( isset( $rules['required'] ) && $rules['required'] && empty( $value ) ) {
				return new WP_Error(
					'agoda_required_field',
					sprintf(
						/* translators: %s: Field name */
						__( 'Field "%s" is required.', 'agoda-booking' ),
						$field
					)
				);
			}

			// Skip if not required and empty
			if ( empty( $value ) && ( ! isset( $rules['required'] ) || ! $rules['required'] ) ) {
				if ( isset( $rules['default'] ) ) {
					$sanitized[ $field ] = $rules['default'];
				}
				continue;
			}

			// Sanitize based on type
			if ( isset( $rules['type'] ) ) {
				switch ( $rules['type'] ) {
					case 'string':
						$value = sanitize_text_field( $value );
						break;

					case 'email':
						$value = sanitize_email( $value );
						if ( ! is_email( $value ) ) {
							return new WP_Error(
								'agoda_invalid_email',
								sprintf(
									/* translators: %s: Field name */
									__( 'Invalid email format for field "%s".', 'agoda-booking' ),
									$field
								)
							);
						}
						break;

					case 'int':
					case 'integer':
						$value = absint( $value );
						break;

					case 'float':
					case 'decimal':
						$value = floatval( $value );
						break;

					case 'url':
						$value = esc_url_raw( $value );
						break;

					case 'array':
						if ( ! is_array( $value ) ) {
							return new WP_Error(
								'agoda_invalid_array',
								sprintf(
									/* translators: %s: Field name */
									__( 'Field "%s" must be an array.', 'agoda-booking' ),
									$field
								)
							);
						}
						if ( isset( $rules['items'] ) ) {
							$value = array_map( function( $item ) use ( $rules ) {
								return self::sanitize_value( $item, $rules['items'] );
							}, $value );
						}
						break;

					case 'bool':
					case 'boolean':
						$value = (bool) $value;
						break;
				}
			}

			// Additional validation
			if ( isset( $rules['validate'] ) && is_callable( $rules['validate'] ) ) {
				$validation_result = call_user_func( $rules['validate'], $value );
				if ( is_wp_error( $validation_result ) ) {
					return $validation_result;
				}
			}

			$sanitized[ $field ] = $value;
		}

		return $sanitized;
	}

	/**
	 * Sanitize a single value.
	 *
	 * @param mixed  $value Value to sanitize.
	 * @param string $type Type of value.
	 * @return mixed Sanitized value.
	 */
	private static function sanitize_value( $value, $type ) {
		switch ( $type ) {
			case 'string':
				return sanitize_text_field( $value );
			case 'int':
				return absint( $value );
			case 'float':
				return floatval( $value );
			case 'bool':
				return (bool) $value;
			default:
				return $value;
		}
	}

	/**
	 * Escape output for display.
	 *
	 * @param mixed  $output Output to escape.
	 * @param string $type Type of output (html, url, attr, js, etc.).
	 * @return mixed Escaped output.
	 */
	public static function escape_output( $output, $type = 'html' ) {
		if ( is_array( $output ) ) {
			return array_map( function( $item ) use ( $type ) {
				return self::escape_output( $item, $type );
			}, $output );
		}

		if ( is_string( $output ) ) {
			switch ( $type ) {
				case 'html':
					return esc_html( $output );
				case 'attr':
					return esc_attr( $output );
				case 'url':
					return esc_url( $output );
				case 'js':
					return esc_js( $output );
				case 'textarea':
					return esc_textarea( $output );
				default:
					return esc_html( $output );
			}
		}

		return $output;
	}

	/**
	 * Check if API credentials are exposed in frontend.
	 *
	 * @return bool True if safe, false if exposed.
	 */
	public static function check_credentials_exposure() {
		// Check if credentials are in JavaScript
		$scripts = wp_scripts()->registered;
		foreach ( $scripts as $handle => $script ) {
			if ( isset( $script->extra['data'] ) ) {
				$data = $script->extra['data'];
				if ( strpos( $data, 'agoda_site_id' ) !== false || strpos( $data, 'agoda_api_key' ) !== false ) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Rate limit check (simple implementation).
	 *
	 * @param string $identifier User identifier (IP address or user ID).
	 * @param int    $max_requests Maximum requests allowed.
	 * @param int    $time_window Time window in seconds.
	 * @return bool|WP_Error True if allowed, WP_Error if rate limited.
	 */
	public static function check_rate_limit( $identifier, $max_requests = 10, $time_window = 60 ) {
		$transient_key = 'agoda_rate_limit_' . md5( $identifier );
		$requests = get_transient( $transient_key );

		if ( false === $requests ) {
			$requests = 0;
		}

		if ( $requests >= $max_requests ) {
			return new WP_Error(
				'agoda_rate_limit_exceeded',
				sprintf(
					/* translators: %d: Number of seconds */
					__( 'Rate limit exceeded. Please try again in %d seconds.', 'agoda-booking' ),
					$time_window
				),
				array(
					'retry_after' => $time_window,
				)
			);
		}

		// Increment request count
		set_transient( $transient_key, $requests + 1, $time_window );

		return true;
	}

	/**
	 * Get user identifier for rate limiting.
	 *
	 * @return string User identifier.
	 */
	public static function get_user_identifier() {
		// Try to get user ID first
		if ( is_user_logged_in() ) {
			return 'user_' . get_current_user_id();
		}

		// Fallback to IP address
		$ip = self::get_client_ip();
		return 'ip_' . md5( $ip );
	}

	/**
	 * Get client IP address.
	 *
	 * @return string IP address.
	 */
	private static function get_client_ip() {
		$ip_keys = array(
			'HTTP_CF_CONNECTING_IP', // Cloudflare
			'HTTP_X_REAL_IP',
			'HTTP_X_FORWARDED_FOR',
			'REMOTE_ADDR',
		);

		foreach ( $ip_keys as $key ) {
			if ( isset( $_SERVER[ $key ] ) && ! empty( $_SERVER[ $key ] ) ) {
				$ip = sanitize_text_field( wp_unslash( $_SERVER[ $key ] ) );
				// Handle comma-separated IPs (from proxies)
				if ( strpos( $ip, ',' ) !== false ) {
					$ip = explode( ',', $ip );
					$ip = trim( $ip[0] );
				}
				if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
					return $ip;
				}
			}
		}

		return '0.0.0.0';
	}
}
