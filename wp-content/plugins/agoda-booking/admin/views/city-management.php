<?php
/**
 * City Management page template
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

// Load Content API class
if ( ! class_exists( 'Agoda_Content_API' ) ) {
	require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-content-api.php';
}
$content_api = new Agoda_Content_API();

// Check if Content API is configured
$content_api_configured = $content_api->validate_credentials();

// Debug: Check what values we have
$debug_token = get_option( 'agoda_content_api_token', '' );
$debug_site_id = get_option( 'agoda_content_api_site_id', '' );
$debug_base_url = get_option( 'agoda_content_api_base_url', '' );

// Get countries for filter
$countries = array();
if ( $content_api_configured ) {
	$countries_result = $content_api->get_countries();
	if ( ! is_wp_error( $countries_result ) && is_array( $countries_result ) ) {
		$countries = $countries_result;
	}
}
?>

<div class="wrap agoda-booking-city-management">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	
	<p class="description">
		<?php esc_html_e( 'Manage and search cities from Agoda Content API. Use this to find city IDs for hotel searches.', 'agoda-booking' ); ?>
	</p>

	<?php if ( ! $content_api_configured ) : ?>
		<div class="notice notice-warning">
			<p>
				<strong><?php esc_html_e( 'Content API not configured.', 'agoda-booking' ); ?></strong>
				<?php esc_html_e( 'Please configure Content API credentials in', 'agoda-booking' ); ?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=agoda-booking-settings' ) ); ?>">
					<?php esc_html_e( 'Settings', 'agoda-booking' ); ?>
				</a>.
			</p>
			<p style="margin-top: 10px;">
				<strong><?php esc_html_e( 'Important:', 'agoda-booking' ); ?></strong>
				<?php esc_html_e( 'After filling in the Content API Token and Site ID in Settings, you must click "Save Settings" button for the changes to take effect.', 'agoda-booking' ); ?>
			</p>
			<?php if ( current_user_can( 'manage_options' ) ) : ?>
				<details style="margin-top: 10px;">
					<summary style="cursor: pointer; font-weight: 600; color: #666;"><?php esc_html_e( 'Debug Info (Click to expand)', 'agoda-booking' ); ?></summary>
					<p style="margin-top: 10px; font-size: 12px; color: #666; padding: 10px; background: #f9f9f9; border-left: 3px solid #666;">
						<strong><?php esc_html_e( 'Current Settings Values:', 'agoda-booking' ); ?></strong><br>
						<?php esc_html_e( 'Token:', 'agoda-booking' ); ?> 
						<?php 
						if ( empty( $debug_token ) ) {
							echo '<span style="color: red;">❌ Empty</span>';
						} else {
							echo '<span style="color: green;">✅ Set (' . strlen( $debug_token ) . ' characters)</span>';
						}
						?><br>
						<?php esc_html_e( 'Site ID:', 'agoda-booking' ); ?> 
						<?php 
						if ( empty( $debug_site_id ) ) {
							echo '<span style="color: red;">❌ Empty</span>';
						} else {
							echo '<span style="color: green;">✅ Set (' . esc_html( $debug_site_id ) . ')</span>';
						}
						?><br>
						<?php esc_html_e( 'Base URL:', 'agoda-booking' ); ?> 
						<?php 
						if ( empty( $debug_base_url ) ) {
							echo '<span style="color: orange;">⚠️ Using default (https://affiliateapi7643.agoda.com)</span>';
						} else {
							echo '<span style="color: green;">✅ ' . esc_html( $debug_base_url ) . '</span>';
						}
						?><br><br>
						<strong><?php esc_html_e( 'What to do:', 'agoda-booking' ); ?></strong><br>
						<?php if ( empty( $debug_token ) || empty( $debug_site_id ) ) : ?>
							<?php esc_html_e( '1. Go to Settings page', 'agoda-booking' ); ?><br>
							<?php esc_html_e( '2. Fill in "Content API Token" and "Content API Site ID" fields', 'agoda-booking' ); ?><br>
							<?php esc_html_e( '3. Click "Save Settings" button', 'agoda-booking' ); ?><br>
							<?php esc_html_e( '4. Come back to this page and refresh', 'agoda-booking' ); ?>
						<?php else : ?>
							<span style="color: green;">✅ <?php esc_html_e( 'Settings are saved! If you still see this message, please refresh the page.', 'agoda-booking' ); ?></span>
						<?php endif; ?>
					</p>
				</details>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="agoda-city-management-container">
		<!-- Search and Filter -->
		<div class="agoda-city-filters">
			<form id="agoda-city-search-form" class="agoda-city-search-form">
				<div class="agoda-filter-row">
					<div class="agoda-filter-group">
						<label for="agoda-city-search">
							<strong><?php esc_html_e( 'Search City:', 'agoda-booking' ); ?></strong>
						</label>
						<input 
							type="text" 
							id="agoda-city-search" 
							name="search" 
							class="regular-text"
							placeholder="<?php esc_attr_e( 'Enter city name...', 'agoda-booking' ); ?>"
						/>
					</div>

					<div class="agoda-filter-group">
						<label for="agoda-city-country-filter">
							<strong><?php esc_html_e( 'Filter by Country:', 'agoda-booking' ); ?></strong>
						</label>
						<select id="agoda-city-country-filter" name="country" class="regular-text">
							<option value=""><?php esc_html_e( 'All Countries', 'agoda-booking' ); ?></option>
							<?php if ( ! empty( $countries ) ) : ?>
								<?php foreach ( $countries as $country ) : ?>
									<?php
									$country_code = isset( $country['CountryCode'] ) ? $country['CountryCode'] : ( isset( $country['country_code'] ) ? $country['country_code'] : '' );
									$country_name = isset( $country['CountryName'] ) ? $country['CountryName'] : ( isset( $country['country_name'] ) ? $country['country_name'] : '' );
									if ( empty( $country_code ) || empty( $country_name ) ) {
										continue;
									}
									?>
									<option value="<?php echo esc_attr( $country_code ); ?>">
										<?php echo esc_html( $country_name ); ?>
									</option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>

					<div class="agoda-filter-group">
						<button type="submit" class="button button-primary">
							<?php esc_html_e( 'Search', 'agoda-booking' ); ?>
						</button>
						<button type="button" id="agoda-reset-city-filters" class="button">
							<?php esc_html_e( 'Reset', 'agoda-booking' ); ?>
						</button>
					</div>
				</div>
			</form>
		</div>

		<!-- Actions -->
		<div class="agoda-city-actions">
			<button type="button" id="agoda-refresh-cities" class="button">
				<?php esc_html_e( 'Refresh Cities List', 'agoda-booking' ); ?>
			</button>
			<button type="button" id="agoda-export-cities" class="button">
				<?php esc_html_e( 'Export Cities', 'agoda-booking' ); ?>
			</button>
			<span class="spinner" id="agoda-city-spinner" style="float: none; margin-left: 10px;"></span>
		</div>

		<!-- Cities List -->
		<div class="agoda-cities-list">
			<div id="agoda-cities-loading" class="agoda-cities-loading" style="display: none;">
				<p><?php esc_html_e( 'Loading cities...', 'agoda-booking' ); ?></p>
			</div>
			
			<div id="agoda-cities-container">
				<?php if ( $content_api_configured ) : ?>
					<p><?php esc_html_e( 'Use the search form above to find cities.', 'agoda-booking' ); ?></p>
				<?php else : ?>
					<p><?php esc_html_e( 'Please configure Content API credentials to use City Management.', 'agoda-booking' ); ?></p>
				<?php endif; ?>
			</div>
			
			<div id="agoda-cities-pagination" class="agoda-cities-pagination">
				<!-- Pagination will be added here -->
			</div>
		</div>
	</div>
</div>

<!-- City Details Modal -->
<div id="agoda-city-modal" class="agoda-modal" style="display: none;">
	<div class="agoda-modal-content">
		<span class="agoda-modal-close">&times;</span>
		<h2><?php esc_html_e( 'City Details', 'agoda-booking' ); ?></h2>
		<div id="agoda-city-modal-body"></div>
	</div>
</div>
