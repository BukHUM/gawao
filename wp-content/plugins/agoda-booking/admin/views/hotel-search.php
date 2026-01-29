<?php
/**
 * Hotel Search Preview page template
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

$default_language = get_option( 'agoda_default_language', 'en-us' );
$default_currency = get_option( 'agoda_default_currency', 'USD' );

// Check if Content API is available for city search
$content_api_available = false;
if ( ! class_exists( 'Agoda_Content_API' ) ) {
	require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-content-api.php';
}
$content_api = new Agoda_Content_API();
$content_api_available = $content_api->validate_credentials();
?>

<div class="wrap agoda-booking-hotel-search">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	
	<p class="description">
		<?php esc_html_e( 'Use this page to test hotel searches before using them on your website. You can test City Search and Hotel List Search.', 'agoda-booking' ); ?>
	</p>

	<div class="agoda-hotel-search-container">
		<!-- Search Form -->
		<div class="agoda-search-form-wrapper">
			<h2><?php esc_html_e( 'Search Hotels', 'agoda-booking' ); ?></h2>
			
			<form id="agoda-admin-search-form" class="agoda-admin-search-form">
				<!-- Search Type -->
				<div class="agoda-form-row">
					<label for="agoda-search-type">
						<strong><?php esc_html_e( 'Search Type', 'agoda-booking' ); ?></strong>
					</label>
					<select id="agoda-search-type" name="search_type" class="regular-text">
						<option value="city"><?php esc_html_e( 'City Search', 'agoda-booking' ); ?></option>
						<option value="hotels"><?php esc_html_e( 'Hotel List Search', 'agoda-booking' ); ?></option>
					</select>
					<p class="description">
						<?php esc_html_e( 'City Search: Search by city ID. Hotel List Search: Search by multiple hotel IDs.', 'agoda-booking' ); ?>
					</p>
				</div>

				<!-- City Search (for City Search) -->
				<div class="agoda-form-row" id="agoda-city-id-row">
					<?php if ( $content_api_available ) : ?>
						<label for="agoda-city-search">
							<strong><?php esc_html_e( 'Search City by Name', 'agoda-booking' ); ?></strong>
						</label>
						<div class="agoda-city-search-wrapper">
							<input 
								type="text" 
								id="agoda-city-search" 
								name="city_search" 
								class="regular-text"
								placeholder="<?php esc_attr_e( 'Type city name (e.g., Bangkok, Phuket)...', 'agoda-booking' ); ?>"
								autocomplete="off"
							/>
							<div id="agoda-city-search-results" class="agoda-city-autocomplete" style="display: none;"></div>
						</div>
						<input 
							type="hidden" 
							id="agoda-city-id" 
							name="city_id" 
						/>
						<div id="agoda-selected-city" class="agoda-selected-city" style="display: none; margin-top: 10px; padding: 10px; background: #f0f0f0; border-radius: 4px;">
							<strong><?php esc_html_e( 'Selected City:', 'agoda-booking' ); ?></strong>
							<span id="agoda-selected-city-name"></span>
							<button type="button" id="agoda-clear-city" class="button button-small" style="margin-left: 10px;">
								<?php esc_html_e( 'Clear', 'agoda-booking' ); ?>
							</button>
						</div>
						<p class="description">
							<?php esc_html_e( 'Type city name to search. Or enter City ID directly below.', 'agoda-booking' ); ?>
						</p>
						<details style="margin-top: 10px;">
							<summary style="cursor: pointer; font-weight: 600; color: #666;"><?php esc_html_e( 'Or enter City ID directly', 'agoda-booking' ); ?></summary>
							<input 
								type="number" 
								id="agoda-city-id-direct" 
								name="city_id_direct" 
								class="regular-text"
								style="margin-top: 10px;"
								placeholder="<?php esc_attr_e( 'e.g., 9395 for Bangkok', 'agoda-booking' ); ?>"
							/>
							<p class="description">
								<?php esc_html_e( 'If you know the City ID, you can enter it directly here.', 'agoda-booking' ); ?>
							</p>
						</details>
					<?php else : ?>
						<label for="agoda-city-id">
							<strong><?php esc_html_e( 'City ID', 'agoda-booking' ); ?></strong>
						</label>
						<input 
							type="number" 
							id="agoda-city-id" 
							name="city_id" 
							class="regular-text"
							placeholder="<?php esc_attr_e( 'e.g., 9395 for Bangkok', 'agoda-booking' ); ?>"
						/>
						<p class="description">
							<?php esc_html_e( 'Enter the Agoda city ID. Example: 9395 for Bangkok, Thailand.', 'agoda-booking' ); ?>
							<br>
							<em style="color: #666;">
								<?php esc_html_e( 'Tip: Configure Content API in Settings to enable city name search.', 'agoda-booking' ); ?>
							</em>
						</p>
					<?php endif; ?>
				</div>

				<!-- Hotel IDs (for Hotel List Search) -->
				<div class="agoda-form-row" id="agoda-hotel-ids-row" style="display: none;">
					<label for="agoda-hotel-ids">
						<strong><?php esc_html_e( 'Hotel IDs', 'agoda-booking' ); ?></strong>
					</label>
					<textarea 
						id="agoda-hotel-ids" 
						name="hotel_ids" 
						class="large-text"
						rows="3"
						placeholder="<?php esc_attr_e( 'Enter hotel IDs separated by commas (e.g., 12345, 67890, 11111)', 'agoda-booking' ); ?>"
					></textarea>
					<p class="description">
						<?php esc_html_e( 'Enter multiple hotel IDs separated by commas. Example: 12345, 67890, 11111', 'agoda-booking' ); ?>
					</p>
				</div>

				<!-- Dates -->
				<div class="agoda-form-row-group">
					<div class="agoda-form-row">
						<label for="agoda-check-in">
							<strong><?php esc_html_e( 'Check-in Date', 'agoda-booking' ); ?></strong>
						</label>
						<input 
							type="date" 
							id="agoda-check-in" 
							name="check_in" 
							class="regular-text"
							value="<?php echo esc_attr( date( 'Y-m-d', strtotime( '+7 days' ) ) ); ?>"
							required
						/>
					</div>
					
					<div class="agoda-form-row">
						<label for="agoda-check-out">
							<strong><?php esc_html_e( 'Check-out Date', 'agoda-booking' ); ?></strong>
						</label>
						<input 
							type="date" 
							id="agoda-check-out" 
							name="check_out" 
							class="regular-text"
							value="<?php echo esc_attr( date( 'Y-m-d', strtotime( '+8 days' ) ) ); ?>"
							required
						/>
					</div>
				</div>

				<!-- Occupancy -->
				<div class="agoda-form-row-group">
					<div class="agoda-form-row">
						<label for="agoda-adults">
							<strong><?php esc_html_e( 'Adults', 'agoda-booking' ); ?></strong>
						</label>
						<input 
							type="number" 
							id="agoda-adults" 
							name="adults" 
							class="small-text"
							min="1"
							value="2"
							required
						/>
					</div>
					
					<div class="agoda-form-row">
						<label for="agoda-children">
							<strong><?php esc_html_e( 'Children', 'agoda-booking' ); ?></strong>
						</label>
						<input 
							type="number" 
							id="agoda-children" 
							name="children" 
							class="small-text"
							min="0"
							value="0"
						/>
					</div>
				</div>

				<!-- Children Ages (if children > 0) -->
				<div class="agoda-form-row" id="agoda-children-ages-row" style="display: none;">
					<label>
						<strong><?php esc_html_e( 'Children Ages', 'agoda-booking' ); ?></strong>
					</label>
					<div id="agoda-children-ages-container">
						<!-- Dynamically populated -->
					</div>
					<p class="description">
						<?php esc_html_e( 'Enter the age of each child (0-17 years)', 'agoda-booking' ); ?>
					</p>
				</div>

				<!-- Filters -->
				<div class="agoda-form-section">
					<h3><?php esc_html_e( 'Filters (Optional)', 'agoda-booking' ); ?></h3>
					
					<div class="agoda-form-row-group">
						<div class="agoda-form-row">
							<label for="agoda-min-price">
								<strong><?php esc_html_e( 'Min Price', 'agoda-booking' ); ?></strong>
							</label>
							<input 
								type="number" 
								id="agoda-min-price" 
								name="min_price" 
								class="small-text"
								min="0"
								step="0.01"
							/>
						</div>
						
						<div class="agoda-form-row">
							<label for="agoda-max-price">
								<strong><?php esc_html_e( 'Max Price', 'agoda-booking' ); ?></strong>
							</label>
							<input 
								type="number" 
								id="agoda-max-price" 
								name="max_price" 
								class="small-text"
								min="0"
								step="0.01"
							/>
						</div>
					</div>

					<div class="agoda-form-row-group">
						<div class="agoda-form-row">
							<label for="agoda-min-rating">
								<strong><?php esc_html_e( 'Min Star Rating', 'agoda-booking' ); ?></strong>
							</label>
							<select id="agoda-min-rating" name="min_rating" class="regular-text">
								<option value="0"><?php esc_html_e( 'Any', 'agoda-booking' ); ?></option>
								<option value="1">1 <?php esc_html_e( 'Star', 'agoda-booking' ); ?></option>
								<option value="2">2 <?php esc_html_e( 'Stars', 'agoda-booking' ); ?></option>
								<option value="3">3 <?php esc_html_e( 'Stars', 'agoda-booking' ); ?></option>
								<option value="4">4 <?php esc_html_e( 'Stars', 'agoda-booking' ); ?></option>
								<option value="5">5 <?php esc_html_e( 'Stars', 'agoda-booking' ); ?></option>
							</select>
						</div>
						
						<div class="agoda-form-row">
							<label for="agoda-min-review">
								<strong><?php esc_html_e( 'Min Review Score', 'agoda-booking' ); ?></strong>
							</label>
							<input 
								type="number" 
								id="agoda-min-review" 
								name="min_review" 
								class="small-text"
								min="0"
								max="10"
								step="0.1"
							/>
						</div>
					</div>

					<div class="agoda-form-row">
						<label>
							<input 
								type="checkbox" 
								id="agoda-discount-only" 
								name="discount_only" 
								value="1"
							/>
							<?php esc_html_e( 'Show only discounted hotels', 'agoda-booking' ); ?>
						</label>
					</div>

					<div class="agoda-form-row">
						<label for="agoda-sort-by">
							<strong><?php esc_html_e( 'Sort By', 'agoda-booking' ); ?></strong>
						</label>
						<select id="agoda-sort-by" name="sort_by" class="regular-text">
							<option value="PriceAsc"><?php esc_html_e( 'Price: Low to High', 'agoda-booking' ); ?></option>
							<option value="PriceDesc"><?php esc_html_e( 'Price: High to Low', 'agoda-booking' ); ?></option>
							<option value="ReviewScoreDesc"><?php esc_html_e( 'Review Score: High to Low', 'agoda-booking' ); ?></option>
							<option value="StarRatingDesc"><?php esc_html_e( 'Star Rating: High to Low', 'agoda-booking' ); ?></option>
						</select>
					</div>

					<div class="agoda-form-row">
						<label for="agoda-max-results">
							<strong><?php esc_html_e( 'Max Results', 'agoda-booking' ); ?></strong>
						</label>
						<input 
							type="number" 
							id="agoda-max-results" 
							name="max_results" 
							class="small-text"
							min="1"
							max="100"
							value="10"
						/>
						<p class="description">
							<?php esc_html_e( 'Maximum number of results to return (1-100)', 'agoda-booking' ); ?>
						</p>
					</div>
				</div>

				<!-- Language & Currency -->
				<div class="agoda-form-row-group">
					<div class="agoda-form-row">
						<label for="agoda-language">
							<strong><?php esc_html_e( 'Language', 'agoda-booking' ); ?></strong>
						</label>
						<input 
							type="text" 
							id="agoda-language" 
							name="language" 
							class="regular-text"
							value="<?php echo esc_attr( $default_language ); ?>"
							placeholder="en-us"
						/>
						<p class="description">
							<?php esc_html_e( 'Language code (e.g., en-us, th-th)', 'agoda-booking' ); ?>
						</p>
					</div>
					
					<div class="agoda-form-row">
						<label for="agoda-currency">
							<strong><?php esc_html_e( 'Currency', 'agoda-booking' ); ?></strong>
						</label>
						<input 
							type="text" 
							id="agoda-currency" 
							name="currency" 
							class="regular-text"
							value="<?php echo esc_attr( $default_currency ); ?>"
							placeholder="USD"
						/>
						<p class="description">
							<?php esc_html_e( 'Currency code (e.g., USD, THB)', 'agoda-booking' ); ?>
						</p>
					</div>
				</div>

				<!-- Submit Button -->
				<div class="agoda-form-row">
					<button type="submit" class="button button-primary button-large" id="agoda-search-submit">
						<?php esc_html_e( 'Search Hotels', 'agoda-booking' ); ?>
					</button>
					<span class="spinner" id="agoda-search-spinner" style="float: none; margin-left: 10px;"></span>
				</div>
			</form>
		</div>

		<!-- Results Area -->
		<div class="agoda-results-wrapper" id="agoda-results-wrapper" style="display: none;">
			<h2><?php esc_html_e( 'Search Results', 'agoda-booking' ); ?></h2>
			
			<div id="agoda-results-info" class="agoda-results-info"></div>
			
			<div id="agoda-results-container">
				<!-- Results will be displayed here -->
			</div>
		</div>

		<!-- Error Area -->
		<div id="agoda-error-container" class="agoda-error-container" style="display: none;">
			<div class="notice notice-error">
				<p id="agoda-error-message"></p>
			</div>
		</div>
	</div>
</div>

<!-- Hotel Details Modal -->
<div id="agoda-hotel-modal" class="agoda-modal" style="display: none;">
	<div class="agoda-modal-content">
		<span class="agoda-modal-close">&times;</span>
		<div id="agoda-hotel-modal-body"></div>
	</div>
</div>
