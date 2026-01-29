<?php
/**
 * Statistics & Analytics page template
 *
 * @package Agoda_Booking
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Check user capabilities
if ( ! current_user_can( 'manage_options' ) ) {
	return;
}

// Load statistics class
if ( ! class_exists( 'Agoda_Statistics' ) ) {
	require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-statistics.php';
}
$statistics = new Agoda_Statistics();

// Get statistics for different periods
$stats_all = $statistics->get_search_statistics( 'all' );
$stats_month = $statistics->get_search_statistics( 'month' );
$stats_week = $statistics->get_search_statistics( 'week' );
$stats_day = $statistics->get_search_statistics( 'day' );

// Get popular cities and hotels
$popular_cities = $statistics->get_popular_cities( 10 );
$popular_hotels = $statistics->get_popular_hotels( 10 );

// Get performance metrics
$performance = $statistics->get_performance_metrics();
?>

<div class="wrap agoda-booking-statistics">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	
	<p class="description">
		<?php esc_html_e( 'View usage statistics and performance metrics for your Agoda Booking plugin.', 'agoda-booking' ); ?>
	</p>

	<div class="agoda-statistics-container">
		<!-- Period Selector -->
		<div class="agoda-statistics-period">
			<label for="agoda-statistics-period-select">
				<strong><?php esc_html_e( 'Period:', 'agoda-booking' ); ?></strong>
			</label>
			<select id="agoda-statistics-period-select" class="regular-text">
				<option value="day"><?php esc_html_e( 'Last 24 Hours', 'agoda-booking' ); ?></option>
				<option value="week"><?php esc_html_e( 'Last 7 Days', 'agoda-booking' ); ?></option>
				<option value="month"><?php esc_html_e( 'Last 30 Days', 'agoda-booking' ); ?></option>
				<option value="all" selected><?php esc_html_e( 'All Time', 'agoda-booking' ); ?></option>
			</select>
		</div>

		<!-- Usage Statistics -->
		<div class="agoda-statistics-section">
			<h2><?php esc_html_e( 'Usage Statistics', 'agoda-booking' ); ?></h2>
			
			<div class="agoda-stats-grid">
				<div class="agoda-stat-card">
					<div class="agoda-stat-label"><?php esc_html_e( 'Total Searches', 'agoda-booking' ); ?></div>
					<div class="agoda-stat-value" id="stat-total-searches">
						<?php echo esc_html( $stats_all['total_searches'] ); ?>
					</div>
				</div>
				
				<div class="agoda-stat-card">
					<div class="agoda-stat-label"><?php esc_html_e( 'Successful Searches', 'agoda-booking' ); ?></div>
					<div class="agoda-stat-value" id="stat-successful-searches">
						<?php echo esc_html( $stats_all['successful_searches'] ); ?>
					</div>
				</div>
				
				<div class="agoda-stat-card">
					<div class="agoda-stat-label"><?php esc_html_e( 'Failed Searches', 'agoda-booking' ); ?></div>
					<div class="agoda-stat-value" id="stat-failed-searches">
						<?php echo esc_html( $stats_all['failed_searches'] ); ?>
					</div>
				</div>
				
				<div class="agoda-stat-card">
					<div class="agoda-stat-label"><?php esc_html_e( 'Cached Searches', 'agoda-booking' ); ?></div>
					<div class="agoda-stat-value" id="stat-cached-searches">
						<?php echo esc_html( $stats_all['cached_searches'] ); ?>
					</div>
				</div>
				
				<div class="agoda-stat-card">
					<div class="agoda-stat-label"><?php esc_html_e( 'Avg Hotels Found', 'agoda-booking' ); ?></div>
					<div class="agoda-stat-value" id="stat-avg-hotels">
						<?php echo esc_html( $stats_all['average_hotels_found'] ); ?>
					</div>
				</div>
			</div>

			<!-- Searches Chart -->
			<div class="agoda-chart-container">
				<h3><?php esc_html_e( 'Searches Over Time', 'agoda-booking' ); ?></h3>
				<canvas id="agoda-searches-chart" width="400" height="200"></canvas>
			</div>
		</div>

		<!-- Performance Metrics -->
		<div class="agoda-statistics-section">
			<h2><?php esc_html_e( 'Performance Metrics', 'agoda-booking' ); ?></h2>
			
			<div class="agoda-stats-grid">
				<div class="agoda-stat-card">
					<div class="agoda-stat-label"><?php esc_html_e( 'Avg Response Time', 'agoda-booking' ); ?></div>
					<div class="agoda-stat-value">
						<?php echo esc_html( $performance['average_response_time'] ); ?>s
					</div>
				</div>
				
				<div class="agoda-stat-card">
					<div class="agoda-stat-label"><?php esc_html_e( 'Min Response Time', 'agoda-booking' ); ?></div>
					<div class="agoda-stat-value">
						<?php echo esc_html( $performance['min_response_time'] ); ?>s
					</div>
				</div>
				
				<div class="agoda-stat-card">
					<div class="agoda-stat-label"><?php esc_html_e( 'Max Response Time', 'agoda-booking' ); ?></div>
					<div class="agoda-stat-value">
						<?php echo esc_html( $performance['max_response_time'] ); ?>s
					</div>
				</div>
				
				<div class="agoda-stat-card">
					<div class="agoda-stat-label"><?php esc_html_e( 'Cache Hit Rate', 'agoda-booking' ); ?></div>
					<div class="agoda-stat-value">
						<?php echo esc_html( $performance['cache_hit_rate'] ); ?>%
					</div>
				</div>
				
				<div class="agoda-stat-card">
					<div class="agoda-stat-label"><?php esc_html_e( 'Error Rate', 'agoda-booking' ); ?></div>
					<div class="agoda-stat-value">
						<?php echo esc_html( $performance['error_rate'] ); ?>%
					</div>
				</div>
			</div>

			<!-- Response Time Chart -->
			<div class="agoda-chart-container">
				<h3><?php esc_html_e( 'Response Time Trend', 'agoda-booking' ); ?></h3>
				<canvas id="agoda-response-time-chart" width="400" height="200"></canvas>
			</div>
		</div>

		<!-- Popular Cities & Hotels -->
		<div class="agoda-statistics-section">
			<div class="agoda-popular-grid">
				<div class="agoda-popular-cities">
					<h2><?php esc_html_e( 'Popular Cities', 'agoda-booking' ); ?></h2>
					<?php if ( ! empty( $popular_cities ) ) : ?>
						<table class="wp-list-table widefat fixed striped">
							<thead>
								<tr>
									<th><?php esc_html_e( 'City ID', 'agoda-booking' ); ?></th>
									<th><?php esc_html_e( 'Search Count', 'agoda-booking' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ( $popular_cities as $city ) : ?>
									<tr>
										<td><?php echo esc_html( $city['city_id'] ); ?></td>
										<td><?php echo esc_html( $city['search_count'] ); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					<?php else : ?>
						<p><?php esc_html_e( 'No city search data available.', 'agoda-booking' ); ?></p>
					<?php endif; ?>
				</div>

				<div class="agoda-popular-hotels">
					<h2><?php esc_html_e( 'Popular Hotels', 'agoda-booking' ); ?></h2>
					<?php if ( ! empty( $popular_hotels ) ) : ?>
						<table class="wp-list-table widefat fixed striped">
							<thead>
								<tr>
									<th><?php esc_html_e( 'Hotel ID', 'agoda-booking' ); ?></th>
									<th><?php esc_html_e( 'Hotel Name', 'agoda-booking' ); ?></th>
									<th><?php esc_html_e( 'Search Count', 'agoda-booking' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ( $popular_hotels as $hotel ) : ?>
									<tr>
										<td><?php echo esc_html( $hotel['hotel_id'] ); ?></td>
										<td><?php echo esc_html( $hotel['hotel_name'] ); ?></td>
										<td><?php echo esc_html( $hotel['search_count'] ); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					<?php else : ?>
						<p><?php esc_html_e( 'No hotel search data available.', 'agoda-booking' ); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<!-- Actions -->
		<div class="agoda-statistics-actions">
			<button type="button" id="agoda-refresh-statistics" class="button">
				<?php esc_html_e( 'Refresh Statistics', 'agoda-booking' ); ?>
			</button>
			<button type="button" id="agoda-export-statistics" class="button">
				<?php esc_html_e( 'Export Statistics', 'agoda-booking' ); ?>
			</button>
			<button type="button" id="agoda-clear-statistics" class="button button-secondary">
				<?php esc_html_e( 'Clear Statistics', 'agoda-booking' ); ?>
			</button>
		</div>
	</div>
</div>
