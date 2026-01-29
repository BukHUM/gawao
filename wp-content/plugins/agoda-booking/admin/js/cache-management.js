/**
 * Cache Management JavaScript for Admin
 *
 * @package Agoda_Booking
 */

(function($) {
	'use strict';

	$(document).ready(function() {
		// Clear All Cache
		$('#agoda-clear-all-cache').on('click', function(e) {
			e.preventDefault();
			
			if (!confirm('Are you sure you want to clear ALL cache? This action cannot be undone.')) {
				return;
			}
			
			var $button = $(this);
			var $result = $('#agoda-cache-operations-result');
			
			$button.prop('disabled', true);
			$result.html('<p>Clearing cache...</p>');
			
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
						setTimeout(function() {
							location.reload();
						}, 1000);
					} else {
						$result.html('<div class="notice notice-error is-dismissible"><p>' + (response.data.message || 'Failed to clear cache.') + '</p></div>');
					}
				},
				error: function() {
					$button.prop('disabled', false);
					$result.html('<div class="notice notice-error is-dismissible"><p>Failed to clear cache.</p></div>');
				}
			});
		});

		// Clear Expired Cache
		$('#agoda-clear-expired-cache').on('click', function(e) {
			e.preventDefault();
			
			var $button = $(this);
			var $result = $('#agoda-cache-operations-result');
			
			$button.prop('disabled', true);
			$result.html('<p>Clearing expired cache...</p>');
			
			$.ajax({
				url: agodaBookingAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'agoda_clear_expired_cache',
					nonce: agodaBookingAdmin.nonceCacheManagement
				},
				success: function(response) {
					$button.prop('disabled', false);
					
					if (response.success) {
						$result.html('<div class="notice notice-success is-dismissible"><p>' + response.data.message + '</p></div>');
						setTimeout(function() {
							location.reload();
						}, 1000);
					} else {
						$result.html('<div class="notice notice-error is-dismissible"><p>' + (response.data.message || 'Failed to clear expired cache.') + '</p></div>');
					}
				},
				error: function() {
					$button.prop('disabled', false);
					$result.html('<div class="notice notice-error is-dismissible"><p>Failed to clear expired cache.</p></div>');
				}
			});
		});

		// Refresh Cache Stats
		$('#agoda-refresh-cache-stats').on('click', function(e) {
			e.preventDefault();
			location.reload();
		});

		// Clear Cache by Pattern
		$('#agoda-clear-pattern-form').on('submit', function(e) {
			e.preventDefault();
			
			var $form = $(this);
			var $button = $form.find('button[type="submit"]');
			var $result = $('#agoda-cache-operations-result');
			var pattern = $('#agoda-cache-pattern').val();
			
			if (!pattern) {
				if (!confirm('No pattern specified. This will clear ALL cache. Continue?')) {
					return;
				}
			}
			
			$button.prop('disabled', true);
			$result.html('<p>Clearing cache...</p>');
			
			$.ajax({
				url: agodaBookingAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'agoda_clear_cache_by_pattern',
					nonce: agodaBookingAdmin.nonceCacheManagement,
					pattern: pattern
				},
				success: function(response) {
					$button.prop('disabled', false);
					
					if (response.success) {
						$result.html('<div class="notice notice-success is-dismissible"><p>' + response.data.message + '</p></div>');
						$('#agoda-cache-pattern').val('');
						setTimeout(function() {
							location.reload();
						}, 1000);
					} else {
						$result.html('<div class="notice notice-error is-dismissible"><p>' + (response.data.message || 'Failed to clear cache.') + '</p></div>');
					}
				},
				error: function() {
					$button.prop('disabled', false);
					$result.html('<div class="notice notice-error is-dismissible"><p>Failed to clear cache.</p></div>');
				}
			});
		});

		// Delete Cache Entry
		$('.agoda-delete-cache-entry').on('click', function(e) {
			e.preventDefault();
			
			if (!confirm('Are you sure you want to delete this cache entry?')) {
				return;
			}
			
			var $button = $(this);
			var key = $button.data('key');
			var $row = $button.closest('tr');
			
			$button.prop('disabled', true);
			
			$.ajax({
				url: agodaBookingAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'agoda_delete_cache_entry',
					nonce: agodaBookingAdmin.nonceCacheManagement,
					key: key
				},
				success: function(response) {
					if (response.success) {
						$row.fadeOut(300, function() {
							$(this).remove();
						});
					} else {
						alert(response.data.message || 'Failed to delete cache entry.');
						$button.prop('disabled', false);
					}
				},
				error: function() {
					alert('Failed to delete cache entry.');
					$button.prop('disabled', false);
				}
			});
		});

		// View Cache Entry
		$('.agoda-view-cache-entry').on('click', function(e) {
			e.preventDefault();
			
			var $button = $(this);
			var key = $button.data('key');
			var $modal = $('#agoda-cache-modal');
			var $modalBody = $('#agoda-cache-modal-body');
			
			$button.prop('disabled', true);
			$modalBody.html('<p>Loading...</p>');
			$modal.show();
			
			$.ajax({
				url: agodaBookingAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'agoda_get_cache_entry',
					nonce: agodaBookingAdmin.nonceCacheManagement,
					key: key
				},
				success: function(response) {
					$button.prop('disabled', false);
					
					if (response.success) {
						var html = '<h3>Cache Key: <code>' + escapeHtml(response.data.key) + '</code></h3>';
						html += '<div class="agoda-cache-entry-content">';
						html += '<h4>Cache Value:</h4>';
						html += '<pre>' + escapeHtml(JSON.stringify(response.data.value, null, 2)) + '</pre>';
						html += '</div>';
						$modalBody.html(html);
					} else {
						$modalBody.html('<p class="error">' + (response.data.message || 'Failed to load cache entry.') + '</p>');
					}
				},
				error: function() {
					$button.prop('disabled', false);
					$modalBody.html('<p class="error">Failed to load cache entry.</p>');
				}
			});
		});

		// Close Modal
		$('.agoda-modal-close').on('click', function() {
			$('#agoda-cache-modal').hide();
		});

		$(window).on('click', function(e) {
			var $modal = $('#agoda-cache-modal');
			if ($(e.target).is($modal)) {
				$modal.hide();
			}
		});

		// Escape HTML
		function escapeHtml(text) {
			var map = {
				'&': '&amp;',
				'<': '&lt;',
				'>': '&gt;',
				'"': '&quot;',
				"'": '&#039;'
			};
			return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
		}
	});

})(jQuery);
