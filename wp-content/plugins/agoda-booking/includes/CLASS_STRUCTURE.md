# Agoda Booking Plugin - Class Structure Documentation

## Overview

This document describes the class structure and methods of the Agoda Booking WordPress plugin.

---

## Agoda_Booking (Main Class)

**Location:** `includes/class-agoda-booking.php`

**Purpose:** Main orchestrator class that initializes and coordinates all plugin functionality.

### Methods

- `__construct()` - Initialize plugin, load dependencies, set up hooks
- `load_dependencies()` - Load all required class files
- `set_locale()` - Set up internationalization
- `load_plugin_textdomain()` - Load translation files
- `define_admin_hooks()` - Register admin area hooks
- `define_public_hooks()` - Register public-facing hooks
- `run()` - Execute plugin (called after initialization)

---

## Agoda_API (API Integration Class)

**Location:** `includes/class-agoda-api.php`

**Purpose:** Handles all communication with Agoda Affiliate API.

### Properties

- `$endpoint` (string) - API endpoint URL
- `$site_id` (string) - Site ID for authentication
- `$api_key` (string) - API Key for authentication
- `$cid` (string) - CID (Customer ID) for affiliate tracking

### Public Methods

- `__construct()` - Initialize API credentials from options
- `get_cid()` - Get CID for affiliate tracking (returns CID or Site ID)
- `search_city( $params )` - Search hotels by city ID
  - **Parameters:**
    - `cityId` (int) - City ID
    - `checkInDate` (string) - Check-in date (YYYY-MM-DD)
    - `checkOutDate` (string) - Check-out date (YYYY-MM-DD)
    - `language` (string, optional) - Language code
    - `currency` (string, optional) - Currency code
    - `occupancy` (array, optional) - Occupancy details
    - `additional` (array, optional) - Additional filters
  - **Returns:** `array|WP_Error` - Hotels list or error

- `search_hotels( $params )` - Search hotels by hotel ID list
  - **Parameters:**
    - `hotelId` (array) - Array of hotel IDs
    - `checkInDate` (string) - Check-in date (YYYY-MM-DD)
    - `checkOutDate` (string) - Check-out date (YYYY-MM-DD)
    - `language` (string, optional) - Language code
    - `currency` (string, optional) - Currency code
    - `occupancy` (array, optional) - Occupancy details
  - **Returns:** `array|WP_Error` - Hotels list or error

- `validate_credentials()` - Validate API credentials
  - **Returns:** `bool` - True if valid

### Private Methods

- `prepare_request( $params, $type )` - Build request body
  - **Parameters:**
    - `$params` (array) - Search parameters
    - `$type` (string) - Request type: 'city' or 'hotels'
  - **Returns:** `array` - Request body

- `send_request( $body )` - Send API request via wp_remote_post
  - **Parameters:**
    - `$body` (array) - Request body
  - **Returns:** `array|WP_Error` - API response or error

- `parse_response( $response )` - Parse JSON response
  - **Parameters:**
    - `$response` (array|WP_Error) - Raw API response
  - **Returns:** `array|WP_Error` - Parsed data or error

- `handle_errors( $status_code, $response_body )` - Handle API errors
  - **Parameters:**
    - `$status_code` (int) - HTTP status code
    - `$response_body` (string) - Response body
  - **Returns:** `WP_Error` - Error object

- `handle_response( $response )` - Process API response
  - **Parameters:**
    - `$response` (array|WP_Error) - API response
  - **Returns:** `array|WP_Error` - Processed response or error

---

## Agoda_Admin (Admin Interface Class)

**Location:** `includes/class-agoda-admin.php`

**Purpose:** Handles all admin area functionality including settings page.

### Public Methods

- `add_settings_page()` - Add settings page to WordPress admin menu
- `register_settings()` - Register plugin settings with WordPress
- `render_settings_page()` - Render settings page template
- `enqueue_styles()` - Enqueue admin CSS styles
- `enqueue_scripts()` - Enqueue admin JavaScript

### Settings Registered

- `agoda_site_id` - Site ID
- `agoda_api_key` - API Key
- `agoda_cid` - CID (Customer ID)
- `agoda_default_language` - Default language
- `agoda_default_currency` - Default currency
- `agoda_cache_duration` - Cache duration in seconds

---

## Agoda_Frontend (Frontend Display Class)

**Location:** `includes/class-agoda-frontend.php`

**Purpose:** Handles all public-facing functionality.

### Public Methods

- `render_search_form( $atts )` - Render search form (shortcode handler)
  - **Parameters:**
    - `$atts` (array) - Shortcode attributes
  - **Returns:** `string` - Search form HTML

- `render_results( $results )` - Render search results
  - **Parameters:**
    - `$results` (array) - Search results array
  - **Returns:** `string` - Results HTML

- `ajax_search()` - Handle AJAX search request
  - Validates nonce, sanitizes input, calls API, returns JSON

- `enqueue_styles()` - Enqueue frontend CSS styles

- `enqueue_scripts()` - Enqueue frontend JavaScript
  - Localizes script with `agodaBooking` object containing:
    - `ajaxUrl` - AJAX endpoint URL
    - `nonce` - Security nonce

---

## Agoda_Validator (Input Validation Class)

**Location:** `includes/class-agoda-validator.php`

**Purpose:** Validates and sanitizes user input.

### Public Methods

- `validate_dates( $check_in, $check_out )` - Validate date range
  - **Parameters:**
    - `$check_in` (string) - Check-in date (YYYY-MM-DD)
    - `$check_out` (string) - Check-out date (YYYY-MM-DD)
  - **Returns:** `bool|WP_Error` - True if valid, WP_Error otherwise
  - **Validation Rules:**
    - Check-in date must be >= today
    - Check-out date must be > check-in date
    - Dates must be in YYYY-MM-DD format

- `validate_occupancy( $adults, $children, $children_ages )` - Validate occupancy
  - **Parameters:**
    - `$adults` (int) - Number of adults
    - `$children` (int) - Number of children
    - `$children_ages` (array) - Children ages array
  - **Returns:** `bool|WP_Error` - True if valid, WP_Error otherwise
  - **Validation Rules:**
    - Adults must be >= 1
    - Children must be >= 0
    - Children ages array length must match number of children

- `sanitize_input( $input )` - Sanitize input data
  - **Parameters:**
    - `$input` (mixed) - Input to sanitize
  - **Returns:** `mixed` - Sanitized input

---

## Agoda_Cache (Caching Class)

**Location:** `includes/class-agoda-cache.php`

**Purpose:** Handles caching of API responses using WordPress Transients API.

### Public Methods

- `get_cache( $key )` - Get cached data
  - **Parameters:**
    - `$key` (string) - Cache key
  - **Returns:** `mixed|false` - Cached data or false if not found

- `set_cache( $key, $data, $duration )` - Set cache data
  - **Parameters:**
    - `$key` (string) - Cache key
    - `$data` (mixed) - Data to cache
    - `$duration` (int, optional) - Cache duration in seconds (default: from options)
  - **Returns:** `bool` - True on success, false on failure

- `clear_cache( $key )` - Clear cache
  - **Parameters:**
    - `$key` (string, optional) - Cache key (clears all if not provided)
  - **Returns:** `bool` - True on success, false on failure

### Cache Keys Format

- Search results: `agoda_search_{hash}` where hash is MD5 of search parameters
- City list: `agoda_cities_{language}` (if implemented)

---

## Class Relationships

```
Agoda_Booking (Main)
    ├── Agoda_Admin (Admin Interface)
    │   └── Uses: Settings API, Admin Views
    │
    ├── Agoda_Frontend (Public Interface)
    │   ├── Uses: Agoda_API, Agoda_Validator, Agoda_Cache
    │   └── Uses: Public Views, AJAX
    │
    ├── Agoda_API (API Integration)
    │   ├── Uses: Agoda_Cache (for caching responses)
    │   └── Uses: Agoda_Validator (for input validation)
    │
    ├── Agoda_Validator (Input Validation)
    │   └── Standalone utility class
    │
    └── Agoda_Cache (Caching)
        └── Standalone utility class
```

---

## WordPress Hooks Used

### Actions

- `plugins_loaded` - Load text domain
- `admin_menu` - Add settings page
- `admin_init` - Register settings
- `admin_enqueue_scripts` - Enqueue admin assets
- `wp_enqueue_scripts` - Enqueue frontend assets
- `wp_ajax_agoda_search` - AJAX search handler (logged in)
- `wp_ajax_nopriv_agoda_search` - AJAX search handler (not logged in)

### Filters

- `agoda_search_params` - Filter search parameters (future)
- `agoda_hotel_card` - Filter hotel card display (future)
- `agoda_error_message` - Filter error messages (future)

### Shortcodes

- `[agoda_search]` - Display search form

---

## Data Flow

### Search Flow

1. User submits search form (frontend)
2. JavaScript sends AJAX request
3. `Agoda_Frontend::ajax_search()` receives request
4. `Agoda_Validator` validates input
5. `Agoda_Cache` checks for cached results
6. If not cached, `Agoda_API::search_city()` or `search_hotels()` is called
7. `Agoda_API` prepares request, sends to Agoda, parses response
8. Results are cached via `Agoda_Cache`
9. Results are returned as JSON to frontend
10. Frontend displays results

### Settings Flow

1. Admin accesses Settings > Agoda Booking
2. `Agoda_Admin::render_settings_page()` displays form
3. User saves settings
4. WordPress Settings API validates and saves
5. Settings are stored in `wp_options` table

---

## Notes

- All classes follow WordPress Coding Standards
- All methods include PHPDoc comments
- Security: Nonces, capability checks, input sanitization, output escaping
- Error handling: WP_Error objects for API errors
- Caching: Uses WordPress Transients API
- Internationalization: All strings are translatable
