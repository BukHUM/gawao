<?php
/**
 * Bulk Operations page template
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
?>

<div class="wrap agoda-booking-bulk-operations">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	
	<p class="description">
		<?php esc_html_e( 'Perform bulk operations on multiple hotels or cache entries at once.', 'agoda-booking' ); ?>
	</p>

	<div class="agoda-bulk-operations-container">
		<!-- Bulk Hotel Search -->
		<div class="agoda-bulk-section">
			<h2><?php esc_html_e( 'Bulk Hotel Search', 'agoda-booking' ); ?></h2>
			
			<p class="description">
				<?php esc_html_e( 'Upload a CSV file with hotel IDs or enter hotel IDs manually to search multiple hotels at once.', 'agoda-booking' ); ?>
			</p>

			<form id="agoda-bulk-hotel-search-form" class="agoda-bulk-form">
				<div class="agoda-form-row">
					<label for="agoda-bulk-hotel-ids">
						<strong><?php esc_html_e( 'Hotel IDs', 'agoda-booking' ); ?></strong>
					</label>
					<textarea 
						id="agoda-bulk-hotel-ids" 
						name="hotel_ids" 
						class="large-text"
						rows="5"
						placeholder="<?php esc_attr_e( 'Enter hotel IDs separated by commas or new lines (e.g., 12345, 67890, 11111)', 'agoda-booking' ); ?>"
					></textarea>
					<p class="description">
						<?php esc_html_e( 'Enter multiple hotel IDs separated by commas or new lines. Maximum 50 hotels per search.', 'agoda-booking' ); ?>
					</p>
				</div>

				<div class="agoda-form-row-group">
					<div class="agoda-form-row">
						<label for="agoda-bulk-check-in">
							<strong><?php esc_html_e( 'Check-in Date', 'agoda-booking' ); ?></strong>
						</label>
						<input 
							type="date" 
							id="agoda-bulk-check-in" 
							name="check_in" 
							class="regular-text"
							value="<?php echo esc_attr( date( 'Y-m-d', strtotime( '+7 days' ) ) ); ?>"
							required
						/>
					</div>
					
					<div class="agoda-form-row">
						<label for="agoda-bulk-check-out">
							<strong><?php esc_html_e( 'Check-out Date', 'agoda-booking' ); ?></strong>
						</label>
						<input 
							type="date" 
							id="agoda-bulk-check-out" 
							name="check_out" 
							class="regular-text"
							value="<?php echo esc_attr( date( 'Y-m-d', strtotime( '+8 days' ) ) ); ?>"
							required
						/>
					</div>
				</div>

				<div class="agoda-form-row-group">
					<div class="agoda-form-row">
						<label for="agoda-bulk-adults">
							<strong><?php esc_html_e( 'Adults', 'agoda-booking' ); ?></strong>
						</label>
						<input 
							type="number" 
							id="agoda-bulk-adults" 
							name="adults" 
							class="small-text"
							min="1"
							value="2"
							required
						/>
					</div>
					
					<div class="agoda-form-row">
						<label for="agoda-bulk-currency">
							<strong><?php esc_html_e( 'Currency', 'agoda-booking' ); ?></strong>
						</label>
						<input 
							type="text" 
							id="agoda-bulk-currency" 
							name="currency" 
							class="regular-text"
							value="<?php echo esc_attr( get_option( 'agoda_default_currency', 'USD' ) ); ?>"
							placeholder="USD"
						/>
					</div>
				</div>

				<div class="agoda-form-row">
					<button type="submit" class="button button-primary button-large" id="agoda-bulk-search-submit">
						<?php esc_html_e( 'Search Hotels', 'agoda-booking' ); ?>
					</button>
					<span class="spinner" id="agoda-bulk-search-spinner" style="float: none; margin-left: 10px;"></span>
				</div>
			</form>

			<!-- Results -->
			<div id="agoda-bulk-search-results" class="agoda-bulk-results" style="display: none;">
				<h3><?php esc_html_e( 'Search Results', 'agoda-booking' ); ?></h3>
				<div id="agoda-bulk-results-info"></div>
				<div id="agoda-bulk-results-container"></div>
				<div class="agoda-bulk-actions">
					<button type="button" id="agoda-export-bulk-results" class="button">
						<?php esc_html_e( 'Export Results', 'agoda-booking' ); ?>
					</button>
				</div>
			</div>
		</div>

		<!-- Bulk Cache Clear -->
		<div class="agoda-bulk-section">
			<h2><?php esc_html_e( 'Bulk Cache Clear', 'agoda-booking' ); ?></h2>
			
			<p class="description">
				<?php esc_html_e( 'Clear cache for multiple cities or date ranges at once.', 'agoda-booking' ); ?>
			</p>

			<form id="agoda-bulk-cache-clear-form" class="agoda-bulk-form">
				<div class="agoda-form-row">
					<label>
						<strong><?php esc_html_e( 'Clear Method', 'agoda-booking' ); ?></strong>
					</label>
					<select id="agoda-bulk-cache-method" name="cache_method" class="regular-text">
						<option value="cities"><?php esc_html_e( 'By City IDs', 'agoda-booking' ); ?></option>
						<option value="date_range"><?php esc_html_e( 'By Date Range', 'agoda-booking' ); ?></option>
					</select>
				</div>

				<!-- City IDs Method -->
				<div class="agoda-form-row" id="agoda-bulk-cache-cities-row">
					<label for="agoda-bulk-cache-city-ids">
						<strong><?php esc_html_e( 'City IDs', 'agoda-booking' ); ?></strong>
					</label>
					<textarea 
						id="agoda-bulk-cache-city-ids" 
						name="city_ids" 
						class="large-text"
						rows="3"
						placeholder="<?php esc_attr_e( 'Enter city IDs separated by commas (e.g., 9395, 1234, 5678)', 'agoda-booking' ); ?>"
					></textarea>
					<p class="description">
						<?php esc_html_e( 'Enter city IDs separated by commas. Cache entries matching these city IDs will be cleared.', 'agoda-booking' ); ?>
					</p>
				</div>

				<!-- Date Range Method -->
				<div class="agoda-form-row-group" id="agoda-bulk-cache-date-row" style="display: none;">
					<div class="agoda-form-row">
						<label for="agoda-bulk-cache-date-from">
							<strong><?php esc_html_e( 'Date From', 'agoda-booking' ); ?></strong>
						</label>
						<input 
							type="date" 
							id="agoda-bulk-cache-date-from" 
							name="date_from" 
							class="regular-text"
						/>
					</div>
					
					<div class="agoda-form-row">
						<label for="agoda-bulk-cache-date-to">
							<strong><?php esc_html_e( 'Date To', 'agoda-booking' ); ?></strong>
						</label>
						<input 
							type="date" 
							id="agoda-bulk-cache-date-to" 
							name="date_to" 
							class="regular-text"
						/>
					</div>
					<p class="description">
						<?php esc_html_e( 'Clear cache entries that match searches within this date range.', 'agoda-booking' ); ?>
					</p>
				</div>

				<div class="agoda-form-row">
					<button type="submit" class="button button-primary" id="agoda-bulk-cache-clear-submit">
						<?php esc_html_e( 'Clear Cache', 'agoda-booking' ); ?>
					</button>
					<span class="spinner" id="agoda-bulk-cache-spinner" style="float: none; margin-left: 10px;"></span>
				</div>
			</form>

			<div id="agoda-bulk-cache-result"></div>
		</div>
	</div>
</div>
