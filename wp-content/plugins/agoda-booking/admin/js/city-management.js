/**
 * City Management JavaScript for Admin
 *
 * @package Agoda_Booking
 */

(function($) {
	'use strict';

	$(document).ready(function() {
		var currentPage = 1;
		var currentSearch = '';
		var currentCountry = '';

		// Search form submission
		$('#agoda-city-search-form').on('submit', function(e) {
			e.preventDefault();
			currentPage = 1;
			currentSearch = $('#agoda-city-search').val();
			currentCountry = $('#agoda-city-country-filter').val();
			loadCities();
		});

		// Reset filters
		$('#agoda-reset-city-filters').on('click', function() {
			$('#agoda-city-search').val('');
			$('#agoda-city-country-filter').val('');
			currentPage = 1;
			currentSearch = '';
			currentCountry = '';
			loadCities();
		});

		// Refresh cities
		$('#agoda-refresh-cities').on('click', function() {
			// Clear cache and reload
			loadCities(true);
		});

		// Export cities
		$('#agoda-export-cities').on('click', function() {
			exportCities();
		});

		// Load cities
		function loadCities(clearCache) {
			$('#agoda-cities-loading').show();
			$('#agoda-cities-container').empty();
			
			if (clearCache) {
				// Clear cache by deleting transient
				$.ajax({
					url: agodaBookingAdmin.ajaxUrl,
					type: 'POST',
					data: {
						action: 'agoda_clear_cache',
						nonce: agodaBookingAdmin.nonceClearCache,
						pattern: 'content_api_feed_3'
					},
					success: function() {
						fetchCities();
					}
				});
			} else {
				fetchCities();
			}
		}

		// Fetch cities from API
		function fetchCities() {
			$.ajax({
				url: agodaBookingAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'agoda_get_cities',
					nonce: agodaBookingAdmin.nonceCityManagement,
					search: currentSearch,
					country: currentCountry,
					page: currentPage,
					per_page: 50
				},
				success: function(response) {
					$('#agoda-cities-loading').hide();
					
					if (response.success) {
						displayCities(response.data);
					} else {
						$('#agoda-cities-container').html('<p class="error">' + (response.data.message || 'Failed to load cities.') + '</p>');
					}
				},
				error: function() {
					$('#agoda-cities-loading').hide();
					$('#agoda-cities-container').html('<p class="error">Failed to load cities.</p>');
				}
			});
		}

		// Display cities
		function displayCities(data) {
			var $container = $('#agoda-cities-container');
			$container.empty();
			
			if (data.cities.length === 0) {
				$container.html('<p>' + 'No cities found.' + '</p>');
				$('#agoda-cities-pagination').empty();
				return;
			}
			
			// Create table
			var $table = $('<table class="wp-list-table widefat fixed striped"></table>');
			var $thead = $('<thead></thead>');
			var $tbody = $('<tbody></tbody>');
			
			// Table header
			$thead.append(
				$('<tr></tr>').append(
					$('<th>').text('City ID'),
					$('<th>').text('City Name'),
					$('<th>').text('Country'),
					$('<th>').text('Country Code'),
					$('<th>').text('Actions')
				)
			);
			
			// Table rows
			data.cities.forEach(function(city) {
				var $row = $('<tr></tr>');
				
				// City ID
				var cityId = city.CityId || city.city_id || 'N/A';
				$row.append($('<td>').html('<strong>' + cityId + '</strong>'));
				
				// City Name
				var cityName = city.CityName || city.city_name || 'N/A';
				$row.append($('<td>').html('<strong>' + escapeHtml(cityName) + '</strong>'));
				
				// Country
				var countryName = city.CountryName || city.country_name || 'N/A';
				$row.append($('<td>').text(countryName));
				
				// Country Code
				var countryCode = city.CountryCode || city.country_code || 'N/A';
				$row.append($('<td>').text(countryCode));
				
				// Actions
				var $actionsCell = $('<td>');
				$actionsCell.append(
					$('<button>')
						.addClass('button button-small')
						.text('View Details')
						.data('city-id', cityId)
						.on('click', function() {
							showCityDetails($(this).data('city-id'));
						})
				);
				$actionsCell.append(' ');
				$actionsCell.append(
					$('<button>')
						.addClass('button button-small')
						.text('Copy City ID')
						.data('city-id', cityId)
						.on('click', function() {
							copyToClipboard($(this).data('city-id'));
							$(this).text('Copied!').prop('disabled', true);
							setTimeout(function() {
								$(this).text('Copy City ID').prop('disabled', false);
							}.bind(this), 2000);
						})
				);
				$row.append($actionsCell);
				
				$tbody.append($row);
			});
			
			$table.append($thead).append($tbody);
			$container.append($table);
			
			// Pagination
			displayPagination(data);
		}

		// Display pagination
		function displayPagination(data) {
			var $pagination = $('#agoda-cities-pagination');
			$pagination.empty();
			
			if (data.total_pages <= 1) {
				return;
			}
			
			var $nav = $('<div class="agoda-pagination"></div>');
			
			// Previous button
			if (data.page > 1) {
				$nav.append(
					$('<button>')
						.addClass('button')
						.text('← Previous')
						.on('click', function() {
							currentPage = data.page - 1;
							loadCities();
						})
				);
			}
			
			// Page info
			$nav.append(
				$('<span>')
					.addClass('agoda-page-info')
					.text('Page ' + data.page + ' of ' + data.total_pages + ' (' + data.total + ' cities)')
			);
			
			// Next button
			if (data.page < data.total_pages) {
				$nav.append(
					$('<button>')
						.addClass('button')
						.text('Next →')
						.on('click', function() {
							currentPage = data.page + 1;
							loadCities();
						})
				);
			}
			
			$pagination.append($nav);
		}

		// Show city details
		function showCityDetails(cityId) {
			var $modal = $('#agoda-city-modal');
			var $modalBody = $('#agoda-city-modal-body');
			
			$modalBody.html('<p>Loading...</p>');
			$modal.show();
			
			$.ajax({
				url: agodaBookingAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'agoda_get_city_details',
					nonce: agodaBookingAdmin.nonceCityManagement,
					city_id: cityId
				},
				success: function(response) {
					if (response.success) {
						var city = response.data.city;
						var html = '<table class="form-table">';
						
						// Display all city fields
						for (var key in city) {
							if (city.hasOwnProperty(key)) {
								html += '<tr><th>' + escapeHtml(key) + ':</th><td>' + escapeHtml(city[key]) + '</td></tr>';
							}
						}
						
						html += '</table>';
						$modalBody.html(html);
					} else {
						$modalBody.html('<p class="error">' + (response.data.message || 'Failed to load city details.') + '</p>');
					}
				},
				error: function() {
					$modalBody.html('<p class="error">Failed to load city details.</p>');
				}
			});
		}

		// Export cities
		function exportCities() {
			$.ajax({
				url: agodaBookingAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'agoda_get_cities',
					nonce: agodaBookingAdmin.nonceCityManagement,
					search: currentSearch,
					country: currentCountry,
					per_page: 999999
				},
				success: function(response) {
					if (response.success && response.data.cities.length > 0) {
						// Generate CSV
						var csv = 'City ID,City Name,Country,Country Code\n';
						
						response.data.cities.forEach(function(city) {
							var row = [
								city.CityId || city.city_id || '',
								'"' + ((city.CityName || city.city_name || '').replace(/"/g, '""')) + '"',
								'"' + ((city.CountryName || city.country_name || '').replace(/"/g, '""')) + '"',
								city.CountryCode || city.country_code || ''
							];
							csv += row.join(',') + '\n';
						});
						
						// Download CSV
						var blob = new Blob([csv], { type: 'text/csv' });
						var url = window.URL.createObjectURL(blob);
						var a = document.createElement('a');
						a.href = url;
						a.download = 'agoda-cities-' + new Date().toISOString().split('T')[0] + '.csv';
						document.body.appendChild(a);
						a.click();
						document.body.removeChild(a);
						window.URL.revokeObjectURL(url);
					} else {
						alert('No cities to export.');
					}
				},
				error: function() {
					alert('Failed to export cities.');
				}
			});
		}

		// Close modal
		$('.agoda-modal-close').on('click', function() {
			$('#agoda-city-modal').hide();
		});

		$(window).on('click', function(e) {
			var $modal = $('#agoda-city-modal');
			if ($(e.target).is($modal)) {
				$modal.hide();
			}
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
