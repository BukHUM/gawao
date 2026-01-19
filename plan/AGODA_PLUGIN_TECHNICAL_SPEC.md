# Agoda Plugin - Technical Specifications

## 🔧 API Specifications

### ⚠️ สำคัญ: มี API หลายตัวที่แตกต่างกัน

#### 1. Affiliate Lite API V2.0 (Search API) - ใช้ใน Plugin นี้

**Endpoint:**
```
http://affiliateapi7643.agoda.com/affiliateservice/lt_v1
```

**Authentication:**
```http
Authorization: {siteId}:{apiKey}
Accept-Encoding: gzip,deflate
Content-Type: application/json
```

**Request Method:** HTTP POST

**Request Types:**
- **City Search**: Search by city ID
- **Hotel List Search**: Search by array of hotel IDs

#### 2. Content API (Data Feed) - สำหรับอนาคต (Optional)

**Endpoint:**
```
https://[baseURL]/datafeeds/feed/getfeed
```

**Authentication:**
```
Query Parameters: token={token}&site_id={site_id}
```

**Request Method:** HTTP GET

**Feed Types:**
- Feed 1: Continents
- Feed 2: Countries
- Feed 3: Cities
- Feed 4: Areas
- Feed 5: Hotels
- Feed 19: Hotel Information (full details)
- Feed 32: Hotel Changes (daily updates)

**Documentation:** https://developer.agoda.com/demand/docs/content-api

---

## 📋 Request Schema

### City Search Request
```json
{
  "criteria": {
    "additional": {
      "currency": "USD",
      "dailyRate": {
        "maximum": 10000,
        "minimum": 1
      },
      "discountOnly": false,
      "language": "en-us",
      "maxResult": 10,
      "minimumReviewScore": 0,
      "minimumStarRating": 0,
      "occupancy": {
        "numberOfAdult": 2,
        "numberOfChildren": 1,
        "childrenAges": [10, 12]
      },
      "sortBy": "PriceAsc"
    },
    "checkInDate": "2018-09-02",
    "checkOutDate": "2018-09-03",
    "cityId": 9395
  }
}
```

### Hotel List Search Request
```json
{
  "criteria": {
    "additional": {
      "currency": "USD",
      "discountOnly": false,
      "language": "en-us",
      "occupancy": {
        "numberOfAdult": 2,
        "numberOfChildren": 1,
        "childrenAges": [10, 12]
      }
    },
    "checkInDate": "2018-09-02",
    "checkOutDate": "2018-09-03",
    "hotelId": [407854]
  }
}
```

### Required Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| cityId / hotelId | Integer / Array | Yes | City ID or Hotel ID array |
| checkInDate | String (YYYY-MM-DD) | Yes | Check-in date |
| checkOutDate | String (YYYY-MM-DD) | Yes | Check-out date |
| language | String | No | Language code (default: en-us) |
| currency | String | No | Currency code (default: USD) |

### Optional Parameters (City Search Only)
| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| sortBy | String | "Recommended" | Sort order |
| maxResult | Integer (1-30) | 10 | Maximum results |
| discountOnly | Boolean | false | Show only discounted hotels |
| minimumStarRating | Double (0-5) | 0 | Minimum star rating |
| minimumReviewScore | Double (1-10) | 0 | Minimum review score |
| dailyRate.minimum | Decimal | 0 | Minimum daily rate |
| dailyRate.maximum | Decimal | 100000 | Maximum daily rate |

### Occupancy Parameters
| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| numberOfAdult | Integer | 2 | Number of adults |
| numberOfChildren | Integer | 0 | Number of children |
| childrenAges | Array of Integer | [] | Children ages (must match numberOfChildren) |

---

## 📤 Response Schema

### Success Response
```json
{
  "results": [
    {
      "crossedOutRate": 50.34,
      "currency": "USD",
      "dailyRate": 18.54,
      "discountPercentage": 0,
      "freeWifi": true,
      "hotelId": 463019,
      "hotelName": "Decor Do Hostel",
      "imageURL": "http://pix6.agoda.net/hotellmages/463/463019/463019_16030116190040357686.jpg?s=800x600",
      "includeBreakfast": false,
      "landingURL": "https://www.agoda.com/partners/partnersearch.aspx?cid=1752828&hid=463019&currency=USD&checkin=2017-09-02&checkout=2017-09-03&NumberofAdults=2&NumberofChildren=1&Rooms=1",
      "reviewScore": 8.1,
      "starRating": 2
    }
  ]
}
```

### Response Fields
| Field | Type | Description |
|-------|------|-------------|
| hotelId | Integer | Hotel ID |
| hotelName | String | Hotel name |
| dailyRate | Decimal | Daily rate |
| crossedOutRate | Decimal | Original rate (if discounted) |
| currency | String | Currency code |
| discountPercentage | Integer (0-100) | Discount percentage |
| starRating | Integer (0-5) | Star rating |
| reviewScore | Double (1-10) | Review score |
| imageURL | String | Hotel image URL |
| landingURL | String | Booking URL |
| freeWifi | Boolean | Free WiFi available |
| includeBreakfast | Boolean | Breakfast included |

---

## 🚨 HTTP Status Codes

### Success Codes
| Code | Meaning | Description |
|------|---------|-------------|
| 200 | OK | Request processed successfully |
| 202 | Accepted | Request queued for processing |
| 204 | No Content | No valid data returned |
| 206 | Partial Content | Partial results (asynchronous search) |

### Error Codes
| Code | Meaning | Description |
|------|---------|-------------|
| 400 | Bad Request | Malformed request |
| 401 | Unauthorized | Invalid API key or IP restriction |
| 403 | Forbidden | Terms violation or quota exceeded |
| 404 | Not Found | Service not found |
| 410 | Gone | Request object too old or invalid |
| 500 | Internal Server Error | Server error |
| 503 | Service Unavailable | Service temporarily unavailable |
| 506 | Partial Confirm | Cannot process entire request |

---

## 🌍 Supported Languages

Common languages:
- `en-us` - English (US)
- `th-th` - Thai
- `zh-cn` - Simplified Chinese
- `zh-tw` - Traditional Chinese
- `ja-jp` - Japanese
- `ko-kr` - Korean

(Full list available in PDF documentation)

---

## 💱 Supported Currencies

Common currencies:
- `USD` - US Dollar
- `THB` - Thai Baht
- `EUR` - Euro
- `GBP` - British Pound
- `JPY` - Japanese Yen
- `CNY` - Chinese Yuan

(Full list available in PDF documentation)

---

## 🔄 Sort Options (City Search Only)

| Value | Description |
|-------|-------------|
| Recommended | Recommended (default) |
| PriceAsc | Price: Low to High |
| PriceDesc | Price: High to Low |
| StarRatingAsc | Star Rating: Low to High |
| StarRatingDesc | Star Rating: High to Low |
| AllGuestsReviewScore | Review Score: All Guests |
| BusinessTravellerReviewScore | Review Score: Business Travellers |
| CouplesReviewScore | Review Score: Couples |
| SoloTravellersReviewScore | Review Score: Solo Travellers |
| FamiliesWithYoungReviewScore | Review Score: Families with Young Children |
| FamiliesWithTeenReviewScore | Review Score: Families with Teens |
| GroupsReviewScore | Review Score: Groups |

---

## 🏗️ Database Schema

### WordPress Options (wp_options table)
```sql
-- Plugin Settings (Affiliate Lite API)
option_name: 'agoda_site_id'
option_value: '123456'  -- Site ID for Affiliate Lite API authentication (used in Authorization header)

option_name: 'agoda_api_key'
option_value: '00000000-0000-0000-0000-00000000000'  -- API Key for Affiliate Lite API authentication

option_name: 'agoda_cid'
option_value: '1752828'  -- CID (Customer ID) for affiliate tracking in landing URLs (optional, uses Site ID if empty)
-- Note: CID is found in Affiliate Dashboard → Profile → Manage Your Sites
-- This is DIFFERENT from Site ID for API authentication

-- Plugin Settings (Content API - Optional, for future enhancement)
option_name: 'agoda_content_api_token'
option_value: 'token_here'  -- Token for Content API authentication (optional)

option_name: 'agoda_content_api_site_id'
option_value: '1752828'  -- Site ID for Content API (may be same as CID or different)

option_name: 'agoda_content_api_endpoint'
option_value: 'https://[baseURL]/datafeeds/feed/getfeed'  -- Content API endpoint (optional)

-- General Settings
option_name: 'agoda_default_language'
option_value: 'en-us'

option_name: 'agoda_default_currency'
option_value: 'USD'

option_name: 'agoda_api_endpoint'
option_value: 'http://affiliateapi7643.agoda.com/affiliateservice/lt_v1'

option_name: 'agoda_cache_duration'
option_value: '3600'  -- 1 hour in seconds
```

### WordPress Transients (wp_options table)
```sql
-- Cached Search Results
option_name: '_transient_agoda_search_{hash}'
option_value: '{serialized search results}'
expiration: {current_time + cache_duration}
```

---

## 🔐 Security Requirements

### API Credentials
- Store in WordPress Options API (encrypted if possible)
- Never expose in frontend JavaScript
- Validate format before saving
- Test connection on save

### Input Validation
- Sanitize all user inputs
- Validate date formats (YYYY-MM-DD)
- Validate numeric inputs (integers, decimals)
- Validate array structures
- Check date ranges (check-out > check-in)

### Output Security
- Escape all outputs
- Use WordPress escaping functions
- Sanitize URLs before display
- Validate nonces for forms

### Capabilities
- Settings page: `manage_options`
- AJAX requests: Verify nonces
- Admin functions: Check user capabilities

---

## 📦 WordPress Hooks

### Actions
```php
// Plugin activation
register_activation_hook(__FILE__, 'agoda_booking_activate');

// Plugin deactivation
register_deactivation_hook(__FILE__, 'agoda_booking_deactivate');

// Admin menu
add_action('admin_menu', 'agoda_booking_add_settings_page');

// Enqueue scripts
add_action('wp_enqueue_scripts', 'agoda_booking_enqueue_scripts');
add_action('admin_enqueue_scripts', 'agoda_booking_enqueue_admin_scripts');

// AJAX handlers
add_action('wp_ajax_agoda_search', 'agoda_booking_ajax_search');
add_action('wp_ajax_nopriv_agoda_search', 'agoda_booking_ajax_search');
```

### Filters
```php
// Customize search parameters
apply_filters('agoda_search_params', $params);

// Customize results display
apply_filters('agoda_hotel_card', $hotel_data, $template);

// Customize error messages
apply_filters('agoda_error_message', $message, $error_code);
```

---

## 🧪 Testing Scenarios

### Success Cases
1. ✅ City search with valid parameters
2. ✅ Hotel list search with valid parameters
3. ✅ Search with filters (price, rating, discount)
4. ✅ Search with children and ages
5. ✅ Multiple results returned
6. ✅ Single result returned

### Error Cases
1. ❌ Invalid API credentials (401)
2. ❌ Invalid city ID (400/404)
3. ❌ Invalid date range (check-out <= check-in)
4. ❌ Past dates
5. ❌ Empty results (204)
6. ❌ API timeout (503)
7. ❌ Network error
8. ❌ Invalid response format

### Edge Cases
1. ⚠️ Very long stay (>30 days)
2. ⚠️ Many children (10+)
3. ⚠️ Very large hotel list (100+ hotels)
4. ⚠️ Special characters in input
5. ⚠️ Concurrent searches
6. ⚠️ Cache expiration during search

---

## 📊 Performance Considerations

### Caching Strategy
- Cache duration: 1 hour (configurable)
- Cache key: MD5 hash of search parameters
- Clear cache: On settings update, manual clear
- Cache storage: WordPress Transients API

### API Optimization
- Request timeout: 30 seconds
- Retry logic: 3 attempts with exponential backoff
- Rate limiting: Respect API limits
- Batch requests: Not applicable (single request per search)

### Frontend Optimization
- Lazy load images
- Pagination for large results
- AJAX loading (no page reload)
- Minify CSS/JS for production

---

## 🔄 Error Handling Flow

```
User submits search
    ↓
Validate input (client-side)
    ↓
Send AJAX request
    ↓
Validate input (server-side)
    ↓
Check cache
    ↓
If cached → Return cached results
    ↓
If not cached → Call Agoda API
    ↓
    ├─ Success (200) → Parse response → Cache → Return results
    ├─ Error (400/401/403) → Return error message
    ├─ Error (500/503) → Retry (max 3 times) → Return error if failed
    └─ Network error → Return error message
    ↓
Display results/error to user
```

---

## 📝 Code Structure Example

### Main Plugin File
```php
<?php
/**
 * Plugin Name: Agoda Booking
 * Description: Search and book hotels through Agoda API
 * Version: 1.0.0
 * Author: Your Name
 */

if (!defined('ABSPATH')) {
    exit;
}

define('AGODA_BOOKING_VERSION', '1.0.0');
define('AGODA_BOOKING_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AGODA_BOOKING_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-api.php';
require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-admin.php';
require_once AGODA_BOOKING_PLUGIN_DIR . 'includes/class-agoda-frontend.php';

// Initialize plugin
function agoda_booking_init() {
    // Initialize classes
}
add_action('plugins_loaded', 'agoda_booking_init');
```

---

## 🎯 Implementation Priority

### Phase 1 (Critical)
1. API Integration Class
2. Basic Search Functionality
3. Admin Settings
4. Error Handling

### Phase 2 (Important)
1. Frontend Search Form
2. Results Display
3. Input Validation
4. Caching

### Phase 3 (Enhancement)
1. Advanced Filters
2. UI/UX Improvements
3. Performance Optimization
4. Documentation

---

**หมายเหตุ:** Technical specifications นี้ควรอ้างอิงจากเอกสาร Agoda API ล่าสุดเสมอ และอาจมีการเปลี่ยนแปลงตาม API version
