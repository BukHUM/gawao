<?php
/**
 * The caching class.
 *
 * @package Agoda_Booking
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The caching class.
 */
class Agoda_Cache {

	/**
	 * Get cached data.
	 *
	 * @param string $key Cache key.
	 * @return mixed|false Cached data or false if not found.
	 */
	public function get_cache( $key ) {
		// Check if caching is enabled
		$cache_duration = get_option( 'agoda_cache_duration', 3600 );
		if ( $cache_duration <= 0 ) {
			return false;
		}

		return get_transient( 'agoda_' . $key );
	}

	/**
	 * Set cache data.
	 *
	 * @param string $key Cache key.
	 * @param mixed  $data Data to cache.
	 * @param int    $duration Cache duration in seconds.
	 * @return bool True on success, false on failure.
	 */
	public function set_cache( $key, $data, $duration = null ) {
		// Check if caching is enabled
		if ( null === $duration ) {
			$duration = get_option( 'agoda_cache_duration', 3600 );
		}

		// Don't cache if duration is 0 or negative
		if ( $duration <= 0 ) {
			return false;
		}

		return set_transient( 'agoda_' . $key, $data, $duration );
	}

	/**
	 * Clear cache.
	 *
	 * @param string $key Cache key (optional, clears all if not provided).
	 * @return bool True on success, false on failure.
	 */
	public function clear_cache( $key = null ) {
		if ( $key ) {
			return delete_transient( 'agoda_' . $key );
		}

		// Clear all Agoda transients (cache and rate limit)
		global $wpdb;
		
		// Clear cache transients
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

		// Clear rate limit transients (optional - only if rate limiting is enabled)
		$rate_limit_enabled = get_option( 'agoda_rate_limit_enabled', false );
		if ( $rate_limit_enabled ) {
			$wpdb->query(
				$wpdb->prepare(
					"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
					$wpdb->esc_like( '_transient_agoda_rate_limit_' ) . '%'
				)
			);
			$wpdb->query(
				$wpdb->prepare(
					"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
					$wpdb->esc_like( '_transient_timeout_agoda_rate_limit_' ) . '%'
				)
			);
		}

		return true;
	}

	/**
	 * Generate cache key from search parameters.
	 *
	 * @param array $params Search parameters.
	 * @return string Cache key.
	 */
	public function generate_cache_key( $params ) {
		// Normalize parameters for consistent cache keys
		$normalized = array();
		
		// Sort keys for consistency
		ksort( $params );
		
		// Normalize nested arrays
		foreach ( $params as $key => $value ) {
			if ( is_array( $value ) ) {
				ksort( $value );
				$normalized[ $key ] = $value;
			} else {
				$normalized[ $key ] = $value;
			}
		}
		
		// Generate hash from normalized parameters
		return 'search_' . md5( wp_json_encode( $normalized ) );
	}

	/**
	 * Get cache statistics.
	 *
	 * @return array Cache statistics.
	 */
	public function get_cache_stats() {
		global $wpdb;
		
		$cache_count = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM {$wpdb->options} WHERE option_name LIKE %s AND option_name NOT LIKE %s",
				$wpdb->esc_like( '_transient_agoda_' ) . '%',
				$wpdb->esc_like( '_transient_timeout_agoda_' ) . '%'
			)
		);
		
		return array(
			'cache_count' => (int) $cache_count,
			'cache_enabled' => get_option( 'agoda_cache_duration', 3600 ) > 0,
			'cache_duration' => get_option( 'agoda_cache_duration', 3600 ),
		);
	}

	/**
	 * Get cache entries list.
	 *
	 * @param int $limit Maximum number of entries to return.
	 * @return array Array of cache entries with key, expires, and size.
	 */
	public function get_cache_entries( $limit = 20 ) {
		global $wpdb;
		
		$entries = array();
		
		// Get cache transients (exclude timeout entries)
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT option_name, option_value, autoload 
				FROM {$wpdb->options} 
				WHERE option_name LIKE %s 
				AND option_name NOT LIKE %s
				ORDER BY option_id DESC
				LIMIT %d",
				$wpdb->esc_like( '_transient_agoda_' ) . '%',
				$wpdb->esc_like( '_transient_timeout_agoda_' ) . '%',
				$limit
			),
			ARRAY_A
		);
		
		foreach ( $results as $row ) {
			// Extract cache key from option_name
			$key = str_replace( '_transient_agoda_', '', $row['option_name'] );
			
			// Get expiration time
			$timeout_key = '_transient_timeout_agoda_' . $key;
			$expires = get_option( $timeout_key, false );
			
			// Calculate size
			$size = strlen( maybe_serialize( $row['option_value'] ) );
			
			$entries[] = array(
				'key'     => $key,
				'expires' => $expires ? (int) $expires : false,
				'size'    => $size,
			);
		}
		
		return $entries;
	}

	/**
	 * Clear cache by pattern.
	 *
	 * @param string $pattern Pattern to match cache keys.
	 * @return int Number of cache entries cleared.
	 */
	public function clear_cache_by_pattern( $pattern ) {
		global $wpdb;
		
		if ( empty( $pattern ) ) {
			return $this->clear_cache() ? 1 : 0;
		}
		
		$count = 0;
		
		// Get matching cache keys
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT option_name FROM {$wpdb->options} 
				WHERE option_name LIKE %s 
				AND option_name NOT LIKE %s
				AND option_name LIKE %s",
				$wpdb->esc_like( '_transient_agoda_' ) . '%',
				$wpdb->esc_like( '_transient_timeout_agoda_' ) . '%',
				$wpdb->esc_like( '_transient_agoda_' ) . $wpdb->esc_like( $pattern ) . '%'
			),
			ARRAY_A
		);
		
		foreach ( $results as $row ) {
			$key = str_replace( '_transient_agoda_', '', $row['option_name'] );
			if ( $this->clear_cache( $key ) ) {
				$count++;
			}
		}
		
		return $count;
	}

	/**
	 * Clear expired cache entries.
	 *
	 * @return int Number of expired cache entries cleared.
	 */
	public function clear_expired_cache() {
		global $wpdb;
		
		$count = 0;
		$current_time = current_time( 'timestamp' );
		
		// Get all cache timeout entries
		$timeouts = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT option_name, option_value 
				FROM {$wpdb->options} 
				WHERE option_name LIKE %s",
				$wpdb->esc_like( '_transient_timeout_agoda_' ) . '%'
			),
			ARRAY_A
		);
		
		foreach ( $timeouts as $timeout ) {
			$expires = (int) $timeout['option_value'];
			
			// If expired, delete the cache entry
			if ( $expires < $current_time ) {
				$key = str_replace( '_transient_timeout_agoda_', '', $timeout['option_name'] );
				if ( $this->clear_cache( $key ) ) {
					$count++;
				}
			}
		}
		
		return $count;
	}

	/**
	 * Get cache entry value.
	 *
	 * @param string $key Cache key.
	 * @return mixed|false Cache value or false if not found.
	 */
	public function get_cache_entry( $key ) {
		return get_transient( 'agoda_' . $key );
	}
}
