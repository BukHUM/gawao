/**
 * Admin scripts for Agoda Booking plugin
 *
 * @package Agoda_Booking
 */

(function($) {
	'use strict';

	$(document).ready(function() {
		// Toggle rate limit settings visibility
		$('#agoda_rate_limit_enabled').on('change', function() {
			if ($(this).is(':checked')) {
				$('.agoda-rate-limit-settings').show();
			} else {
				$('.agoda-rate-limit-settings').hide();
			}
		});

		// Test connection button
		$('#agoda-test-connection').on('click', function(e) {
			e.preventDefault();
			
			var $button = $(this);
			var $spinner = $('.agoda-booking-test-connection .spinner');
			var $result = $('#agoda-test-result');
			
			// Get current form values
			var siteId = $('#agoda_site_id').val();
			var apiKey = $('#agoda_api_key').val();
			
			// Validate inputs
			if (!siteId || !apiKey) {
				$result.html('<div class="notice notice-error"><p>' + agodaBookingAdmin.strings.invalidCred + '</p></div>');
				return;
			}
			
			// Disable button and show spinner
			$button.prop('disabled', true);
			$spinner.addClass('is-active');
			$result.html('<p>' + agodaBookingAdmin.strings.testing + '</p>');
			
			// Send AJAX request
			$.ajax({
				url: agodaBookingAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'agoda_test_connection',
					nonce: agodaBookingAdmin.nonce,
					site_id: siteId,
					api_key: apiKey
				},
				success: function(response) {
					$spinner.removeClass('is-active');
					$button.prop('disabled', false);
					
					if (response.success) {
						var message = response.data.message;
						if (response.data.count !== undefined) {
							message += ' ' + response.data.count + ' ' + (response.data.count === 1 ? 'hotel' : 'hotels') + ' found.';
						}
						$result.html('<div class="notice notice-success"><p>' + message + '</p></div>');
					} else {
						var errorMsg = response.data.message || agodaBookingAdmin.strings.error;
						$result.html('<div class="notice notice-error"><p>' + errorMsg + '</p></div>');
					}
				},
				error: function() {
					$spinner.removeClass('is-active');
					$button.prop('disabled', false);
					$result.html('<div class="notice notice-error"><p>' + agodaBookingAdmin.strings.error + '</p></div>');
				}
			});
		});

		// Test Connection from Dashboard
		$('#agoda-test-connection-dashboard').on('click', function(e) {
			e.preventDefault();
			
			var $button = $(this);
			var $result = $('#agoda-dashboard-actions-result');
			
			$button.prop('disabled', true);
			$result.html('<p>' + agodaBookingAdmin.strings.testing + '</p>');
			
			// Get Site ID and API Key from settings (we'll need to get them via AJAX or store in JS)
			var siteId = $('#agoda_site_id').val() || '';
			var apiKey = $('#agoda_api_key').val() || '';
			
			// If not on settings page, we need to get from server
			if (!siteId || !apiKey) {
				$.ajax({
					url: agodaBookingAdmin.ajaxUrl,
					type: 'POST',
					data: {
						action: 'agoda_get_dashboard_stats',
						nonce: agodaBookingAdmin.nonceDashboard
					},
					success: function(response) {
						if (response.success) {
							// Try to get credentials from settings page or use stored values
							siteId = $('#agoda_site_id').val() || '';
							apiKey = $('#agoda_api_key').val() || '';
							
							if (!siteId || !apiKey) {
								$result.html('<div class="notice notice-warning"><p>' + agodaBookingAdmin.strings.invalidCred + '</p></div>');
								$button.prop('disabled', false);
								return;
							}
						}
					}
				});
			}
			
			$.ajax({
				url: agodaBookingAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'agoda_test_connection',
					nonce: agodaBookingAdmin.nonce,
					site_id: siteId,
					api_key: apiKey
				},
				success: function(response) {
					$button.prop('disabled', false);
					
					if (response.success) {
						var message = response.data.message;
						if (response.data.count !== undefined) {
							message += ' ' + response.data.count + ' ' + (response.data.count === 1 ? 'hotel' : 'hotels') + ' found.';
						}
						$result.html('<div class="notice notice-success is-dismissible"><p>' + message + '</p></div>');
					} else {
						var errorMsg = response.data.message || agodaBookingAdmin.strings.error;
						$result.html('<div class="notice notice-error is-dismissible"><p>' + errorMsg + '</p></div>');
					}
				},
				error: function() {
					$button.prop('disabled', false);
					$result.html('<div class="notice notice-error is-dismissible"><p>' + agodaBookingAdmin.strings.error + '</p></div>');
				}
			});
		});

		// Clear Cache from Dashboard
		$('#agoda-clear-cache-dashboard').on('click', function(e) {
			e.preventDefault();
			
			var $button = $(this);
			var $result = $('#agoda-dashboard-actions-result');
			
			if (!confirm('Are you sure you want to clear all cache?')) {
				return;
			}
			
			$button.prop('disabled', true);
			$result.html('<p>' + agodaBookingAdmin.strings.clearingCache + '</p>');
			
			$.ajax({
				url: agodaBookingAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'agoda_clear_cache',
					nonce: agodaBookingAdmin.nonceClearCache
				},
				success: function(response) {
					$button.prop('disabled', false);
					
					if (response.success) {
						$result.html('<div class="notice notice-success is-dismissible"><p>' + response.data.message + '</p></div>');
						// Reload page after 1 second to refresh stats
						setTimeout(function() {
							location.reload();
						}, 1000);
					} else {
						var errorMsg = response.data.message || 'Failed to clear cache.';
						$result.html('<div class="notice notice-error is-dismissible"><p>' + errorMsg + '</p></div>');
					}
				},
				error: function() {
					$button.prop('disabled', false);
					$result.html('<div class="notice notice-error is-dismissible"><p>Failed to clear cache.</p></div>');
				}
			});
		});
	});

})(jQuery);
