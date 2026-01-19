<?php
/**
 * Settings page template
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

<div class="wrap agoda-booking-settings">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	
	<?php
	// Show settings errors if any
	settings_errors( 'agoda_booking_settings' );
	
	// Show success message if settings were saved
	if ( isset( $_GET['settings-updated'] ) ) {
		?>
		<div class="notice notice-success is-dismissible">
			<p><?php esc_html_e( 'Settings saved successfully.', 'agoda-booking' ); ?></p>
		</div>
		<?php
	}
	?>
	
	<form method="post" action="options.php">
		<?php
		settings_fields( 'agoda_booking_settings' );
		do_settings_sections( 'agoda_booking_settings' );
		?>
		
		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="agoda_site_id"><?php esc_html_e( 'Site ID', 'agoda-booking' ); ?></label>
				</th>
				<td>
					<input 
						type="text" 
						id="agoda_site_id" 
						name="agoda_site_id" 
						value="<?php echo esc_attr( get_option( 'agoda_site_id', '' ) ); ?>" 
						class="regular-text"
						required
					/>
					<p class="description">
						<?php esc_html_e( 'Your Agoda Site ID for API authentication (used in Authorization header). This is DIFFERENT from the Site ID shown in Affiliate Dashboard. You need to get this from the Agoda Developer Portal (developer.agoda.com) or contact your Agoda account manager. The Site ID in Affiliate Dashboard is actually your CID (see below).', 'agoda-booking' ); ?>
					</p>
				</td>
			</tr>
			
			<tr>
				<th scope="row">
					<label for="agoda_api_key"><?php esc_html_e( 'API Key', 'agoda-booking' ); ?></label>
				</th>
				<td>
					<input 
						type="password" 
						id="agoda_api_key" 
						name="agoda_api_key" 
						value="<?php echo esc_attr( get_option( 'agoda_api_key', '' ) ); ?>" 
						class="regular-text"
						required
					/>
					<p class="description">
						<?php esc_html_e( 'Your Agoda API Key for API authentication. This is typically provided when you register for API access through the Agoda Developer Portal (developer.agoda.com) or contact your Agoda account manager.', 'agoda-booking' ); ?>
					</p>
				</td>
			</tr>
			
			<tr>
				<th scope="row">
					<label for="agoda_cid"><?php esc_html_e( 'CID (Customer ID)', 'agoda-booking' ); ?></label>
				</th>
				<td>
					<input 
						type="text" 
						id="agoda_cid" 
						name="agoda_cid" 
						value="<?php echo esc_attr( get_option( 'agoda_cid', '' ) ); ?>" 
						class="regular-text"
					/>
					<p class="description">
						<?php esc_html_e( 'Your Agoda CID (Customer ID) for affiliate tracking in landing URLs. This is the "Site ID" shown in the Agoda Affiliate Dashboard (partners.agoda.com) under Profile → Manage Your Sites. For example, if you see Site ID "1425703" in the dashboard, that is your CID. If left empty, Site ID (for API) will be used instead.', 'agoda-booking' ); ?>
					</p>
				</td>
			</tr>
			
			<tr>
				<th scope="row">
					<label for="agoda_default_language"><?php esc_html_e( 'Default Language', 'agoda-booking' ); ?></label>
				</th>
				<td>
					<select id="agoda_default_language" name="agoda_default_language">
						<option value="en-us" <?php selected( get_option( 'agoda_default_language', 'en-us' ), 'en-us' ); ?>>English (US)</option>
						<option value="th-th" <?php selected( get_option( 'agoda_default_language', 'en-us' ), 'th-th' ); ?>>Thai</option>
						<option value="zh-cn" <?php selected( get_option( 'agoda_default_language', 'en-us' ), 'zh-cn' ); ?>>Simplified Chinese</option>
						<option value="zh-tw" <?php selected( get_option( 'agoda_default_language', 'en-us' ), 'zh-tw' ); ?>>Traditional Chinese</option>
						<option value="ja-jp" <?php selected( get_option( 'agoda_default_language', 'en-us' ), 'ja-jp' ); ?>>Japanese</option>
						<option value="ko-kr" <?php selected( get_option( 'agoda_default_language', 'en-us' ), 'ko-kr' ); ?>>Korean</option>
					</select>
					<p class="description">
						<?php esc_html_e( 'Default language for API requests.', 'agoda-booking' ); ?>
					</p>
				</td>
			</tr>
			
			<tr>
				<th scope="row">
					<label for="agoda_default_currency"><?php esc_html_e( 'Default Currency', 'agoda-booking' ); ?></label>
				</th>
				<td>
					<select id="agoda_default_currency" name="agoda_default_currency">
						<option value="USD" <?php selected( get_option( 'agoda_default_currency', 'USD' ), 'USD' ); ?>>USD - US Dollar</option>
						<option value="THB" <?php selected( get_option( 'agoda_default_currency', 'USD' ), 'THB' ); ?>>THB - Thai Baht</option>
						<option value="EUR" <?php selected( get_option( 'agoda_default_currency', 'USD' ), 'EUR' ); ?>>EUR - Euro</option>
						<option value="GBP" <?php selected( get_option( 'agoda_default_currency', 'USD' ), 'GBP' ); ?>>GBP - British Pound</option>
						<option value="JPY" <?php selected( get_option( 'agoda_default_currency', 'USD' ), 'JPY' ); ?>>JPY - Japanese Yen</option>
						<option value="CNY" <?php selected( get_option( 'agoda_default_currency', 'USD' ), 'CNY' ); ?>>CNY - Chinese Yuan</option>
					</select>
					<p class="description">
						<?php esc_html_e( 'Default currency for API requests.', 'agoda-booking' ); ?>
					</p>
				</td>
			</tr>
			
			<tr>
				<th scope="row">
					<label for="agoda_api_endpoint"><?php esc_html_e( 'API Endpoint', 'agoda-booking' ); ?></label>
				</th>
				<td>
					<input 
						type="text" 
						id="agoda_api_endpoint" 
						name="agoda_api_endpoint" 
						value="<?php echo esc_attr( get_option( 'agoda_api_endpoint', 'http://affiliateapi7643.agoda.com/affiliateservice/lt_v1' ) ); ?>" 
						class="regular-text"
						readonly
						disabled
					/>
					<p class="description">
						<?php esc_html_e( 'Agoda API endpoint URL. This is set by default and should not be changed unless instructed by Agoda.', 'agoda-booking' ); ?>
					</p>
				</td>
			</tr>
			
			<tr>
				<th scope="row">
					<label for="agoda_cache_duration"><?php esc_html_e( 'Cache Duration (seconds)', 'agoda-booking' ); ?></label>
				</th>
				<td>
					<input 
						type="number" 
						id="agoda_cache_duration" 
						name="agoda_cache_duration" 
						value="<?php echo esc_attr( get_option( 'agoda_cache_duration', 3600 ) ); ?>" 
						class="small-text"
						min="0"
					/>
					<p class="description">
						<?php esc_html_e( 'How long to cache search results (in seconds). Default: 3600 (1 hour). Set to 0 to disable caching.', 'agoda-booking' ); ?>
					</p>
				</td>
			</tr>
			
			<tr>
				<th scope="row">
					<label for="agoda_rate_limit_enabled"><?php esc_html_e( 'Enable Rate Limiting', 'agoda-booking' ); ?></label>
				</th>
				<td>
					<input 
						type="checkbox" 
						id="agoda_rate_limit_enabled" 
						name="agoda_rate_limit_enabled" 
						value="1"
						<?php checked( get_option( 'agoda_rate_limit_enabled', false ), true ); ?>
					/>
					<p class="description">
						<?php esc_html_e( 'Enable rate limiting to prevent API abuse and respect API quotas. This limits the number of requests per user/IP address.', 'agoda-booking' ); ?>
					</p>
				</td>
			</tr>
			
			<tr class="agoda-rate-limit-settings" style="<?php echo get_option( 'agoda_rate_limit_enabled', false ) ? '' : 'display: none;'; ?>">
				<th scope="row">
					<label for="agoda_rate_limit_max"><?php esc_html_e( 'Max Requests', 'agoda-booking' ); ?></label>
				</th>
				<td>
					<input 
						type="number" 
						id="agoda_rate_limit_max" 
						name="agoda_rate_limit_max" 
						value="<?php echo esc_attr( get_option( 'agoda_rate_limit_max', 10 ) ); ?>" 
						class="small-text"
						min="1"
					/>
					<p class="description">
						<?php esc_html_e( 'Maximum number of requests allowed per time window. Default: 10.', 'agoda-booking' ); ?>
					</p>
				</td>
			</tr>
			
			<tr class="agoda-rate-limit-settings" style="<?php echo get_option( 'agoda_rate_limit_enabled', false ) ? '' : 'display: none;'; ?>">
				<th scope="row">
					<label for="agoda_rate_limit_window"><?php esc_html_e( 'Time Window (seconds)', 'agoda-booking' ); ?></label>
				</th>
				<td>
					<input 
						type="number" 
						id="agoda_rate_limit_window" 
						name="agoda_rate_limit_window" 
						value="<?php echo esc_attr( get_option( 'agoda_rate_limit_window', 60 ) ); ?>" 
						class="small-text"
						min="1"
					/>
					<p class="description">
						<?php esc_html_e( 'Time window in seconds for rate limiting. Default: 60 (1 minute).', 'agoda-booking' ); ?>
					</p>
				</td>
			</tr>
			
			<!-- Content API Settings -->
			<tr>
				<th scope="row" colspan="2">
					<h2 style="margin: 20px 0 10px 0; padding-bottom: 10px; border-bottom: 1px solid #ddd;">
						<?php esc_html_e( 'Content API Settings (Optional)', 'agoda-booking' ); ?>
					</h2>
					<div class="notice notice-info inline" style="margin: 10px 0; padding: 10px;">
						<p style="margin: 0;">
							<strong>⚠️ <?php esc_html_e( 'Important:', 'agoda-booking' ); ?></strong>
							<?php esc_html_e( 'Content API uses DIFFERENT credentials from Affiliate Lite API above!', 'agoda-booking' ); ?>
						</p>
						<ul style="margin: 10px 0 0 20px; line-height: 1.8;">
							<li>
								<strong><?php esc_html_e( 'Affiliate Lite API (above):', 'agoda-booking' ); ?></strong>
								<?php esc_html_e( 'Uses Site ID + API Key in Authorization header', 'agoda-booking' ); ?>
							</li>
							<li>
								<strong><?php esc_html_e( 'Content API (below):', 'agoda-booking' ); ?></strong>
								<?php esc_html_e( 'Uses Token + Site ID in query parameters', 'agoda-booking' ); ?>
							</li>
							<li>
								<?php esc_html_e( 'These are TWO DIFFERENT APIs with different authentication methods!', 'agoda-booking' ); ?>
							</li>
							<li>
								<?php esc_html_e( 'Content API Site ID may be the same as or different from Affiliate Lite API Site ID - check with Agoda.', 'agoda-booking' ); ?>
							</li>
						</ul>
					</div>
					<p class="description" style="margin-bottom: 15px;">
						<?php esc_html_e( 'Content API is used for City Management feature. If you want to use City Management to search and find city IDs, please configure these settings. You need to request Content API access separately from Agoda.', 'agoda-booking' ); ?>
					</p>
				</th>
			</tr>
			
			<tr>
				<th scope="row">
					<label for="agoda_content_api_token"><?php esc_html_e( 'Content API Token', 'agoda-booking' ); ?></label>
				</th>
				<td>
					<input 
						type="text" 
						id="agoda_content_api_token" 
						name="agoda_content_api_token" 
						value="<?php echo esc_attr( get_option( 'agoda_content_api_token', '' ) ); ?>" 
						class="regular-text"
					/>
					<p class="description">
						<?php esc_html_e( 'Your Content API token (NOT the same as API Key above). Get this from Agoda Developer Portal or your account manager. This is different from the API Key used for Affiliate Lite API.', 'agoda-booking' ); ?>
					</p>
				</td>
			</tr>
			
			<tr>
				<th scope="row">
					<label for="agoda_content_api_site_id"><?php esc_html_e( 'Content API Site ID', 'agoda-booking' ); ?></label>
				</th>
				<td>
					<input 
						type="text" 
						id="agoda_content_api_site_id" 
						name="agoda_content_api_site_id" 
						value="<?php echo esc_attr( get_option( 'agoda_content_api_site_id', '' ) ); ?>" 
						class="regular-text"
					/>
					<p class="description">
						<?php esc_html_e( 'Site ID for Content API. This may be the same as or different from your Affiliate Lite API Site ID (above). Check with Agoda to confirm. If you\'re not sure, you can try using the same Site ID as Affiliate Lite API first.', 'agoda-booking' ); ?>
					</p>
				</td>
			</tr>
			
			<tr>
				<th scope="row">
					<label for="agoda_content_api_base_url"><?php esc_html_e( 'Content API Base URL', 'agoda-booking' ); ?></label>
				</th>
				<td>
					<input 
						type="text" 
						id="agoda_content_api_base_url" 
						name="agoda_content_api_base_url" 
						value="<?php echo esc_attr( get_option( 'agoda_content_api_base_url', 'https://affiliateapi7643.agoda.com' ) ); ?>" 
						class="regular-text"
					/>
					<p class="description">
						<?php esc_html_e( 'Base URL for Content API. Default: https://affiliateapi7643.agoda.com', 'agoda-booking' ); ?>
					</p>
				</td>
			</tr>
		</table>
		
		<?php submit_button( __( 'Save Settings', 'agoda-booking' ), 'primary', 'submit', true, array( 'id' => 'agoda-save-settings' ) ); ?>
	</form>
	
	<div class="agoda-booking-test-connection">
		<h2><?php esc_html_e( 'Test Connection', 'agoda-booking' ); ?></h2>
		<p class="description">
			<?php esc_html_e( 'Test your API credentials by clicking the button below. This will perform a test search to verify your Site ID and API Key are correct.', 'agoda-booking' ); ?>
		</p>
		<button type="button" id="agoda-test-connection" class="button button-secondary">
			<?php esc_html_e( 'Test Connection', 'agoda-booking' ); ?>
		</button>
		<span class="spinner"></span>
		<div id="agoda-test-result"></div>
	</div>
	
	<div class="agoda-booking-help-section" style="margin-top: 30px; padding: 20px; background: #f9f9f9; border: 1px solid #e0e0e0; border-radius: 4px;">
		<h2><?php esc_html_e( 'How to Get Your Credentials', 'agoda-booking' ); ?></h2>
		<div style="margin-top: 15px;">
			<h3><?php esc_html_e( 'Important: Site ID vs CID', 'agoda-booking' ); ?></h3>
			<p style="margin-left: 20px; line-height: 1.8; color: #d63638; font-weight: 600;">
				<?php esc_html_e( '⚠️ The "Site ID" shown in Affiliate Dashboard is actually your CID, NOT the Site ID for API authentication!', 'agoda-booking' ); ?>
			</p>
			
			<h3 style="margin-top: 20px;"><?php esc_html_e( 'How to Get Your Credentials:', 'agoda-booking' ); ?></h3>
			<ol style="margin-left: 20px; line-height: 1.8;">
				<li><strong><?php esc_html_e( 'CID (Customer ID):', 'agoda-booking' ); ?></strong>
					<ul style="margin-left: 20px; margin-top: 5px;">
						<li><?php esc_html_e( 'Log in to your Agoda Affiliate Dashboard at', 'agoda-booking' ); ?> <a href="https://partners.agoda.com" target="_blank" rel="noopener noreferrer">partners.agoda.com</a></li>
						<li><?php esc_html_e( 'Go to Profile → Manage Your Sites', 'agoda-booking' ); ?></li>
						<li><?php esc_html_e( 'The "Site ID" shown there (e.g., 1425703) is your CID - use this in the CID field above', 'agoda-booking' ); ?></li>
					</ul>
				</li>
				<li><strong><?php esc_html_e( 'Site ID & API Key (for Affiliate Lite API):', 'agoda-booking' ); ?></strong>
					<ul style="margin-left: 20px; margin-top: 5px;">
						<li><?php esc_html_e( 'Note: Affiliate Lite API uses Authorization header (different from Content API)', 'agoda-booking' ); ?></li>
						<li><?php esc_html_e( 'The Site ID for Affiliate Lite API may be the same as your CID, or different', 'agoda-booking' ); ?></li>
						<li><?php esc_html_e( 'Contact your Agoda account manager to get:', 'agoda-booking' ); ?>
							<ul style="margin-left: 20px; margin-top: 5px;">
								<li><?php esc_html_e( 'Site ID for Affiliate Lite API authentication', 'agoda-booking' ); ?></li>
								<li><?php esc_html_e( 'API Key for Affiliate Lite API authentication', 'agoda-booking' ); ?></li>
							</ul>
						</li>
						<li><?php esc_html_e( 'Or visit the Agoda Developer Portal at', 'agoda-booking' ); ?> <a href="https://developer.agoda.com" target="_blank" rel="noopener noreferrer">developer.agoda.com</a> <?php esc_html_e( 'to request API access', 'agoda-booking' ); ?></li>
					</ul>
				</li>
				<li><strong><?php esc_html_e( 'Token & Site ID (for Content API):', 'agoda-booking' ); ?></strong>
					<ul style="margin-left: 20px; margin-top: 5px;">
						<li><?php esc_html_e( 'Content API uses GET with token + site_id in query parameters', 'agoda-booking' ); ?></li>
						<li><?php esc_html_e( 'Token is DIFFERENT from API Key (used in Affiliate Lite API)', 'agoda-booking' ); ?></li>
						<li><?php esc_html_e( 'Content API Site ID may be the same as or different from Affiliate Lite API Site ID', 'agoda-booking' ); ?></li>
						<li><?php esc_html_e( 'How to get Content API credentials:', 'agoda-booking' ); ?>
							<ul style="margin-left: 20px; margin-top: 5px;">
								<li><?php esc_html_e( 'Visit Agoda Developer Portal:', 'agoda-booking' ); ?> <a href="https://developer.agoda.com/demand/docs/content-api" target="_blank" rel="noopener noreferrer">developer.agoda.com/demand/docs/content-api</a></li>
								<li><?php esc_html_e( 'Contact your Agoda Account Manager to request Content API access', 'agoda-booking' ); ?></li>
								<li><?php esc_html_e( 'Note: Content API access may need to be requested separately from Affiliate Lite API', 'agoda-booking' ); ?></li>
							</ul>
						</li>
					</ul>
				</li>
				<li><strong><?php esc_html_e( 'Important Note:', 'agoda-booking' ); ?></strong>
					<ul style="margin-left: 20px; margin-top: 5px; color: #d63638;">
						<li><?php esc_html_e( 'Content API uses GET with token + site_id in query parameters', 'agoda-booking' ); ?></li>
						<li><?php esc_html_e( 'Affiliate Lite API uses POST with Authorization header (siteId:apiKey)', 'agoda-booking' ); ?></li>
						<li><?php esc_html_e( 'These are TWO DIFFERENT APIs with different authentication methods!', 'agoda-booking' ); ?></li>
						<li><?php esc_html_e( 'You need to request Content API access separately from Agoda', 'agoda-booking' ); ?></li>
					</ul>
				</li>
			</ol>
			
			<h3 style="margin-top: 20px;"><?php esc_html_e( 'Useful Links:', 'agoda-booking' ); ?></h3>
			<ul style="margin-left: 20px; line-height: 1.8;">
				<li><a href="https://developer.agoda.com/demand/docs/getting-started" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Agoda Developer Portal - Getting Started', 'agoda-booking' ); ?></a></li>
				<li><a href="https://developer.agoda.com/demand/docs/content-api" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Agoda Content API Documentation', 'agoda-booking' ); ?></a></li>
				<li><a href="https://developer.agoda.com/demand/docs/best-practices-certification-process" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Agoda Best Practices & Certification', 'agoda-booking' ); ?></a></li>
				<li><a href="https://partners.agoda.com" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Agoda Affiliate Dashboard', 'agoda-booking' ); ?></a></li>
			</ul>
		</div>
	</div>
</div>
