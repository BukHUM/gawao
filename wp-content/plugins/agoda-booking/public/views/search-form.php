<?php
/**
 * Search form template
 *
 * @package Agoda_Booking
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$default_language = get_option( 'agoda_default_language', 'en-us' );
$default_currency = get_option( 'agoda_default_currency', 'USD' );
?>

<form id="agoda-search-form" class="agoda-search-form" role="search" aria-label="<?php esc_attr_e( 'Hotel search form', 'agoda-booking' ); ?>">
	<div class="form-row">
		<label for="agoda-check-in"><?php esc_html_e( 'Check-in Date', 'agoda-booking' ); ?></label>
		<input 
			type="date" 
			id="agoda-check-in" 
			name="check_in" 
			required 
			aria-required="true"
			aria-describedby="agoda-check-in-desc"
		/>
		<span id="agoda-check-in-desc" class="agoda-sr-only"><?php esc_html_e( 'Select your check-in date', 'agoda-booking' ); ?></span>
	</div>
	
	<div class="form-row">
		<label for="agoda-check-out"><?php esc_html_e( 'Check-out Date', 'agoda-booking' ); ?></label>
		<input 
			type="date" 
			id="agoda-check-out" 
			name="check_out" 
			required 
			aria-required="true"
			aria-describedby="agoda-check-out-desc"
		/>
		<span id="agoda-check-out-desc" class="agoda-sr-only"><?php esc_html_e( 'Select your check-out date', 'agoda-booking' ); ?></span>
	</div>
	
	<div class="form-row">
		<label for="agoda-city-id"><?php esc_html_e( 'City ID', 'agoda-booking' ); ?></label>
		<input 
			type="number" 
			id="agoda-city-id" 
			name="city_id" 
			placeholder="<?php esc_attr_e( 'Enter city ID', 'agoda-booking' ); ?>" 
			required 
			aria-required="true"
			aria-describedby="agoda-city-id-desc"
		/>
		<p id="agoda-city-id-desc" class="description"><?php esc_html_e( 'Enter the Agoda city ID (e.g., 9395 for Bangkok)', 'agoda-booking' ); ?></p>
	</div>
	
	<div class="form-row">
		<label for="agoda-adults"><?php esc_html_e( 'Adults', 'agoda-booking' ); ?></label>
		<input 
			type="number" 
			id="agoda-adults" 
			name="adults" 
			min="1" 
			value="2" 
			required 
			aria-required="true"
			aria-describedby="agoda-adults-desc"
		/>
		<span id="agoda-adults-desc" class="agoda-sr-only"><?php esc_html_e( 'Number of adults', 'agoda-booking' ); ?></span>
	</div>
	
	<div class="form-row">
		<label for="agoda-children"><?php esc_html_e( 'Children', 'agoda-booking' ); ?></label>
		<input 
			type="number" 
			id="agoda-children" 
			name="children" 
			min="0" 
			value="0" 
			aria-describedby="agoda-children-desc"
		/>
		<p id="agoda-children-desc" class="description"><?php esc_html_e( 'Number of children (ages 0-17)', 'agoda-booking' ); ?></p>
	</div>
	
	<div class="form-row" id="agoda-children-ages-row" style="display: none;" role="group" aria-labelledby="agoda-children-ages-label">
		<label id="agoda-children-ages-label"><?php esc_html_e( 'Children Ages', 'agoda-booking' ); ?></label>
		<div id="agoda-children-ages-container" role="list">
			<!-- Children ages inputs will be dynamically added here -->
		</div>
		<p class="description"><?php esc_html_e( 'Enter the age of each child (0-17 years)', 'agoda-booking' ); ?></p>
	</div>
	
	<input type="hidden" id="agoda-language" name="language" value="<?php echo esc_attr( $default_language ); ?>" />
	<input type="hidden" id="agoda-currency" name="currency" value="<?php echo esc_attr( $default_currency ); ?>" />
	
	<div class="form-row">
		<button type="submit" class="button" aria-label="<?php esc_attr_e( 'Search for hotels', 'agoda-booking' ); ?>">
			<?php esc_html_e( 'Search Hotels', 'agoda-booking' ); ?>
		</button>
	</div>
</form>

<div class="agoda-loading" style="display: none;" role="status" aria-live="polite" aria-label="<?php esc_attr_e( 'Loading search results', 'agoda-booking' ); ?>">
	<p><?php esc_html_e( 'Searching...', 'agoda-booking' ); ?></p>
</div>

<div class="agoda-error" style="display: none;" role="alert" aria-live="assertive">
	<p></p>
	<button type="button" class="agoda-retry-button" style="display: none;">
		<?php esc_html_e( 'Try Again', 'agoda-booking' ); ?>
	</button>
</div>

<div id="agoda-results" class="agoda-results" style="display: none;"></div>
