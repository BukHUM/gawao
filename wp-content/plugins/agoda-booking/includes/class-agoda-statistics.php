<?php
/**
 * Statistics and Analytics class.
 *
 * @package Agoda_Booking
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Statistics and Analytics class.
 */
class Agoda_Statistics {

	/**
	 * Track a search event.
	 *
	 * @param array $params Search parameters.
	 * @param array $result Search result.
	 * @param float $response_time Response time in seconds.
	 * @return void
	 */
	public function track_search( $params, $result, $response_time = 0 ) {
		$search_data = array(
			'timestamp'     => current_time( 'mysql' ),
			'date'          => current_time( 'Y-m-d' ),
			'city_id'       => isset( $params['cityId'] ) ? $params['cityId'] : null,
			'hotel_ids'     => isset( $params['hotelId'] ) ? $params['hotelId'] : null,
			'check_in'      => isset( $params['checkInDate'] ) ? $params['checkInDate'] : null,
			'check_out'     => isset( $params['checkOutDate'] ) ? $params['checkOutDate'] : null,
			'hotel_count'   => isset( $result['count'] ) ? $result['count'] : 0,
			'response_time' => $response_time,
			'cached'        => isset( $result['cached'] ) ? $result['cached'] : false,
			'error'          => is_wp_error( $result ) ? true : false,
		);

		// Store in options (simple approach - could be improved with custom table)
		$searches = get_option( 'agoda_search_statistics', array() );
		$searches[] = $search_data;

		// Keep only last 1000 searches to prevent database bloat
		if ( count( $searches ) > 1000 ) {
			$searches = array_slice( $searches, -1000 );
		}

		update_option( 'agoda_search_statistics', $searches );

		// Track popular cities
		if ( isset( $params['cityId'] ) ) {
			$this->track_popular_city( $params['cityId'] );
		}

		// Track popular hotels
		if ( isset( $result['results'] ) && is_array( $result['results'] ) ) {
			foreach ( $result['results'] as $hotel ) {
				if ( isset( $hotel['hotelId'] ) ) {
					$this->track_popular_hotel( $hotel['hotelId'], $hotel['hotelName'] ?? '' );
				}
			}
		}

		// Track response time
		if ( $response_time > 0 ) {
			$this->track_response_time( $response_time );
		}
	}

	/**
	 * Track popular city.
	 *
	 * @param int $city_id City ID.
	 * @return void
	 */
	private function track_popular_city( $city_id ) {
		$popular_cities = get_option( 'agoda_popular_cities', array() );
		
		if ( ! isset( $popular_cities[ $city_id ] ) ) {
			$popular_cities[ $city_id ] = 0;
		}
		
		$popular_cities[ $city_id ]++;
		update_option( 'agoda_popular_cities', $popular_cities );
	}

	/**
	 * Track popular hotel.
	 *
	 * @param int    $hotel_id Hotel ID.
	 * @param string $hotel_name Hotel name.
	 * @return void
	 */
	private function track_popular_hotel( $hotel_id, $hotel_name = '' ) {
		$popular_hotels = get_option( 'agoda_popular_hotels', array() );
		
		if ( ! isset( $popular_hotels[ $hotel_id ] ) ) {
			$popular_hotels[ $hotel_id ] = array(
				'count' => 0,
				'name'  => $hotel_name,
			);
		}
		
		$popular_hotels[ $hotel_id ]['count']++;
		if ( ! empty( $hotel_name ) ) {
			$popular_hotels[ $hotel_id ]['name'] = $hotel_name;
		}
		
		update_option( 'agoda_popular_hotels', $popular_hotels );
	}

	/**
	 * Track response time.
	 *
	 * @param float $response_time Response time in seconds.
	 * @return void
	 */
	private function track_response_time( $response_time ) {
		$response_times = get_option( 'agoda_response_times', array() );
		$response_times[] = array(
			'timestamp' => current_time( 'mysql' ),
			'time'      => $response_time,
		);

		// Keep only last 500 response times
		if ( count( $response_times ) > 500 ) {
			$response_times = array_slice( $response_times, -500 );
		}

		update_option( 'agoda_response_times', $response_times );
	}

	/**
	 * Get search statistics.
	 *
	 * @param string $period Period: 'day', 'week', 'month', 'all'.
	 * @return array Statistics data.
	 */
	public function get_search_statistics( $period = 'all' ) {
		$searches = get_option( 'agoda_search_statistics', array() );
		
		if ( empty( $searches ) ) {
			return array(
				'total_searches' => 0,
				'successful_searches' => 0,
				'failed_searches' => 0,
				'cached_searches' => 0,
				'average_hotels_found' => 0,
				'searches_by_date' => array(),
			);
		}

		// Filter by period
		$filtered_searches = $this->filter_by_period( $searches, $period );

		$total = count( $filtered_searches );
		$successful = 0;
		$failed = 0;
		$cached = 0;
		$total_hotels = 0;
		$searches_by_date = array();

		foreach ( $filtered_searches as $search ) {
			if ( isset( $search['error'] ) && $search['error'] ) {
				$failed++;
			} else {
				$successful++;
			}

			if ( isset( $search['cached'] ) && $search['cached'] ) {
				$cached++;
			}

			if ( isset( $search['hotel_count'] ) ) {
				$total_hotels += $search['hotel_count'];
			}

			// Group by date
			$date = isset( $search['date'] ) ? $search['date'] : date( 'Y-m-d', strtotime( $search['timestamp'] ) );
			if ( ! isset( $searches_by_date[ $date ] ) ) {
				$searches_by_date[ $date ] = 0;
			}
			$searches_by_date[ $date ]++;
		}

		// Sort by date
		ksort( $searches_by_date );

		return array(
			'total_searches'      => $total,
			'successful_searches' => $successful,
			'failed_searches'     => $failed,
			'cached_searches'     => $cached,
			'average_hotels_found' => $total > 0 ? round( $total_hotels / $total, 2 ) : 0,
			'searches_by_date'     => $searches_by_date,
		);
	}

	/**
	 * Get popular cities.
	 *
	 * @param int $limit Number of cities to return.
	 * @return array Popular cities.
	 */
	public function get_popular_cities( $limit = 10 ) {
		$popular_cities = get_option( 'agoda_popular_cities', array() );
		
		arsort( $popular_cities );
		
		$result = array();
		$count = 0;
		foreach ( $popular_cities as $city_id => $search_count ) {
			if ( $count >= $limit ) {
				break;
			}
			$result[] = array(
				'city_id'     => $city_id,
				'search_count' => $search_count,
			);
			$count++;
		}
		
		return $result;
	}

	/**
	 * Get popular hotels.
	 *
	 * @param int $limit Number of hotels to return.
	 * @return array Popular hotels.
	 */
	public function get_popular_hotels( $limit = 10 ) {
		$popular_hotels = get_option( 'agoda_popular_hotels', array() );
		
		// Sort by count
		uasort( $popular_hotels, function( $a, $b ) {
			return $b['count'] - $a['count'];
		});
		
		$result = array();
		$count = 0;
		foreach ( $popular_hotels as $hotel_id => $data ) {
			if ( $count >= $limit ) {
				break;
			}
			$result[] = array(
				'hotel_id'     => $hotel_id,
				'hotel_name'   => isset( $data['name'] ) ? $data['name'] : '',
				'search_count' => isset( $data['count'] ) ? $data['count'] : 0,
			);
			$count++;
		}
		
		return $result;
	}

	/**
	 * Get performance metrics.
	 *
	 * @return array Performance metrics.
	 */
	public function get_performance_metrics() {
		$response_times = get_option( 'agoda_response_times', array() );
		
		if ( empty( $response_times ) ) {
			return array(
				'average_response_time' => 0,
				'min_response_time'    => 0,
				'max_response_time'    => 0,
				'total_requests'      => 0,
			);
		}

		$times = array_column( $response_times, 'time' );
		$total = count( $times );
		$average = $total > 0 ? array_sum( $times ) / $total : 0;
		$min = $total > 0 ? min( $times ) : 0;
		$max = $total > 0 ? max( $times ) : 0;

		// Get cache hit rate
		$searches = get_option( 'agoda_search_statistics', array() );
		$total_searches = count( $searches );
		$cached_searches = 0;
		foreach ( $searches as $search ) {
			if ( isset( $search['cached'] ) && $search['cached'] ) {
				$cached_searches++;
			}
		}
		$cache_hit_rate = $total_searches > 0 ? round( ( $cached_searches / $total_searches ) * 100, 2 ) : 0;

		// Get error rate
		$failed_searches = 0;
		foreach ( $searches as $search ) {
			if ( isset( $search['error'] ) && $search['error'] ) {
				$failed_searches++;
			}
		}
		$error_rate = $total_searches > 0 ? round( ( $failed_searches / $total_searches ) * 100, 2 ) : 0;

		return array(
			'average_response_time' => round( $average, 3 ),
			'min_response_time'    => round( $min, 3 ),
			'max_response_time'    => round( $max, 3 ),
			'total_requests'       => $total,
			'cache_hit_rate'       => $cache_hit_rate,
			'error_rate'           => $error_rate,
		);
	}

	/**
	 * Filter searches by period.
	 *
	 * @param array  $searches Search data.
	 * @param string $period Period: 'day', 'week', 'month', 'all'.
	 * @return array Filtered searches.
	 */
	private function filter_by_period( $searches, $period ) {
		if ( 'all' === $period ) {
			return $searches;
		}

		$cutoff_date = '';
		switch ( $period ) {
			case 'day':
				$cutoff_date = date( 'Y-m-d', strtotime( '-1 day' ) );
				break;
			case 'week':
				$cutoff_date = date( 'Y-m-d', strtotime( '-7 days' ) );
				break;
			case 'month':
				$cutoff_date = date( 'Y-m-d', strtotime( '-30 days' ) );
				break;
		}

		$filtered = array();
		foreach ( $searches as $search ) {
			$search_date = isset( $search['date'] ) ? $search['date'] : date( 'Y-m-d', strtotime( $search['timestamp'] ) );
			if ( $search_date >= $cutoff_date ) {
				$filtered[] = $search;
			}
		}

		return $filtered;
	}

	/**
	 * Clear statistics.
	 *
	 * @return bool True on success.
	 */
	public function clear_statistics() {
		delete_option( 'agoda_search_statistics' );
		delete_option( 'agoda_popular_cities' );
		delete_option( 'agoda_popular_hotels' );
		delete_option( 'agoda_response_times' );
		return true;
	}
}
