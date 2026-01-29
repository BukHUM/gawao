/**
 * Hotel Search Preview JavaScript for Admin
 *
 * @package Agoda_Booking
 */

(function($) {
	'use strict';

	$(document).ready(function() {
		var $form = $('#agoda-admin-search-form');
		var $searchType = $('#agoda-search-type');
		var $cityIdRow = $('#agoda-city-id-row');
		var $hotelIdsRow = $('#agoda-hotel-ids-row');
		var $childrenInput = $('#agoda-children');
		var $childrenAgesRow = $('#agoda-children-ages-row');
		var $childrenAgesContainer = $('#agoda-children-ages-container');
		var $resultsWrapper = $('#agoda-results-wrapper');
		var $errorContainer = $('#agoda-error-container');
		var $spinner = $('#agoda-search-spinner');
		var $submitButton = $('#agoda-search-submit');
		
		// City search autocomplete (if available)
		var $citySearch = $('#agoda-city-search');
		var $citySearchResults = $('#agoda-city-search-results');
		var $cityId = $('#agoda-city-id');
		var $cityIdDirect = $('#agoda-city-id-direct');
		var $selectedCity = $('#agoda-selected-city');
		var $selectedCityName = $('#agoda-selected-city-name');
		var citySearchTimeout = null;

		// Toggle search type fields
		$searchType.on('change', function() {
			if ($(this).val() === 'city') {
				$cityIdRow.show();
				$hotelIdsRow.hide();
				if ($cityId.length > 0) {
					$cityId.prop('required', true);
				} else if ($cityIdDirect.length > 0) {
					$cityIdDirect.prop('required', true);
				}
				$('#agoda-hotel-ids').prop('required', false);
			} else {
				$cityIdRow.hide();
				$hotelIdsRow.show();
				if ($cityId.length > 0) {
					$cityId.prop('required', false);
				}
				if ($cityIdDirect.length > 0) {
					$cityIdDirect.prop('required', false);
				}
				if ($citySearch.length > 0) {
					$citySearch.val('');
				}
				if ($selectedCity.length > 0) {
					$selectedCity.hide();
				}
				$('#agoda-hotel-ids').prop('required', true);
			}
		});

		// City search autocomplete (if available)
		if ($citySearch.length > 0) {
			$citySearch.on('input', function() {
				var searchTerm = $(this).val().trim();
				
				// Clear previous timeout
				if (citySearchTimeout) {
					clearTimeout(citySearchTimeout);
				}
				
				// Hide results if empty
				if (searchTerm.length < 2) {
					$citySearchResults.hide().empty();
					return;
				}
				
				// Debounce search
				citySearchTimeout = setTimeout(function() {
					searchCities(searchTerm);
				}, 300);
			});

			// Hide results when clicking outside
			$(document).on('click', function(e) {
				if (!$(e.target).closest('.agoda-city-search-wrapper').length) {
					$citySearchResults.hide();
				}
			});

			// Handle city selection
			$(document).on('click', '.agoda-city-result-item', function() {
				var cityId = $(this).data('city-id');
				var cityName = $(this).data('city-name');
				var countryName = $(this).data('country-name');
				
				if ($cityId.length > 0) {
					$cityId.val(cityId);
				}
				$citySearch.val(cityName + ', ' + countryName);
				if ($selectedCityName.length > 0) {
					$selectedCityName.text(cityName + ', ' + countryName + ' (ID: ' + cityId + ')');
				}
				if ($selectedCity.length > 0) {
					$selectedCity.show();
				}
				$citySearchResults.hide();
			});

			// Clear selected city
			$('#agoda-clear-city').on('click', function() {
				$citySearch.val('');
				if ($cityId.length > 0) {
					$cityId.val('');
				}
				if ($cityIdDirect.length > 0) {
					$cityIdDirect.val('');
				}
				if ($selectedCity.length > 0) {
					$selectedCity.hide();
				}
			});

			// Sync direct city ID input
			if ($cityIdDirect.length > 0) {
				$cityIdDirect.on('input', function() {
					var cityId = $(this).val();
					if ($cityId.length > 0) {
						$cityId.val(cityId);
					}
					if (cityId) {
						$citySearch.val('');
						if ($selectedCity.length > 0) {
							$selectedCity.hide();
						}
					}
				});
			}
		}

		// Search cities function
		function searchCities(searchTerm) {
			if (!agodaBookingAdmin.nonceCityManagement) {
				console.error('City Management nonce not available');
				$citySearchResults.html('<div class="agoda-city-error">City search is not available. Please configure Content API in Settings.</div>').show();
				return;
			}
			
			$.ajax({
				url: agodaBookingAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'agoda_get_cities',
					nonce: agodaBookingAdmin.nonceCityManagement,
					search: searchTerm,
					per_page: 10
				},
				success: function(response) {
					if (response.success && response.data && response.data.cities && response.data.cities.length > 0) {
						displayCityResults(response.data.cities);
					} else {
						var message = 'No cities found.';
						if (response.data && response.data.message) {
							message = response.data.message;
						}
						$citySearchResults.html('<div class="agoda-city-no-results">' + message + '</div>').show();
					}
				},
				error: function(xhr, status, error) {
					var errorMessage = 'Error searching cities.';
					if (xhr.responseJSON && xhr.responseJSON.data && xhr.responseJSON.data.message) {
						errorMessage = xhr.responseJSON.data.message;
					}
					$citySearchResults.html('<div class="agoda-city-error">' + errorMessage + '</div>').show();
					console.error('City search error:', xhr, status, error);
				}
			});
		}

		// Display city search results
		function displayCityResults(cities) {
			var html = '';
			cities.forEach(function(city) {
				var cityId = city.CityId || city.city_id || '';
				var cityName = city.CityName || city.city_name || 'N/A';
				var countryName = city.CountryName || city.country_name || 'N/A';
				
				html += '<div class="agoda-city-result-item" data-city-id="' + escapeHtml(cityId) + '" data-city-name="' + escapeHtml(cityName) + '" data-country-name="' + escapeHtml(countryName) + '">';
				html += '<strong>' + escapeHtml(cityName) + '</strong>, ' + escapeHtml(countryName);
				html += ' <span style="color: #666;">(ID: ' + escapeHtml(cityId) + ')</span>';
				html += '</div>';
			});
			$citySearchResults.html(html).show();
		}

		// Handle children ages input
		$childrenInput.on('change', function() {
			var childrenCount = parseInt($(this).val()) || 0;
			$childrenAgesContainer.empty();
			
			if (childrenCount > 0) {
				$childrenAgesRow.show();
				for (var i = 0; i < childrenCount; i++) {
					var $inputGroup = $('<div class="agoda-children-age-input"></div>');
					$inputGroup.append(
						$('<label>').text('Child ' + (i + 1) + ' Age:').attr('for', 'child_age_' + i)
					);
					$inputGroup.append(
						$('<input>')
							.attr({
								type: 'number',
								id: 'child_age_' + i,
								name: 'children_ages[]',
								min: 0,
								max: 17,
								value: 0,
								class: 'small-text'
							})
					);
					$childrenAgesContainer.append($inputGroup);
				}
			} else {
				$childrenAgesRow.hide();
			}
		});

		// Form submission
		$form.on('submit', function(e) {
			e.preventDefault();
			
			// Hide previous results/errors
			$resultsWrapper.hide();
			$errorContainer.hide();
			
			// Show spinner
			$spinner.addClass('is-active');
			$submitButton.prop('disabled', true);
			
			// Collect form data
			var formData = {
				action: 'agoda_admin_hotel_search',
				nonce: agodaBookingAdmin.nonceAdminSearch,
				search_type: $searchType.val(),
				check_in: $('#agoda-check-in').val(),
				check_out: $('#agoda-check-out').val(),
				adults: $('#agoda-adults').val(),
				children: $childrenInput.val(),
				language: $('#agoda-language').val(),
				currency: $('#agoda-currency').val(),
			};

			// Add search type specific data
			if (formData.search_type === 'city') {
				// Get city ID from either hidden field or direct input
				var cityId = '';
				if ($cityId.length > 0 && $cityId.val()) {
					cityId = $cityId.val();
				} else if ($cityIdDirect.length > 0 && $cityIdDirect.val()) {
					cityId = $cityIdDirect.val();
				}
				formData.city_id = cityId;
			} else {
				formData.hotel_ids = $('#agoda-hotel-ids').val();
			}

			// Add children ages
			var childrenAges = [];
			$('input[name="children_ages[]"]').each(function() {
				childrenAges.push($(this).val());
			});
			if (childrenAges.length > 0) {
				formData.children_ages = childrenAges;
			}

			// Add filters
			if ($('#agoda-min-price').val()) {
				formData.min_price = $('#agoda-min-price').val();
			}
			if ($('#agoda-max-price').val()) {
				formData.max_price = $('#agoda-max-price').val();
			}
			if ($('#agoda-min-rating').val()) {
				formData.min_rating = $('#agoda-min-rating').val();
			}
			if ($('#agoda-min-review').val()) {
				formData.min_review = $('#agoda-min-review').val();
			}
			if ($('#agoda-discount-only').is(':checked')) {
				formData.discount_only = '1';
			}
			if ($('#agoda-sort-by').val()) {
				formData.sort_by = $('#agoda-sort-by').val();
			}
			if ($('#agoda-max-results').val()) {
				formData.max_results = $('#agoda-max-results').val();
			}

			// Send AJAX request
			$.ajax({
				url: agodaBookingAdmin.ajaxUrl,
				type: 'POST',
				data: formData,
				success: function(response) {
					$spinner.removeClass('is-active');
					$submitButton.prop('disabled', false);
					
					if (response.success) {
						displayResults(response.data);
					} else {
						displayError(response.data.message || 'Search failed.');
					}
				},
				error: function() {
					$spinner.removeClass('is-active');
					$submitButton.prop('disabled', false);
					displayError('An error occurred while searching. Please try again.');
				}
			});
		});

		// Display results
		function displayResults(data) {
			var $container = $('#agoda-results-container');
			var $info = $('#agoda-results-info');
			
			$container.empty();
			$info.empty();
			
			var count = data.count || 0;
			var results = data.results || [];
			
			$info.html('<p><strong>Found ' + count + ' hotel(s)</strong></p>');
			
			if (results.length === 0) {
				$container.html('<p>No hotels found.</p>');
				$resultsWrapper.show();
				return;
			}
			
			// Create results table
			var $table = $('<table class="wp-list-table widefat fixed striped"></table>');
			var $thead = $('<thead></thead>');
			var $tbody = $('<tbody></tbody>');
			
			// Table header
			$thead.append(
				$('<tr></tr>').append(
					$('<th>').text('Hotel Name'),
					$('<th>').text('Image'),
					$('<th>').text('Rating'),
					$('<th>').text('Price'),
					$('<th>').text('Landing URL'),
					$('<th>').text('Actions')
				)
			);
			
			// Table rows
			results.forEach(function(hotel, index) {
				var $row = $('<tr></tr>');
				
				// Hotel Name
				$row.append($('<td>').html('<strong>' + escapeHtml(hotel.hotelName || 'N/A') + '</strong>'));
				
				// Image
				var $imgCell = $('<td>');
				if (hotel.imageURL) {
					$imgCell.append(
						$('<img>')
							.attr('src', hotel.imageURL)
							.attr('alt', hotel.hotelName || '')
							.css({
								width: '100px',
								height: 'auto',
								maxHeight: '60px',
								objectFit: 'cover'
							})
					);
				} else {
					$imgCell.text('N/A');
				}
				$row.append($imgCell);
				
				// Rating
				var ratingText = '';
				if (hotel.starRating) {
					ratingText = hotel.starRating + ' ★';
				}
				if (hotel.reviewScore) {
					ratingText += (ratingText ? ' | ' : '') + hotel.reviewScore + '/10';
				}
				$row.append($('<td>').text(ratingText || 'N/A'));
				
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
				
				// Landing URL
				var $urlCell = $('<td>');
				if (hotel.landingURL) {
					var $urlLink = $('<a>')
						.attr('href', hotel.landingURL)
						.attr('target', '_blank')
						.attr('rel', 'noopener noreferrer')
						.text(hotel.landingURL.substring(0, 50) + '...');
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
					$actionsCell.append(' ');
					$actionsCell.append(
						$('<button>')
							.addClass('button button-small')
							.text('View Details')
							.data('hotel', hotel)
							.on('click', function() {
								showHotelDetails($(this).data('hotel'));
							})
					);
				}
				$row.append($actionsCell);
				
				$tbody.append($row);
			});
			
			$table.append($thead).append($tbody);
			$container.append($table);
			$resultsWrapper.show();
		}

		// Display error
		function displayError(message) {
			$('#agoda-error-message').text(message);
			$errorContainer.show();
		}

		// Copy to clipboard
		function copyToClipboard(text) {
			var $temp = $('<textarea>');
			$('body').append($temp);
			$temp.val(text).select();
			document.execCommand('copy');
			$temp.remove();
		}

		// Show hotel details modal
		function showHotelDetails(hotel) {
			var $modal = $('#agoda-hotel-modal');
			var $modalBody = $('#agoda-hotel-modal-body');
			
			var html = '<h2>' + escapeHtml(hotel.hotelName || 'N/A') + '</h2>';
			html += '<table class="form-table">';
			
			if (hotel.hotelId) {
				html += '<tr><th>Hotel ID:</th><td>' + escapeHtml(hotel.hotelId) + '</td></tr>';
			}
			if (hotel.starRating) {
				html += '<tr><th>Star Rating:</th><td>' + escapeHtml(hotel.starRating) + ' ★</td></tr>';
			}
			if (hotel.reviewScore) {
				html += '<tr><th>Review Score:</th><td>' + escapeHtml(hotel.reviewScore) + '/10</td></tr>';
			}
			if (hotel.dailyRate) {
				html += '<tr><th>Price:</th><td>' + escapeHtml(hotel.currency || 'USD') + ' ' + parseFloat(hotel.dailyRate).toFixed(2) + '</td></tr>';
			}
			if (hotel.crossedOutRate) {
				html += '<tr><th>Original Price:</th><td>' + escapeHtml(hotel.currency || 'USD') + ' ' + parseFloat(hotel.crossedOutRate).toFixed(2) + '</td></tr>';
			}
			if (hotel.discountPercentage) {
				html += '<tr><th>Discount:</th><td>' + escapeHtml(hotel.discountPercentage) + '%</td></tr>';
			}
			if (hotel.freeWifi) {
				html += '<tr><th>Amenities:</th><td>Free WiFi</td></tr>';
			}
			if (hotel.includeBreakfast) {
				html += '<tr><th>Amenities:</th><td>Breakfast Included</td></tr>';
			}
			if (hotel.landingURL) {
				html += '<tr><th>Landing URL:</th><td><a href="' + escapeHtml(hotel.landingURL) + '" target="_blank">' + escapeHtml(hotel.landingURL) + '</a></td></tr>';
			}
			
			html += '</table>';
			
			$modalBody.html(html);
			$modal.show();
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

		// Close modal
		$('.agoda-modal-close').on('click', function() {
			$('#agoda-hotel-modal').hide();
		});

		$(window).on('click', function(e) {
			var $modal = $('#agoda-hotel-modal');
			if ($(e.target).is($modal)) {
				$modal.hide();
			}
		});
	});

})(jQuery);
