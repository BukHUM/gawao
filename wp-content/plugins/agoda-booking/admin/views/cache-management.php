<?php
/**
 * Cache Management page template
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

// Get cache statistics
if ( ! class_exists( 'Agoda_Cache' ) ) {
	require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-cache.php';
}
$cache = new Agoda_Cache();
$cache_stats = $cache->get_cache_stats();

// Get cache entries (for preview)
$cache_entries = $cache->get_cache_entries( 20 ); // Get first 20 entries
?>

<div class="wrap agoda-booking-cache">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	
	<p class="description">
		<?php esc_html_e( 'Manage cached data to improve performance and troubleshoot issues. Clear cache when needed to fetch fresh data from the API.', 'agoda-booking' ); ?>
	</p>

	<div class="agoda-cache-container">
		<!-- Cache Overview -->
		<div class="agoda-cache-overview">
			<h2><?php esc_html_e( 'Cache Overview', 'agoda-booking' ); ?></h2>
			
			<div class="agoda-cache-stats-grid">
				<div class="agoda-cache-stat-card">
					<div class="agoda-stat-label"><?php esc_html_e( 'Cache Status', 'agoda-booking' ); ?></div>
					<div class="agoda-stat-value">
						<?php echo $cache_stats['cache_enabled'] ? esc_html__( 'Enabled', 'agoda-booking' ) : esc_html__( 'Disabled', 'agoda-booking' ); ?>
					</div>
				</div>
				
				<div class="agoda-cache-stat-card">
					<div class="agoda-stat-label"><?php esc_html_e( 'Cache Entries', 'agoda-booking' ); ?></div>
					<div class="agoda-stat-value"><?php echo esc_html( $cache_stats['cache_count'] ); ?></div>
				</div>
				
				<div class="agoda-cache-stat-card">
					<div class="agoda-stat-label"><?php esc_html_e( 'Cache Duration', 'agoda-booking' ); ?></div>
					<div class="agoda-stat-value">
						<?php 
						$hours = floor( $cache_stats['cache_duration'] / 3600 );
						$minutes = floor( ( $cache_stats['cache_duration'] % 3600 ) / 60 );
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

		<!-- Cache Operations -->
		<div class="agoda-cache-operations">
			<h2><?php esc_html_e( 'Cache Operations', 'agoda-booking' ); ?></h2>
			
			<div class="agoda-cache-actions">
				<button type="button" id="agoda-clear-all-cache" class="button button-primary">
					<?php esc_html_e( 'Clear All Cache', 'agoda-booking' ); ?>
				</button>
				
				<button type="button" id="agoda-clear-expired-cache" class="button">
					<?php esc_html_e( 'Clear Expired Cache', 'agoda-booking' ); ?>
				</button>
				
				<button type="button" id="agoda-refresh-cache-stats" class="button">
					<?php esc_html_e( 'Refresh Stats', 'agoda-booking' ); ?>
				</button>
			</div>
			
			<div id="agoda-cache-operations-result"></div>
		</div>

		<!-- Clear Cache by Pattern -->
		<div class="agoda-cache-pattern">
			<h2><?php esc_html_e( 'Clear Cache by Pattern', 'agoda-booking' ); ?></h2>
			
			<form id="agoda-clear-pattern-form" class="agoda-cache-pattern-form">
				<div class="agoda-form-row">
					<label for="agoda-cache-pattern">
						<strong><?php esc_html_e( 'Cache Key Pattern', 'agoda-booking' ); ?></strong>
					</label>
					<input 
						type="text" 
						id="agoda-cache-pattern" 
						name="cache_pattern" 
						class="regular-text"
						placeholder="<?php esc_attr_e( 'e.g., search_abc123 or city_9395', 'agoda-booking' ); ?>"
					/>
					<p class="description">
						<?php esc_html_e( 'Enter a pattern to match cache keys. Leave empty to clear all cache.', 'agoda-booking' ); ?>
					</p>
				</div>
				
				<div class="agoda-form-row">
					<button type="submit" class="button">
						<?php esc_html_e( 'Clear Matching Cache', 'agoda-booking' ); ?>
					</button>
				</div>
			</form>
		</div>

		<!-- Cache Preview -->
		<div class="agoda-cache-preview">
			<h2><?php esc_html_e( 'Cache Entries Preview', 'agoda-booking' ); ?></h2>
			
			<p class="description">
				<?php esc_html_e( 'Showing the first 20 cache entries. This is for debugging purposes only.', 'agoda-booking' ); ?>
			</p>
			
			<?php if ( ! empty( $cache_entries ) ) : ?>
				<table class="wp-list-table widefat fixed striped">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Cache Key', 'agoda-booking' ); ?></th>
							<th><?php esc_html_e( 'Expires', 'agoda-booking' ); ?></th>
							<th><?php esc_html_e( 'Size', 'agoda-booking' ); ?></th>
							<th><?php esc_html_e( 'Actions', 'agoda-booking' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $cache_entries as $entry ) : ?>
							<tr>
								<td>
									<code><?php echo esc_html( $entry['key'] ); ?></code>
								</td>
								<td>
									<?php 
									if ( $entry['expires'] ) {
										echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $entry['expires'] ) );
										$time_left = $entry['expires'] - current_time( 'timestamp' );
										if ( $time_left > 0 ) {
											echo ' <span class="agoda-time-left">(';
											if ( $time_left > 3600 ) {
												printf( esc_html__( '%d hours left', 'agoda-booking' ), floor( $time_left / 3600 ) );
											} elseif ( $time_left > 60 ) {
												printf( esc_html__( '%d minutes left', 'agoda-booking' ), floor( $time_left / 60 ) );
											} else {
												printf( esc_html__( '%d seconds left', 'agoda-booking' ), $time_left );
											}
											echo ')</span>';
										} else {
											echo ' <span class="agoda-expired">(' . esc_html__( 'Expired', 'agoda-booking' ) . ')</span>';
										}
									} else {
										echo esc_html__( 'No expiration', 'agoda-booking' );
									}
									?>
								</td>
								<td>
									<?php echo esc_html( size_format( $entry['size'], 2 ) ); ?>
								</td>
								<td>
									<button 
										type="button" 
										class="button button-small agoda-delete-cache-entry" 
										data-key="<?php echo esc_attr( $entry['key'] ); ?>"
									>
										<?php esc_html_e( 'Delete', 'agoda-booking' ); ?>
									</button>
									<button 
										type="button" 
										class="button button-small agoda-view-cache-entry" 
										data-key="<?php echo esc_attr( $entry['key'] ); ?>"
									>
										<?php esc_html_e( 'View', 'agoda-booking' ); ?>
									</button>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php else : ?>
				<p><?php esc_html_e( 'No cache entries found.', 'agoda-booking' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</div>

<!-- Cache Entry View Modal -->
<div id="agoda-cache-modal" class="agoda-modal" style="display: none;">
	<div class="agoda-modal-content">
		<span class="agoda-modal-close">&times;</span>
		<h2><?php esc_html_e( 'Cache Entry Details', 'agoda-booking' ); ?></h2>
		<div id="agoda-cache-modal-body"></div>
	</div>
</div>
