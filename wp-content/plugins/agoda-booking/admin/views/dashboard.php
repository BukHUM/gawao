<?php
/**
 * Dashboard page template
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

// Dashboard statistics are passed from render_dashboard_page()
// $dashboard_stats is available here
?>

<div class="wrap agoda-booking-dashboard">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	
	<div class="agoda-dashboard-widgets">
		<!-- API Status Widget -->
		<div class="agoda-widget agoda-widget-api-status">
			<h2><?php esc_html_e( 'API Status', 'agoda-booking' ); ?></h2>
			<div class="agoda-widget-content">
				<div class="agoda-status-indicator">
					<span class="agoda-status-badge <?php echo $dashboard_stats['api_connected'] ? 'status-connected' : 'status-disconnected'; ?>">
						<?php echo $dashboard_stats['api_connected'] ? esc_html__( 'Connected', 'agoda-booking' ) : esc_html__( 'Disconnected', 'agoda-booking' ); ?>
					</span>
				</div>
				<div class="agoda-status-details">
					<p>
						<strong><?php esc_html_e( 'API Calls Today:', 'agoda-booking' ); ?></strong>
						<span class="agoda-stat-value"><?php echo esc_html( $dashboard_stats['api_calls_today'] ); ?></span>
					</p>
					<?php if ( $dashboard_stats['rate_limit_enabled'] ) : ?>
						<p>
							<strong><?php esc_html_e( 'Rate Limit:', 'agoda-booking' ); ?></strong>
							<span class="agoda-stat-value">
								<?php 
								printf(
									/* translators: %1$d: max requests, %2$d: time window in seconds */
									esc_html__( '%1$d requests per %2$d seconds', 'agoda-booking' ),
									$dashboard_stats['rate_limit_max'],
									$dashboard_stats['rate_limit_window']
								);
								?>
							</span>
						</p>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<!-- Quick Stats Widget -->
		<div class="agoda-widget agoda-widget-quick-stats">
			<h2><?php esc_html_e( 'Quick Stats', 'agoda-booking' ); ?></h2>
			<div class="agoda-widget-content">
				<div class="agoda-stats-grid">
					<div class="agoda-stat-item">
						<div class="agoda-stat-label"><?php esc_html_e( 'Cache Status', 'agoda-booking' ); ?></div>
						<div class="agoda-stat-value">
							<?php echo $dashboard_stats['cache_enabled'] ? esc_html__( 'Enabled', 'agoda-booking' ) : esc_html__( 'Disabled', 'agoda-booking' ); ?>
						</div>
					</div>
					<div class="agoda-stat-item">
						<div class="agoda-stat-label"><?php esc_html_e( 'Cache Entries', 'agoda-booking' ); ?></div>
						<div class="agoda-stat-value"><?php echo esc_html( $dashboard_stats['cache_count'] ); ?></div>
					</div>
					<div class="agoda-stat-item">
						<div class="agoda-stat-label"><?php esc_html_e( 'Cache Duration', 'agoda-booking' ); ?></div>
						<div class="agoda-stat-value">
							<?php 
							$hours = floor( $dashboard_stats['cache_duration'] / 3600 );
							$minutes = floor( ( $dashboard_stats['cache_duration'] % 3600 ) / 60 );
							if ( $hours > 0 ) {
								printf( esc_html__( '%1$dh %2$dm', 'agoda-booking' ), $hours, $minutes );
							} else {
								printf( esc_html__( '%dm', 'agoda-booking' ), $minutes );
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Quick Actions Widget -->
		<div class="agoda-widget agoda-widget-quick-actions">
			<h2><?php esc_html_e( 'Quick Actions', 'agoda-booking' ); ?></h2>
			<div class="agoda-widget-content">
				<div class="agoda-actions-grid">
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=agoda-booking-settings' ) ); ?>" class="button button-primary">
						<?php esc_html_e( 'Settings', 'agoda-booking' ); ?>
					</a>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=agoda-booking-hotel-search' ) ); ?>" class="button">
						<?php esc_html_e( 'Hotel Search', 'agoda-booking' ); ?>
					</a>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=agoda-booking-cache' ) ); ?>" class="button">
						<?php esc_html_e( 'Cache Management', 'agoda-booking' ); ?>
					</a>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=agoda-booking-logs' ) ); ?>" class="button">
						<?php esc_html_e( 'API Logs', 'agoda-booking' ); ?>
					</a>
					<button type="button" id="agoda-test-connection-dashboard" class="button button-secondary">
						<?php esc_html_e( 'Test Connection', 'agoda-booking' ); ?>
					</button>
					<button type="button" id="agoda-clear-cache-dashboard" class="button button-secondary">
						<?php esc_html_e( 'Clear Cache', 'agoda-booking' ); ?>
					</button>
				</div>
				<div id="agoda-dashboard-actions-result"></div>
			</div>
		</div>
	</div>
</div>
