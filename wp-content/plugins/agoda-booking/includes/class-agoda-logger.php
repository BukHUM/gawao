<?php
/**
 * The logging class for error handling and debugging.
 *
 * @package Agoda_Booking
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The logging class.
 */
class Agoda_Logger {

	/**
	 * Log level constants.
	 */
	const LOG_LEVEL_ERROR = 'error';
	const LOG_LEVEL_WARNING = 'warning';
	const LOG_LEVEL_INFO = 'info';
	const LOG_LEVEL_DEBUG = 'debug';

	/**
	 * Whether logging is enabled.
	 *
	 * @var bool
	 */
	private $enabled;

	/**
	 * Minimum log level.
	 *
	 * @var string
	 */
	private $min_level;

	/**
	 * Initialize the logger.
	 */
	public function __construct() {
		// Enable logging if WP_DEBUG is on or if explicitly enabled in options
		$this->enabled = defined( 'WP_DEBUG' ) && WP_DEBUG;
		$this->enabled = $this->enabled || get_option( 'agoda_enable_logging', false );

		// Set minimum log level
		$this->min_level = get_option( 'agoda_log_level', self::LOG_LEVEL_ERROR );
	}

	/**
	 * Log an error.
	 *
	 * @param string $message Error message.
	 * @param array  $context Additional context data.
	 * @return void
	 */
	public function error( $message, $context = array() ) {
		$this->log( self::LOG_LEVEL_ERROR, $message, $context );
	}

	/**
	 * Log a warning.
	 *
	 * @param string $message Warning message.
	 * @param array  $context Additional context data.
	 * @return void
	 */
	public function warning( $message, $context = array() ) {
		$this->log( self::LOG_LEVEL_WARNING, $message, $context );
	}

	/**
	 * Log an info message.
	 *
	 * @param string $message Info message.
	 * @param array  $context Additional context data.
	 * @return void
	 */
	public function info( $message, $context = array() ) {
		$this->log( self::LOG_LEVEL_INFO, $message, $context );
	}

	/**
	 * Log a debug message.
	 *
	 * @param string $message Debug message.
	 * @param array  $context Additional context data.
	 * @return void
	 */
	public function debug( $message, $context = array() ) {
		$this->log( self::LOG_LEVEL_DEBUG, $message, $context );
	}

	/**
	 * Log API error.
	 *
	 * @param string $error_code Error code.
	 * @param string $error_message Error message.
	 * @param array  $context Additional context (status code, response body, etc.).
	 * @return void
	 */
	public function log_api_error( $error_code, $error_message, $context = array() ) {
		$message = sprintf(
			'Agoda API Error [%s]: %s',
			$error_code,
			$error_message
		);

		$this->error( $message, $context );
	}

	/**
	 * Log validation error.
	 *
	 * @param string $field Field name.
	 * @param string $error_message Error message.
	 * @param mixed  $value Invalid value.
	 * @return void
	 */
	public function log_validation_error( $field, $error_message, $value = null ) {
		$message = sprintf(
			'Agoda Validation Error [%s]: %s',
			$field,
			$error_message
		);

		$context = array(
			'field' => $field,
			'value' => $value,
		);

		$this->warning( $message, $context );
	}

	/**
	 * Log API request.
	 *
	 * @param string $endpoint API endpoint.
	 * @param array  $request_body Request body.
	 * @return void
	 */
	public function log_api_request( $endpoint, $request_body = array() ) {
		if ( ! $this->should_log( self::LOG_LEVEL_DEBUG ) ) {
			return;
		}

		$message = sprintf( 'Agoda API Request to: %s', $endpoint );

		$context = array(
			'endpoint' => $endpoint,
			'request'  => $request_body,
		);

		$this->debug( $message, $context );
	}

	/**
	 * Log API response.
	 *
	 * @param int    $status_code HTTP status code.
	 * @param array  $response_data Response data.
	 * @return void
	 */
	public function log_api_response( $status_code, $response_data = array() ) {
		if ( ! $this->should_log( self::LOG_LEVEL_DEBUG ) ) {
			return;
		}

		$message = sprintf( 'Agoda API Response [%d]', $status_code );

		$context = array(
			'status_code' => $status_code,
			'response'    => $response_data,
		);

		if ( $status_code >= 400 ) {
			$this->error( $message, $context );
		} else {
			$this->debug( $message, $context );
		}
	}

	/**
	 * Main logging method.
	 *
	 * @param string $level Log level.
	 * @param string $message Log message.
	 * @param array  $context Additional context.
	 * @return void
	 */
	private function log( $level, $message, $context = array() ) {
		if ( ! $this->enabled ) {
			return;
		}

		if ( ! $this->should_log( $level ) ) {
			return;
		}

		// Format log message
		$log_message = sprintf(
			'[%s] %s',
			strtoupper( $level ),
			$message
		);

		// Add context if provided
		if ( ! empty( $context ) ) {
			$log_message .= ' | Context: ' . wp_json_encode( $context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
		}

		// Add timestamp
		$log_message = sprintf(
			'[%s] %s',
			current_time( 'mysql' ),
			$log_message
		);

		// Log to WordPress debug log
		error_log( $log_message );
	}

	/**
	 * Check if should log at given level.
	 *
	 * @param string $level Log level.
	 * @return bool True if should log, false otherwise.
	 */
	private function should_log( $level ) {
		$levels = array(
			self::LOG_LEVEL_ERROR   => 1,
			self::LOG_LEVEL_WARNING => 2,
			self::LOG_LEVEL_INFO    => 3,
			self::LOG_LEVEL_DEBUG   => 4,
		);

		$current_level = isset( $levels[ $this->min_level ] ) ? $levels[ $this->min_level ] : 1;
		$requested_level = isset( $levels[ $level ] ) ? $levels[ $level ] : 4;

		return $requested_level <= $current_level;
	}

	/**
	 * Get log file path.
	 *
	 * @return string|false Log file path or false if not available.
	 */
	public function get_log_file_path() {
		if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
			if ( defined( 'WP_DEBUG_LOG_FILE' ) && WP_DEBUG_LOG_FILE ) {
				return WP_DEBUG_LOG_FILE;
			}
			return WP_CONTENT_DIR . '/debug.log';
		}
		return false;
	}

	/**
	 * Read logs from file.
	 *
	 * @param array $args Arguments for filtering logs.
	 * @return array Array of log entries.
	 */
	public function read_logs( $args = array() ) {
		$defaults = array(
			'level'      => '',
			'date_from'  => '',
			'date_to'    => '',
			'search'     => '',
			'page'       => 1,
			'per_page'   => 50,
		);
		$args = wp_parse_args( $args, $defaults );

		$log_file = $this->get_log_file_path();
		if ( ! $log_file || ! file_exists( $log_file ) ) {
			return array(
				'logs'      => array(),
				'total'     => 0,
				'page'      => 1,
				'per_page'  => $args['per_page'],
				'total_pages' => 0,
			);
		}

		// Read log file
		$file_content = file_get_contents( $log_file );
		if ( false === $file_content ) {
			return array(
				'logs'      => array(),
				'total'     => 0,
				'page'      => 1,
				'per_page'  => $args['per_page'],
				'total_pages' => 0,
			);
		}

		// Parse log entries (assuming WordPress debug.log format)
		$lines = explode( "\n", $file_content );
		$logs = array();
		$current_entry = '';

		foreach ( $lines as $line ) {
			// Check if line starts with timestamp (WordPress log format)
			if ( preg_match( '/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/', $line, $matches ) ) {
				// Save previous entry if exists
				if ( ! empty( $current_entry ) ) {
					$parsed = $this->parse_log_entry( $current_entry );
					if ( $parsed ) {
						$logs[] = $parsed;
					}
				}
				$current_entry = $line;
			} else {
				// Continuation of previous entry
				$current_entry .= "\n" . $line;
			}
		}

		// Add last entry
		if ( ! empty( $current_entry ) ) {
			$parsed = $this->parse_log_entry( $current_entry );
			if ( $parsed ) {
				$logs[] = $parsed;
			}
		}

		// Filter logs
		$filtered_logs = $this->filter_logs( $logs, $args );

		// Reverse to show newest first
		$filtered_logs = array_reverse( $filtered_logs );

		// Paginate
		$total = count( $filtered_logs );
		$total_pages = ceil( $total / $args['per_page'] );
		$offset = ( $args['page'] - 1 ) * $args['per_page'];
		$paginated_logs = array_slice( $filtered_logs, $offset, $args['per_page'] );

		return array(
			'logs'        => $paginated_logs,
			'total'       => $total,
			'page'        => $args['page'],
			'per_page'    => $args['per_page'],
			'total_pages' => $total_pages,
		);
	}

	/**
	 * Parse a log entry.
	 *
	 * @param string $entry Log entry string.
	 * @return array|false Parsed log entry or false on failure.
	 */
	private function parse_log_entry( $entry ) {
		// Match WordPress debug.log format: [YYYY-MM-DD HH:MM:SS] LEVEL Message | Context: {...}
		if ( ! preg_match( '/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]\s+\[(\w+)\]\s+(.+?)(?:\s+\|\s+Context:\s+(.+))?$/s', $entry, $matches ) ) {
			// Try simpler format
			if ( ! preg_match( '/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]\s+(.+)$/s', $entry, $matches ) ) {
				return false;
			}
			$timestamp = $matches[1];
			$level = 'info';
			$message = $matches[2];
			$context = '';
		} else {
			$timestamp = $matches[1];
			$level = strtolower( $matches[2] );
			$message = $matches[3];
			$context = isset( $matches[4] ) ? $matches[4] : '';
		}

		// Check if it's an Agoda log
		if ( strpos( $message, 'Agoda' ) === false ) {
			return false;
		}

		return array(
			'timestamp' => $timestamp,
			'level'     => $level,
			'message'   => trim( $message ),
			'context'   => $context,
			'raw'       => $entry,
		);
	}

	/**
	 * Filter logs based on criteria.
	 *
	 * @param array $logs Log entries.
	 * @param array $args Filter arguments.
	 * @return array Filtered log entries.
	 */
	private function filter_logs( $logs, $args ) {
		$filtered = array();

		foreach ( $logs as $log ) {
			// Filter by level
			if ( ! empty( $args['level'] ) && $log['level'] !== $args['level'] ) {
				continue;
			}

			// Filter by date
			if ( ! empty( $args['date_from'] ) ) {
				$log_date = date( 'Y-m-d', strtotime( $log['timestamp'] ) );
				if ( $log_date < $args['date_from'] ) {
					continue;
				}
			}
			if ( ! empty( $args['date_to'] ) ) {
				$log_date = date( 'Y-m-d', strtotime( $log['timestamp'] ) );
				if ( $log_date > $args['date_to'] ) {
					continue;
				}
			}

			// Filter by search
			if ( ! empty( $args['search'] ) ) {
				$search = strtolower( $args['search'] );
				$message = strtolower( $log['message'] );
				$context = strtolower( $log['context'] );
				if ( strpos( $message, $search ) === false && strpos( $context, $search ) === false ) {
					continue;
				}
			}

			$filtered[] = $log;
		}

		return $filtered;
	}

	/**
	 * Clear old logs.
	 *
	 * @param int $days Number of days to keep.
	 * @return int Number of log entries cleared.
	 */
	public function clear_old_logs( $days = 30 ) {
		$log_file = $this->get_log_file_path();
		if ( ! $log_file || ! file_exists( $log_file ) ) {
			return 0;
		}

		// Read all logs
		$result = $this->read_logs( array( 'per_page' => 999999 ) );
		$logs = $result['logs'];

		$cutoff_date = date( 'Y-m-d H:i:s', strtotime( "-{$days} days" ) );
		$kept_logs = array();
		$cleared_count = 0;

		foreach ( $logs as $log ) {
			if ( $log['timestamp'] >= $cutoff_date ) {
				$kept_logs[] = $log['raw'];
			} else {
				$cleared_count++;
			}
		}

		// Write back kept logs
		$content = implode( "\n", $kept_logs );
		file_put_contents( $log_file, $content );

		return $cleared_count;
	}
}
