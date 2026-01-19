/**
 * Frontend scripts for Agoda Booking plugin
 *
 * @package Agoda_Booking
 */

(function($) {
	'use strict';

	$(document).ready(function() {
		var isSubmitting = false;

		// ============================================
		// Date Picker & Validation
		// ============================================

		// Set minimum date to today for date inputs
		var today = new Date();
		today.setHours(0, 0, 0, 0);
		var todayISO = today.toISOString().split('T')[0];
		$('#agoda-check-in, #agoda-check-out').attr('min', todayISO);

		// Real-time date validation
		function validateDateField($field, fieldName) {
			var value = $field.val();
			var $errorContainer = $field.siblings('.agoda-field-error');
			
			// Remove existing error
			$errorContainer.remove();
			$field.removeClass('error').attr('aria-invalid', 'false');

			if (!value) {
				return true; // Empty is OK (will be validated on submit)
			}

			var selectedDate = new Date(value);
			selectedDate.setHours(0, 0, 0, 0);
			var todayDate = new Date();
			todayDate.setHours(0, 0, 0, 0);

			// Check if date is in the past
			if (selectedDate < todayDate) {
				showFieldError($field, 'This date cannot be in the past.');
				return false;
			}

			// Validate check-out date against check-in date
			if (fieldName === 'check-out') {
				var checkIn = $('#agoda-check-in').val();
				if (checkIn && value <= checkIn) {
					showFieldError($field, 'Check-out date must be after check-in date.');
					return false;
				}
			}

			return true;
		}

		// Show field error
		function showFieldError($field, message) {
			$field.addClass('error').attr('aria-invalid', 'true');
			var $error = $('<span class="agoda-field-error" role="alert">' + message + '</span>');
			$field.after($error);
		}

		// Clear field error
		function clearFieldError($field) {
			$field.removeClass('error').attr('aria-invalid', 'false');
			$field.siblings('.agoda-field-error').remove();
		}

		// Real-time validation for check-in date
		$('#agoda-check-in').on('change blur', function() {
			validateDateField($(this), 'check-in');
			
			// Update check-out min date
			var checkIn = $(this).val();
			if (checkIn) {
				var checkInDate = new Date(checkIn);
				checkInDate.setDate(checkInDate.getDate() + 1);
				var minCheckOut = checkInDate.toISOString().split('T')[0];
				$('#agoda-check-out').attr('min', minCheckOut);
				
				// If current check-out is invalid, clear it
				var checkOut = $('#agoda-check-out').val();
				if (checkOut && checkOut <= checkIn) {
					$('#agoda-check-out').val('');
					clearFieldError($('#agoda-check-out'));
				}
			}
		});

		// Real-time validation for check-out date
		$('#agoda-check-out').on('change blur', function() {
			validateDateField($(this), 'check-out');
		});

		// ============================================
		// City ID Validation
		// ============================================

		$('#agoda-city-id').on('blur', function() {
			var value = $(this).val();
			clearFieldError($(this));

			if (!value) {
				return; // Will be validated on submit
			}

			if (!/^\d+$/.test(value)) {
				showFieldError($(this), 'City ID must be a number.');
				return;
			}

			var cityId = parseInt(value);
			if (cityId <= 0) {
				showFieldError($(this), 'City ID must be a positive number.');
				return;
			}
		});

		// ============================================
		// Adults/Children Validation
		// ============================================

		$('#agoda-adults').on('blur', function() {
			var value = parseInt($(this).val()) || 0;
			clearFieldError($(this));

			if (value < 1) {
				showFieldError($(this), 'Number of adults must be at least 1.');
			}
		});

		$('#agoda-children').on('blur', function() {
			var value = parseInt($(this).val()) || 0;
			clearFieldError($(this));

			if (value < 0) {
				showFieldError($(this), 'Number of children cannot be negative.');
			}
		});

		// ============================================
		// Children Ages Handling
		// ============================================

		$('#agoda-children').on('change', function() {
			var childrenCount = parseInt($(this).val()) || 0;
			var $agesRow = $('#agoda-children-ages-row');
			var $agesContainer = $('#agoda-children-ages-container');

			if (childrenCount > 0) {
				$agesRow.show();
				$agesContainer.empty();

				for (var i = 0; i < childrenCount; i++) {
					var $ageInput = $('<div class="form-row-inline" role="listitem">' +
						'<label for="agoda-child-age-' + i + '">Child ' + (i + 1) + ' Age:</label>' +
						'<input type="number" id="agoda-child-age-' + i + '" class="agoda-child-age" min="0" max="17" value="0" required aria-required="true" />' +
						'</div>');
					$agesContainer.append($ageInput);
				}

				// Validate children ages
				$('.agoda-child-age').on('blur', function() {
					var age = parseInt($(this).val()) || 0;
					if (age < 0 || age > 17) {
						showFieldError($(this), 'Child age must be between 0 and 17.');
					} else {
						clearFieldError($(this));
					}
				});
			} else {
				$agesRow.hide();
				$agesContainer.empty();
			}
		});

		// ============================================
		// Form Validation (Comprehensive)
		// ============================================

		function validateForm() {
			var isValid = true;
			var $form = $('#agoda-search-form');

			// Clear all previous errors
			$form.find('.agoda-field-error').remove();
			$form.find('.error').removeClass('error').attr('aria-invalid', 'false');

			// Validate check-in date
			var checkIn = $('#agoda-check-in').val();
			if (!checkIn) {
				showFieldError($('#agoda-check-in'), 'Please select a check-in date.');
				isValid = false;
			} else if (!validateDateField($('#agoda-check-in'), 'check-in')) {
				isValid = false;
			}

			// Validate check-out date
			var checkOut = $('#agoda-check-out').val();
			if (!checkOut) {
				showFieldError($('#agoda-check-out'), 'Please select a check-out date.');
				isValid = false;
			} else if (!validateDateField($('#agoda-check-out'), 'check-out')) {
				isValid = false;
			}

			// Validate date range
			if (checkIn && checkOut && new Date(checkOut) <= new Date(checkIn)) {
				showFieldError($('#agoda-check-out'), 'Check-out date must be after check-in date.');
				isValid = false;
			}

			// Validate city ID
			var cityId = $('#agoda-city-id').val();
			if (!cityId) {
				showFieldError($('#agoda-city-id'), 'Please enter a City ID.');
				isValid = false;
			} else if (!/^\d+$/.test(cityId) || parseInt(cityId) <= 0) {
				showFieldError($('#agoda-city-id'), 'City ID must be a positive number.');
				isValid = false;
			}

			// Validate adults
			var adults = parseInt($('#agoda-adults').val()) || 0;
			if (adults < 1) {
				showFieldError($('#agoda-adults'), 'Number of adults must be at least 1.');
				isValid = false;
			}

			// Validate children ages
			var childrenCount = parseInt($('#agoda-children').val()) || 0;
			if (childrenCount > 0) {
				var childrenAges = [];
				$('.agoda-child-age').each(function() {
					var age = parseInt($(this).val()) || 0;
					if (age < 0 || age > 17) {
						showFieldError($(this), 'Child age must be between 0 and 17.');
						isValid = false;
					}
					childrenAges.push(age);
				});

				if (childrenAges.length !== childrenCount) {
					showFieldError($('#agoda-children'), 'Please enter ages for all children.');
					isValid = false;
				}
			}

			return isValid;
		}

		// ============================================
		// AJAX Search
		// ============================================

		$('#agoda-search-form').on('submit', function(e) {
			e.preventDefault();
			
			// Prevent double submission
			if (isSubmitting) {
				return false;
			}

			var $form = $(this);
			var $results = $('#agoda-results');
			var $loading = $('.agoda-loading');
			var $error = $('.agoda-error');
			var $submitButton = $form.find('button[type="submit"]');
			
			// Validate form
			if (!validateForm()) {
				// Scroll to first error
				var $firstError = $form.find('.error').first();
				if ($firstError.length) {
					$('html, body').animate({
						scrollTop: $firstError.offset().top - 100
					}, 500);
				}
				return false;
			}

			// Hide previous results/errors
			$results.hide();
			$error.hide();
			$loading.show();
			$submitButton.prop('disabled', true);
			isSubmitting = true;
			
			// Get form data
			var checkIn = $('#agoda-check-in').val();
			var checkOut = $('#agoda-check-out').val();
			var cityId = $('#agoda-city-id').val();
			
			// Get children ages
			var childrenAges = [];
			$('.agoda-child-age').each(function() {
				var age = parseInt($(this).val()) || 0;
				childrenAges.push(age);
			});

			// Prepare form data
			var formData = {
				action: 'agoda_search',
				nonce: agodaBooking.nonce,
				check_in: checkIn,
				check_out: checkOut,
				city_id: cityId,
				adults: $('#agoda-adults').val() || 2,
				children: $('#agoda-children').val() || 0,
				language: $('#agoda-language').val() || 'en-us',
				currency: $('#agoda-currency').val() || 'USD'
			};

			// Add children ages if provided
			if (childrenAges.length > 0) {
				formData.children_ages = childrenAges;
			}
			
			// Send AJAX request
			$.ajax({
				url: agodaBooking.ajaxUrl,
				type: 'POST',
				data: formData,
				timeout: 30000, // 30 seconds
				success: function(response) {
					$loading.hide();
					$submitButton.prop('disabled', false);
					isSubmitting = false;

					if (response.success) {
						$error.hide();
						$results.html(response.data.html).show();
						
						// Enable lazy loading for images
						enableLazyLoading();
						
						// Scroll to results
						$('html, body').animate({
							scrollTop: $results.offset().top - 100
						}, 500);
					} else {
						var errorMsg = response.data.message || 'An error occurred. Please try again.';
						$error.find('p').text(errorMsg);
						$error.find('.agoda-retry-button').show();
						$error.show();
					}
				},
				error: function(xhr, status, error) {
					$loading.hide();
					$submitButton.prop('disabled', false);
					isSubmitting = false;

					var errorMsg = 'An error occurred. Please try again.';
					if (status === 'timeout') {
						errorMsg = 'Request timed out. Please try again.';
					} else if (xhr.responseJSON && xhr.responseJSON.data && xhr.responseJSON.data.message) {
						errorMsg = xhr.responseJSON.data.message;
					}
					$error.find('p').text(errorMsg);
					$error.find('.agoda-retry-button').show();
					$error.show();
				}
			});

			return false;
		});

		// ============================================
		// Retry Button Handler
		// ============================================

		$(document).on('click', '.agoda-retry-button', function() {
			$('#agoda-search-form').trigger('submit');
		});

		// ============================================
		// Image Lazy Loading
		// ============================================

		function enableLazyLoading() {
			if ('loading' in HTMLImageElement.prototype) {
				// Native lazy loading is supported
				var images = document.querySelectorAll('.agoda-hotel-image[loading="lazy"]');
				images.forEach(function(img) {
					img.addEventListener('load', function() {
						this.classList.add('loaded');
					});
					img.addEventListener('error', function() {
						// Handle image load error
						this.style.display = 'none';
					});
				});
			} else {
				// Fallback for browsers that don't support native lazy loading
				if ('IntersectionObserver' in window) {
					var imageObserver = new IntersectionObserver(function(entries, observer) {
						entries.forEach(function(entry) {
							if (entry.isIntersecting) {
								var img = entry.target;
								img.src = img.dataset.src || img.src;
								img.classList.add('loaded');
								observer.unobserve(img);
							}
						});
					});

					var lazyImages = document.querySelectorAll('.agoda-hotel-image[loading="lazy"]');
					lazyImages.forEach(function(img) {
						if (img.dataset.src) {
							imageObserver.observe(img);
						} else {
							img.classList.add('loaded');
						}
					});
				} else {
					// Fallback: load all images immediately
					var images = document.querySelectorAll('.agoda-hotel-image[loading="lazy"]');
					images.forEach(function(img) {
						img.classList.add('loaded');
					});
				}
			}
		}

		// Initialize lazy loading on page load
		enableLazyLoading();

		// ============================================
		// Form State Management
		// ============================================

		// Enable/disable submit button based on form validity
		function updateSubmitButtonState() {
			var $submitButton = $('#agoda-search-form button[type="submit"]');
			var hasErrors = $('#agoda-search-form .error').length > 0;
			
			// Don't disable if form is being submitted
			if (!isSubmitting) {
				// Button is always enabled, validation happens on submit
				// This allows users to see all errors at once
			}
		}

		// Update submit button state on field changes
		$('#agoda-search-form input, #agoda-search-form select').on('change blur', function() {
			// Clear error for this field if value is now valid
			var $field = $(this);
			if ($field.hasClass('error')) {
				var fieldName = $field.attr('id');
				if (fieldName === 'agoda-check-in' || fieldName === 'agoda-check-out') {
					validateDateField($field, fieldName.replace('agoda-', '').replace('-', '-'));
				} else if (fieldName === 'agoda-city-id') {
					var value = $field.val();
					if (value && /^\d+$/.test(value) && parseInt(value) > 0) {
						clearFieldError($field);
					}
				}
			}
		});
	});

})(jQuery);
