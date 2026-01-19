/**
 * Bulk Operations JavaScript for Admin
 *
 * @package Agoda_Booking
 */

(function($) {
	'use strict';

	$(document).ready(function() {
		var bulkSearchResults = [];

		// Toggle cache clear method
		$('#agoda-bulk-cache-method').on('change', function() {
			if ($(this).val() === 'cities') {
				$('#agoda-bulk-cache-cities-row').show();
				$('#agoda-bulk-cache-date-row').hide();
				$('#agoda-bulk-cache-city-ids').prop('required', true);
				$('#agoda-bulk-cache-date-from').prop('required', false);
				$('#agoda-bulk-cache-date-to').prop('required', false);
			} else {
				$('#agoda-bulk-cache-cities-row').hide();
				$('#agoda-bulk-cache-date-row').show();
				$('#agoda-bulk-cache-city-ids').prop('required', false);
				$('#agoda-bulk-cache-date-from').prop('required', true);
				$('#agoda-bulk-cache-date-to').prop('required', true);
			}
		});

		// Bulk Hotel Search
		$('#agoda-bulk-hotel-search-form').on('submit', function(e) {
			e.preventDefault();
			
			var $form = $(this);
			var $button = $('#agoda-bulk-search-submit');
			var $spinner = $('#agoda-bulk-search-spinner');
			var $results = $('#agoda-bulk-search-results');
			var $resultsContainer = $('#agoda-bulk-results-container');
			var $resultsInfo = $('#agoda-bulk-results-info');
			
			$button.prop('disabled', true);
			$spinner.addClass('is-active');
			$results.hide();
			$resultsContainer.empty();
			$resultsInfo.empty();
			
			// Get hotel IDs
			var hotelIdsStr = $('#agoda-bulk-hotel-ids').val();
			if (!hotelIdsStr.trim()) {
				alert('Please enter hotel IDs.');
				$button.prop('disabled', false);
				$spinner.removeClass('is-active');
				return;
			}
			
			// Collect form data
			var formData = {
				action: 'agoda_bulk_hotel_search',
				nonce: agodaBookingAdmin.nonceBulkOperations,
				hotel_ids: hotelIdsStr,
				check_in: $('#agoda-bulk-check-in').val(),
				check_out: $('#agoda-bulk-check-out').val(),
				adults: $('#agoda-bulk-adults').val(),
				currency: $('#agoda-bulk-currency').val()
			};
			
			$.ajax({
				url: agodaBookingAdmin.ajaxUrl,
				type: 'POST',
				data: formData,
				success: function(response) {
					$spinner.removeClass('is-active');
					$button.prop('disabled', false);
					
					if (response.success) {
						bulkSearchResults = response.data.results || [];
						displayBulkResults(response.data);
					} else {
						alert(response.data.message || 'Search failed.');
					}
				},
				error: function() {
					$spinner.removeClass('is-active');
					$button.prop('disabled', false);
					alert('An error occurred while searching. Please try again.');
				}
			});
		});

		// Display bulk search results
		function displayBulkResults(data) {
			var $container = $('#agoda-bulk-results-container');
			var $info = $('#agoda-bulk-results-info');
			
			$container.empty();
			$info.empty();
			
			var count = data.count || 0;
			var results = data.results || [];
			
			$info.html('<p><strong>Found ' + count + ' hotel(s)</strong></p>');
			
			if (results.length === 0) {
				$container.html('<p>No hotels found.</p>');
				$('#agoda-bulk-search-results').show();
				return;
			}
			
			// Create results table
			var $table = $('<table class="wp-list-table widefat fixed striped"></table>');
			var $thead = $('<thead></thead>');
			var $tbody = $('<tbody></tbody>');
			
			// Table header
			$thead.append(
				$('<tr></tr>').append(
					$('<th>').text('Hotel ID'),
					$('<th>').text('Hotel Name'),
					$('<th>').text('Price'),
					$('<th>').text('Rating'),
					$('<th>').text('Landing URL'),
					$('<th>').text('Actions')
				)
			);
			
			// Table rows
			results.forEach(function(hotel) {
				var $row = $('<tr></tr>');
				
				// Hotel ID
				$row.append($('<td>').text(hotel.hotelId || 'N/A'));
				
				// Hotel Name
				$row.append($('<td>').html('<strong>' + escapeHtml(hotel.hotelName || 'N/A') + '</strong>'));
				
				// Price
				var priceText = '';
				if (hotel.dailyRate) {
					priceText = (hotel.currency || 'USD') + ' ' + parseFloat(hotel.dailyRate).toFixed(2);
					if (hotel.crossedOutRate && hotel.crossedOutRate > hotel.dailyRate) {
						priceText += ' <span style="text-decoration: line-through; color: #999;">' + 
							(hotel.currency || 'USD') + ' ' + parseFloat(hotel.crossedOutRate).toFixed(2) + 
							'</span>';
					}
					if (hotel.discountPercentage) {
						priceText += ' <span style="color: #d63638;">-' + hotel.discountPercentage + '%</span>';
					}
				}
				$row.append($('<td>').html(priceText || 'N/A'));
				
				// Rating
				var ratingText = '';
				if (hotel.starRating) {
					ratingText = hotel.starRating + ' ★';
				}
				if (hotel.reviewScore) {
					ratingText += (ratingText ? ' | ' : '') + hotel.reviewScore + '/10';
				}
				$row.append($('<td>').text(ratingText || 'N/A'));
				
				// Landing URL
				var $urlCell = $('<td>');
				if (hotel.landingURL) {
					var $urlLink = $('<a>')
						.attr('href', hotel.landingURL)
						.attr('target', '_blank')
						.attr('rel', 'noopener noreferrer')
						.text(hotel.landingURL.substring(0, 40) + '...');
					$urlCell.append($urlLink);
				} else {
					$urlCell.text('N/A');
				}
				$row.append($urlCell);
				
				// Actions
				var $actionsCell = $('<td>');
				if (hotel.landingURL) {
					$actionsCell.append(
						$('<button>')
							.addClass('button button-small')
							.text('Copy URL')
							.data('url', hotel.landingURL)
							.on('click', function() {
								copyToClipboard($(this).data('url'));
								$(this).text('Copied!').prop('disabled', true);
								setTimeout(function() {
									$(this).text('Copy URL').prop('disabled', false);
								}.bind(this), 2000);
							})
					);
				}
				$row.append($actionsCell);
				
				$tbody.append($row);
			});
			
			$table.append($thead).append($tbody);
			$container.append($table);
			$('#agoda-bulk-search-results').show();
		}

		// Export bulk results
		$('#agoda-export-bulk-results').on('click', function() {
			if (bulkSearchResults.length === 0) {
				alert('No results to export.');
				return;
			}
			
			// Generate CSV
			var csv = 'Hotel ID,Hotel Name,Price,Currency,Star Rating,Review Score,Landing URL\n';
			
			bulkSearchResults.forEach(function(hotel) {
				var row = [
					hotel.hotelId || '',
					'"' + (hotel.hotelName || '').replace(/"/g, '""') + '"',
					hotel.dailyRate || '',
					hotel.currency || '',
					hotel.starRating || '',
					hotel.reviewScore || '',
					hotel.landingURL || ''
				];
				csv += row.join(',') + '\n';
			});
			
			// Download CSV
			var blob = new Blob([csv], { type: 'text/csv' });
			var url = window.URL.createObjectURL(blob);
			var a = document.createElement('a');
			a.href = url;
			a.download = 'agoda-bulk-search-results-' + new Date().toISOString().split('T')[0] + '.csv';
			document.body.appendChild(a);
			a.click();
			document.body.removeChild(a);
			window.URL.revokeObjectURL(url);
		});

		// Bulk Cache Clear
		$('#agoda-bulk-cache-clear-form').on('submit', function(e) {
			e.preventDefault();
			
			var $form = $(this);
			var $button = $('#agoda-bulk-cache-clear-submit');
			var $spinner = $('#agoda-bulk-cache-spinner');
			var $result = $('#agoda-bulk-cache-result');
			
			$button.prop('disabled', true);
			$spinner.addClass('is-active');
			$result.empty();
			
			var method = $('#agoda-bulk-cache-method').val();
			var formData = {
				action: 'agoda_bulk_cache_clear',
				nonce: agodaBookingAdmin.nonceBulkOperations,
				method: method
			};
			
			if (method === 'cities') {
				formData.city_ids = $('#agoda-bulk-cache-city-ids').val();
			} else {
				formData.date_from = $('#agoda-bulk-cache-date-from').val();
				formData.date_to = $('#agoda-bulk-cache-date-to').val();
			}
			
			$.ajax({
				url: agodaBookingAdmin.ajaxUrl,
				type: 'POST',
				data: formData,
				success: function(response) {
					$spinner.removeClass('is-active');
					$button.prop('disabled', false);
					
					if (response.success) {
						$result.html('<div class="notice notice-success is-dismissible"><p>' + response.data.message + '</p></div>');
					} else {
						$result.html('<div class="notice notice-error is-dismissible"><p>' + (response.data.message || 'Failed to clear cache.') + '</p></div>');
					}
				},
				error: function() {
					$spinner.removeClass('is-active');
					$button.prop('disabled', false);
					$result.html('<div class="notice notice-error is-dismissible"><p>Failed to clear cache.</p></div>');
				}
			});
		});

		// Copy to clipboard
		function copyToClipboard(text) {
			var $temp = $('<textarea>');
			$('body').append($temp);
			$temp.val(text).select();
			document.execCommand('copy');
			$temp.remove();
		}

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
