/**
 * Statistics & Analytics JavaScript for Admin
 *
 * @package Agoda_Booking
 */

(function($) {
	'use strict';

	$(document).ready(function() {
		var currentPeriod = 'all';
		var searchesChart = null;
		var responseTimeChart = null;

		// Initialize charts
		loadStatistics();

		// Period selector
		$('#agoda-statistics-period-select').on('change', function() {
			currentPeriod = $(this).val();
			loadStatistics();
		});

		// Refresh statistics
		$('#agoda-refresh-statistics').on('click', function() {
			loadStatistics();
		});

		// Export statistics
		$('#agoda-export-statistics').on('click', function() {
			var params = $.param({
				action: 'agoda_export_statistics',
				nonce: agodaBookingAdmin.nonceStatistics,
				period: currentPeriod
			});
			window.location.href = agodaBookingAdmin.ajaxUrl + '?' + params;
		});

		// Clear statistics
		$('#agoda-clear-statistics').on('click', function() {
			if (!confirm('Are you sure you want to clear all statistics? This action cannot be undone.')) {
				return;
			}
			
			var $button = $(this);
			$button.prop('disabled', true);
			
			$.ajax({
				url: agodaBookingAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'agoda_clear_statistics',
					nonce: agodaBookingAdmin.nonceStatistics
				},
				success: function(response) {
					$button.prop('disabled', false);
					
					if (response.success) {
						alert(response.data.message);
						location.reload();
					} else {
						alert(response.data.message || 'Failed to clear statistics.');
					}
				},
				error: function() {
					$button.prop('disabled', false);
					alert('Failed to clear statistics.');
				}
			});
		});

		// Load statistics
		function loadStatistics() {
			$.ajax({
				url: agodaBookingAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'agoda_get_statistics',
					nonce: agodaBookingAdmin.nonceStatistics,
					period: currentPeriod
				},
				success: function(response) {
					if (response.success) {
						updateStatistics(response.data);
					} else {
						console.error('Failed to load statistics:', response.data.message);
					}
				},
				error: function() {
					console.error('Failed to load statistics.');
				}
			});
		}

		// Update statistics display
		function updateStatistics(data) {
			// Update stat cards
			$('#stat-total-searches').text(data.stats.total_searches);
			$('#stat-successful-searches').text(data.stats.successful_searches);
			$('#stat-failed-searches').text(data.stats.failed_searches);
			$('#stat-cached-searches').text(data.stats.cached_searches);
			$('#stat-avg-hotels').text(data.stats.average_hotels_found);

			// Update charts
			updateSearchesChart(data.stats.searches_by_date);
			// Note: Response time chart would need response time data by date
			// For now, we'll skip it or use a simple bar chart
		}

		// Update searches chart
		function updateSearchesChart(searchesByDate) {
			var ctx = document.getElementById('agoda-searches-chart');
			if (!ctx) {
				return;
			}

			var dates = Object.keys(searchesByDate);
			var counts = Object.values(searchesByDate);

			// Destroy existing chart if it exists
			if (searchesChart) {
				searchesChart.destroy();
			}

			// Create simple bar chart using canvas (without Chart.js library)
			var canvas = ctx;
			var ctx2d = canvas.getContext('2d');
			var width = canvas.width;
			var height = canvas.height;

			// Clear canvas
			ctx2d.clearRect(0, 0, width, height);

			if (dates.length === 0) {
				ctx2d.fillStyle = '#666';
				ctx2d.font = '14px Arial';
				ctx2d.textAlign = 'center';
				ctx2d.fillText('No data available', width / 2, height / 2);
				return;
			}

			// Draw chart
			var maxCount = Math.max.apply(null, counts);
			var barWidth = width / dates.length;
			var barSpacing = 5;
			var actualBarWidth = barWidth - barSpacing;

			ctx2d.fillStyle = '#2271b1';
			dates.forEach(function(date, index) {
				var barHeight = (counts[index] / maxCount) * (height - 40);
				var x = index * barWidth + barSpacing / 2;
				var y = height - barHeight - 20;
				
				ctx2d.fillRect(x, y, actualBarWidth, barHeight);
				
				// Draw label
				ctx2d.fillStyle = '#333';
				ctx2d.font = '10px Arial';
				ctx2d.textAlign = 'center';
				ctx2d.fillText(counts[index], x + actualBarWidth / 2, y - 5);
				ctx2d.fillStyle = '#2271b1';
			});

			// Draw date labels
			ctx2d.fillStyle = '#666';
			ctx2d.font = '10px Arial';
			ctx2d.textAlign = 'center';
			dates.forEach(function(date, index) {
				var x = index * barWidth + actualBarWidth / 2;
				var dateLabel = date.substring(5); // Remove year
				ctx2d.fillText(dateLabel, x, height - 5);
			});
		}

		// Update response time chart (simplified)
		function updateResponseTimeChart() {
			var ctx = document.getElementById('agoda-response-time-chart');
			if (!ctx) {
				return;
			}

			var canvas = ctx;
			var ctx2d = canvas.getContext('2d');
			var width = canvas.width;
			var height = canvas.height;

			// Clear canvas
			ctx2d.clearRect(0, 0, width, height);

			// Simple placeholder
			ctx2d.fillStyle = '#666';
			ctx2d.font = '14px Arial';
			ctx2d.textAlign = 'center';
			ctx2d.fillText('Response time data visualization', width / 2, height / 2);
			ctx2d.fillText('(Requires additional data tracking)', width / 2, height / 2 + 20);
		}

		// Initialize response time chart
		updateResponseTimeChart();
	});

})(jQuery);
