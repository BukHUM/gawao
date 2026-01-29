<?php
/**
 * API Logs page template
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

// Get log settings
$log_enabled = get_option( 'agoda_enable_logging', defined( 'WP_DEBUG' ) && WP_DEBUG );
$log_level = get_option( 'agoda_log_level', 'error' );
$log_retention = get_option( 'agoda_log_retention_days', 30 );

// Get logs (we'll use AJAX to load them dynamically)
?>

<div class="wrap agoda-booking-logs">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	
	<p class="description">
		<?php esc_html_e( 'View and manage API logs for debugging and monitoring. Logs are stored in WordPress debug log file.', 'agoda-booking' ); ?>
	</p>

	<div class="agoda-logs-container">
		<!-- Log Settings -->
		<div class="agoda-logs-settings">
			<h2><?php esc_html_e( 'Log Settings', 'agoda-booking' ); ?></h2>
			
			<form id="agoda-logs-settings-form" class="agoda-logs-settings-form">
				<table class="form-table">
					<tr>
						<th scope="row">
							<label for="agoda-enable-logging">
								<?php esc_html_e( 'Enable Logging', 'agoda-booking' ); ?>
							</label>
						</th>
						<td>
							<label>
								<input 
									type="checkbox" 
									id="agoda-enable-logging" 
									name="enable_logging" 
									value="1"
									<?php checked( $log_enabled, true ); ?>
								/>
								<?php esc_html_e( 'Enable API logging', 'agoda-booking' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'When enabled, API requests and responses will be logged to WordPress debug log.', 'agoda-booking' ); ?>
							</p>
						</td>
					</tr>
					
					<tr>
						<th scope="row">
							<label for="agoda-log-level">
								<?php esc_html_e( 'Log Level', 'agoda-booking' ); ?>
							</label>
						</th>
						<td>
							<select id="agoda-log-level" name="log_level" class="regular-text">
								<option value="error" <?php selected( $log_level, 'error' ); ?>>
									<?php esc_html_e( 'Error Only', 'agoda-booking' ); ?>
								</option>
								<option value="warning" <?php selected( $log_level, 'warning' ); ?>>
									<?php esc_html_e( 'Warning & Error', 'agoda-booking' ); ?>
								</option>
								<option value="info" <?php selected( $log_level, 'info' ); ?>>
									<?php esc_html_e( 'Info, Warning & Error', 'agoda-booking' ); ?>
								</option>
								<option value="debug" <?php selected( $log_level, 'debug' ); ?>>
									<?php esc_html_e( 'Debug (All)', 'agoda-booking' ); ?>
								</option>
							</select>
							<p class="description">
								<?php esc_html_e( 'Select the minimum log level to record. Debug level includes all logs including API requests.', 'agoda-booking' ); ?>
							</p>
						</td>
					</tr>
					
					<tr>
						<th scope="row">
							<label for="agoda-log-retention">
								<?php esc_html_e( 'Log Retention', 'agoda-booking' ); ?>
							</label>
						</th>
						<td>
							<input 
								type="number" 
								id="agoda-log-retention" 
								name="log_retention" 
								class="small-text"
								min="1"
								value="<?php echo esc_attr( $log_retention ); ?>"
							/>
							<span><?php esc_html_e( 'days', 'agoda-booking' ); ?></span>
							<p class="description">
								<?php esc_html_e( 'Number of days to keep logs. Older logs will be automatically deleted.', 'agoda-booking' ); ?>
							</p>
						</td>
					</tr>
				</table>
				
				<p class="submit">
					<button type="submit" class="button button-primary">
						<?php esc_html_e( 'Save Settings', 'agoda-booking' ); ?>
					</button>
				</p>
			</form>
		</div>

		<!-- Log Filters -->
		<div class="agoda-logs-filters">
			<h2><?php esc_html_e( 'Logs', 'agoda-booking' ); ?></h2>
			
			<div class="agoda-logs-filter-controls">
				<form id="agoda-logs-filter-form" class="agoda-logs-filter-form">
					<div class="agoda-filter-row">
						<div class="agoda-filter-group">
							<label for="agoda-log-level-filter">
								<strong><?php esc_html_e( 'Log Level:', 'agoda-booking' ); ?></strong>
							</label>
							<select id="agoda-log-level-filter" name="log_level_filter" class="regular-text">
								<option value=""><?php esc_html_e( 'All Levels', 'agoda-booking' ); ?></option>
								<option value="error"><?php esc_html_e( 'Error', 'agoda-booking' ); ?></option>
								<option value="warning"><?php esc_html_e( 'Warning', 'agoda-booking' ); ?></option>
								<option value="info"><?php esc_html_e( 'Info', 'agoda-booking' ); ?></option>
								<option value="debug"><?php esc_html_e( 'Debug', 'agoda-booking' ); ?></option>
							</select>
						</div>
						
						<div class="agoda-filter-group">
							<label for="agoda-log-date-from">
								<strong><?php esc_html_e( 'Date From:', 'agoda-booking' ); ?></strong>
							</label>
							<input 
								type="date" 
								id="agoda-log-date-from" 
								name="date_from" 
								class="regular-text"
							/>
						</div>
						
						<div class="agoda-filter-group">
							<label for="agoda-log-date-to">
								<strong><?php esc_html_e( 'Date To:', 'agoda-booking' ); ?></strong>
							</label>
							<input 
								type="date" 
								id="agoda-log-date-to" 
								name="date_to" 
								class="regular-text"
							/>
						</div>
						
						<div class="agoda-filter-group">
							<label for="agoda-log-search">
								<strong><?php esc_html_e( 'Search:', 'agoda-booking' ); ?></strong>
							</label>
							<input 
								type="text" 
								id="agoda-log-search" 
								name="search" 
								class="regular-text"
								placeholder="<?php esc_attr_e( 'Search in log messages...', 'agoda-booking' ); ?>"
							/>
						</div>
						
						<div class="agoda-filter-group">
							<button type="submit" class="button">
								<?php esc_html_e( 'Filter', 'agoda-booking' ); ?>
							</button>
							<button type="button" id="agoda-reset-filters" class="button">
								<?php esc_html_e( 'Reset', 'agoda-booking' ); ?>
							</button>
						</div>
					</div>
				</form>
			</div>
			
			<div class="agoda-logs-actions">
				<button type="button" id="agoda-refresh-logs" class="button">
					<?php esc_html_e( 'Refresh', 'agoda-booking' ); ?>
				</button>
				<button type="button" id="agoda-export-logs" class="button">
					<?php esc_html_e( 'Export Logs', 'agoda-booking' ); ?>
				</button>
				<button type="button" id="agoda-clear-old-logs" class="button">
					<?php esc_html_e( 'Clear Old Logs', 'agoda-booking' ); ?>
				</button>
			</div>
		</div>

		<!-- Log List -->
		<div class="agoda-logs-list">
			<div id="agoda-logs-loading" class="agoda-logs-loading" style="display: none;">
				<p><?php esc_html_e( 'Loading logs...', 'agoda-booking' ); ?></p>
			</div>
			
			<div id="agoda-logs-container">
				<!-- Logs will be loaded here via AJAX -->
			</div>
			
			<div id="agoda-logs-pagination" class="agoda-logs-pagination">
				<!-- Pagination will be added here -->
			</div>
		</div>
	</div>
</div>

<!-- Log Details Modal -->
<div id="agoda-log-modal" class="agoda-modal" style="display: none;">
	<div class="agoda-modal-content agoda-log-modal-content">
		<span class="agoda-modal-close">&times;</span>
		<h2><?php esc_html_e( 'Log Entry Details', 'agoda-booking' ); ?></h2>
		<div id="agoda-log-modal-body"></div>
	</div>
</div>
