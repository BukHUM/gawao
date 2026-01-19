/**
 * API Logs JavaScript for Admin
 *
 * @package Agoda_Booking
 */

(function($) {
	'use strict';

	$(document).ready(function() {
		var currentPage = 1;
		var currentFilters = {};

		// Load logs on page load
		loadLogs();

		// Save log settings
		$('#agoda-logs-settings-form').on('submit', function(e) {
			e.preventDefault();
			
			var $form = $(this);
			var $button = $form.find('button[type="submit"]');
			
			$button.prop('disabled', true);
			
			$.ajax({
				url: agodaBookingAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'agoda_save_log_settings',
					nonce: agodaBookingAdmin.nonceLogs,
					enable_logging: $('#agoda-enable-logging').is(':checked') ? '1' : '0',
					log_level: $('#agoda-log-level').val(),
					log_retention: $('#agoda-log-retention').val()
				},
				success: function(response) {
					$button.prop('disabled', false);
					
					if (response.success) {
						showNotice('success', response.data.message);
					} else {
						showNotice('error', response.data.message || 'Failed to save settings.');
					}
				},
				error: function() {
					$button.prop('disabled', false);
					showNotice('error', 'Failed to save settings.');
				}
			});
		});

		// Filter logs
		$('#agoda-logs-filter-form').on('submit', function(e) {
			e.preventDefault();
			currentPage = 1;
			currentFilters = {
				level: $('#agoda-log-level-filter').val(),
				date_from: $('#agoda-log-date-from').val(),
				date_to: $('#agoda-log-date-to').val(),
				search: $('#agoda-log-search').val()
			};
			loadLogs();
		});

		// Reset filters
		$('#agoda-reset-filters').on('click', function() {
			$('#agoda-log-level-filter').val('');
			$('#agoda-log-date-from').val('');
			$('#agoda-log-date-to').val('');
			$('#agoda-log-search').val('');
			currentPage = 1;
			currentFilters = {};
			loadLogs();
		});

		// Refresh logs
		$('#agoda-refresh-logs').on('click', function() {
			loadLogs();
		});

		// Export logs
		$('#agoda-export-logs').on('click', function() {
			var params = $.param({
				action: 'agoda_export_logs',
				nonce: agodaBookingAdmin.nonceLogs,
				level: currentFilters.level || '',
				date_from: currentFilters.date_from || '',
				date_to: currentFilters.date_to || '',
				search: currentFilters.search || ''
			});
			window.location.href = agodaBookingAdmin.ajaxUrl + '?' + params;
		});

		// Clear old logs
		$('#agoda-clear-old-logs').on('click', function() {
			if (!confirm('Are you sure you want to clear old logs? This action cannot be undone.')) {
				return;
			}
			
			var $button = $(this);
			$button.prop('disabled', true);
			
			$.ajax({
				url: agodaBookingAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'agoda_clear_old_logs',
					nonce: agodaBookingAdmin.nonceLogs,
					days: $('#agoda-log-retention').val() || 30
				},
				success: function(response) {
					$button.prop('disabled', false);
					
					if (response.success) {
						showNotice('success', response.data.message);
						loadLogs();
					} else {
						showNotice('error', response.data.message || 'Failed to clear old logs.');
					}
				},
				error: function() {
					$button.prop('disabled', false);
					showNotice('error', 'Failed to clear old logs.');
				}
			});
		});

		// Load logs
		function loadLogs(page) {
			if (page) {
				currentPage = page;
			}
			
			$('#agoda-logs-loading').show();
			$('#agoda-logs-container').empty();
			
			$.ajax({
				url: agodaBookingAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'agoda_get_logs',
					nonce: agodaBookingAdmin.nonceLogs,
					page: currentPage,
					per_page: 50,
					level: currentFilters.level || '',
					date_from: currentFilters.date_from || '',
					date_to: currentFilters.date_to || '',
					search: currentFilters.search || ''
				},
				success: function(response) {
					$('#agoda-logs-loading').hide();
					
					if (response.success) {
						displayLogs(response.data);
					} else {
						$('#agoda-logs-container').html('<p class="error">' + (response.data.message || 'Failed to load logs.') + '</p>');
					}
				},
				error: function() {
					$('#agoda-logs-loading').hide();
					$('#agoda-logs-container').html('<p class="error">Failed to load logs.</p>');
				}
			});
		}

		// Display logs
		function displayLogs(data) {
			var $container = $('#agoda-logs-container');
			$container.empty();
			
			if (data.logs.length === 0) {
				$container.html('<p>' + 'No logs found.' + '</p>');
				$('#agoda-logs-pagination').empty();
				return;
			}
			
			// Create table
			var $table = $('<table class="wp-list-table widefat fixed striped"></table>');
			var $thead = $('<thead></thead>');
			var $tbody = $('<tbody></tbody>');
			
			// Table header
			$thead.append(
				$('<tr></tr>').append(
					$('<th>').text('Timestamp'),
					$('<th>').text('Level'),
					$('<th>').text('Message'),
					$('<th>').text('Actions')
				)
			);
			
			// Table rows
			data.logs.forEach(function(log) {
				var $row = $('<tr></tr>');
				
				// Timestamp
				$row.append($('<td>').text(log.timestamp));
				
				// Level
				var levelClass = 'agoda-log-level-' + log.level;
				var $levelCell = $('<td>');
				$levelCell.append(
					$('<span>')
						.addClass('agoda-log-badge')
						.addClass(levelClass)
						.text(log.level.toUpperCase())
				);
				$row.append($levelCell);
				
				// Message (truncated)
				var message = log.message;
				if (message.length > 100) {
					message = message.substring(0, 100) + '...';
				}
				$row.append($('<td>').text(message));
				
				// Actions
				var $actionsCell = $('<td>');
				$actionsCell.append(
					$('<button>')
						.addClass('button button-small')
						.text('View Details')
						.data('log', log)
						.on('click', function() {
							showLogDetails($(this).data('log'));
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
			var $pagination = $('#agoda-logs-pagination');
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
							loadLogs(data.page - 1);
						})
				);
			}
			
			// Page info
			$nav.append(
				$('<span>')
					.addClass('agoda-page-info')
					.text('Page ' + data.page + ' of ' + data.total_pages + ' (' + data.total + ' total)')
			);
			
			// Next button
			if (data.page < data.total_pages) {
				$nav.append(
					$('<button>')
						.addClass('button')
						.text('Next →')
						.on('click', function() {
							loadLogs(data.page + 1);
						})
				);
			}
			
			$pagination.append($nav);
		}

		// Show log details
		function showLogDetails(log) {
			var $modal = $('#agoda-log-modal');
			var $modalBody = $('#agoda-log-modal-body');
			
			var html = '<table class="form-table">';
			html += '<tr><th>Timestamp:</th><td>' + escapeHtml(log.timestamp) + '</td></tr>';
			html += '<tr><th>Level:</th><td><span class="agoda-log-badge agoda-log-level-' + log.level + '">' + log.level.toUpperCase() + '</span></td></tr>';
			html += '<tr><th>Message:</th><td><pre>' + escapeHtml(log.message) + '</pre></td></tr>';
			if (log.context) {
				html += '<tr><th>Context:</th><td><pre>' + escapeHtml(log.context) + '</pre></td></tr>';
			}
			html += '</table>';
			
			$modalBody.html(html);
			$modal.show();
		}

		// Close modal
		$('.agoda-modal-close').on('click', function() {
			$('#agoda-log-modal').hide();
		});

		$(window).on('click', function(e) {
			var $modal = $('#agoda-log-modal');
			if ($(e.target).is($modal)) {
				$modal.hide();
			}
		});

		// Show notice
		function showNotice(type, message) {
			var $notice = $('<div class="notice notice-' + type + ' is-dismissible"><p>' + escapeHtml(message) + '</p></div>');
			$('.agoda-booking-logs h1').after($notice);
			setTimeout(function() {
				$notice.fadeOut(300, function() {
					$(this).remove();
				});
			}, 3000);
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
