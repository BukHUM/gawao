# แผนการสร้าง WordPress Plugin สำหรับจองที่พักผ่าน Agoda API

## 📋 ภาพรวมโปรเจค

สร้าง WordPress Plugin สำหรับค้นหาและจองที่พักผ่าน Agoda โดยใช้:
- **Affiliate Lite API V2.0** (Search API) - สำหรับค้นหาโรงแรม
- **Content API** (Data Feed) - สำหรับดึงข้อมูล hotel content (optional, future enhancement)
- **Agoda Demand API** - สำหรับการจอง (optional, future enhancement)

### ⚠️ สำคัญ: API Types & Authentication

**Agoda แบ่ง API เป็น 2 ส่วน:**
- **Supply APIs** (Direct Supply): สำหรับโรงแรม/Channel Manager - ใช้ OAuth 2.0
- **Demand APIs**: สำหรับ Affiliate Partners - ใช้ใน plugin นี้

#### Affiliate Lite API V2.0 (ปัจจุบันใช้) - Demand API
- **Authentication**: `Authorization: {siteId}:{apiKey}` (HTTP POST)
- **Purpose**: ค้นหาโรงแรม (Search)
- **Endpoint**: `http://affiliateapi7643.agoda.com/affiliateservice/lt_v1`
- **Note**: ไม่ใช่ OAuth 2.0 (ใช้ Authorization header แบบเดิม)

#### Content API (สำหรับอนาคต) - Demand API
- **Authentication**: `token` + `site_id` ใน query parameters (HTTP GET)
- **Purpose**: ดึงข้อมูล hotel content (Data Feed)
- **Endpoint**: `https://[baseURL]/datafeeds/feed/getfeed`
- **Documentation**: https://developer.agoda.com/demand/docs/content-api

#### Supply APIs (ไม่เกี่ยวข้องกับ plugin นี้)
- **Authentication**: OAuth 2.0 (Token-Based) - เริ่มใช้ 2025
- **APIs**: YCS API, OTA API, Content Push API, Promotion API
- **Documentation**: https://developer.agoda.com/supply/docs/authentication-2025
- **Note**: ใช้สำหรับโรงแรม/Channel Manager ไม่ใช่ Affiliate Partners

#### Site ID vs CID
- **CID (Customer ID)**: หาได้จาก Affiliate Dashboard → Profile → Manage Your Sites (ใช้สำหรับ affiliate tracking)
- **Site ID (API)**: หาได้จาก Developer Portal หรือ Account Manager (ใช้สำหรับ API authentication)
- อาจเป็นตัวเดียวกันหรือต่างกัน (ต้องตรวจสอบกับ Account Manager)

---

## 🎯 Phase 1: การวิเคราะห์และเตรียมความพร้อม (Analysis & Preparation)

### 1.1 วิเคราะห์ความต้องการ
- [ ] **กำหนดขอบเขตการใช้งาน**
  - ใช้ Affiliate Lite API (Search + Redirect) หรือ Demand API (Search + Book)
  - ตรวจสอบว่าเป็น Affiliate Model หรือ Partner Fulfillment Model
  
- [ ] **อ่านเอกสารให้ครบถ้วน**
  - Affiliate Lite API V2.0 (PDF ที่มี)
  - Agoda Demand API Documentation (https://developer.agoda.com/demand/docs/getting-started)
  - Best Practices & Certification Process

- [ ] **เตรียมข้อมูล API Credentials**
  - **Affiliate Lite API**:
    - Site ID (สำหรับ API authentication)
    - API Key (สำหรับ API authentication)
  - **CID (Customer ID)**:
    - CID สำหรับ affiliate tracking (หาได้จาก Affiliate Dashboard)
  - **Content API** (optional, สำหรับอนาคต):
    - Token
    - Site ID (อาจเป็นตัวเดียวกับ CID หรือ Site ID ของ Affiliate Lite API)
  - ตรวจสอบว่าได้ Sandbox access หรือยัง

### 1.2 กำหนดฟีเจอร์หลัก
- [ ] **Search Functionality**
  - City Search (ค้นหาจากเมือง)
  - Hotel List Search (ค้นหาจากรายชื่อโรงแรม)
  - Filter options (ราคา, ดาว, review score, discount)
  
- [ ] **Display Results**
  - แสดงรายการโรงแรม
  - แสดงราคา, รูปภาพ, rating
  - Landing URL สำหรับ redirect
  
- [ ] **Settings Management**
  - ตั้งค่า Site ID และ API Key
  - ตั้งค่า Default language, currency
  - ตั้งค่า Default search parameters

---

## 🏗️ Phase 2: ออกแบบโครงสร้าง Plugin (Plugin Architecture)

### 2.1 สร้างโครงสร้างโฟลเดอร์
```
wp-content/plugins/agoda-booking/
├── agoda-booking.php          # Main plugin file
├── readme.txt                 # Plugin description
├── uninstall.php              # Cleanup on uninstall
├── includes/
│   ├── class-agoda-api.php    # API integration class
│   ├── class-agoda-admin.php  # Admin settings page
│   ├── class-agoda-frontend.php # Frontend display
│   ├── class-agoda-validator.php # Input validation
│   └── class-agoda-cache.php  # Caching mechanism
├── admin/
│   ├── css/
│   │   └── admin.css          # Admin styles
│   ├── js/
│   │   └── admin.js           # Admin scripts
│   └── views/
│       └── settings.php       # Settings page template
├── public/
│   ├── css/
│   │   └── frontend.css       # Frontend styles
│   ├── js/
│   │   └── frontend.js        # Frontend scripts
│   └── views/
│       ├── search-form.php    # Search form template
│       └── results.php        # Results display template
├── languages/
│   └── (translation files)
└── assets/
    └── (images, icons)
```

### 2.2 กำหนด Database Schema
- [ ] **Options Table** (ใช้ WordPress Options API)
  - `agoda_site_id` - Site ID (ใช้ใน Authorization header สำหรับ API authentication)
  - `agoda_api_key` - API Key
  - `agoda_cid` - CID (Customer ID สำหรับ affiliate tracking ใน landing URL, optional - ถ้าไม่ใส่จะใช้ Site ID แทน)
  - `agoda_default_language` - Default language (default: en-us)
  - `agoda_default_currency` - Default currency (default: USD)
  - `agoda_api_endpoint` - API endpoint URL
  - `agoda_cache_duration` - Cache duration (seconds)

- [ ] **Transients** (สำหรับ caching)
  - `agoda_search_{hash}` - Cache search results
  - `agoda_cities_{language}` - Cache city list (ถ้ามี)

### 2.3 กำหนด Class Structure
```
Agoda_Booking (Main Class)
├── Agoda_API (API Integration)
│   ├── search_city()
│   ├── search_hotels()
│   ├── validate_credentials()
│   └── handle_response()
├── Agoda_Admin (Admin Interface)
│   ├── add_settings_page()
│   ├── register_settings()
│   └── render_settings_page()
├── Agoda_Frontend (Frontend Display)
│   ├── render_search_form()
│   ├── render_results()
│   └── enqueue_scripts()
├── Agoda_Validator (Input Validation)
│   ├── validate_dates()
│   ├── validate_occupancy()
│   └── sanitize_input()
└── Agoda_Cache (Caching)
    ├── get_cache()
    ├── set_cache()
    └── clear_cache()
```

---

## 💻 Phase 3: พัฒนา Core Functionality (Core Development)

### 3.1 สร้าง Main Plugin File
- [ ] **Plugin Header**
  - Plugin Name, Description, Version
  - Author, License
  - Requires WordPress version, PHP version
  
- [ ] **Security Checks**
  - Prevent direct access
  - Nonce verification
  - Capability checks
  
- [ ] **Constants & Autoloader**
  - Define plugin constants (path, URL, version)
  - Autoload classes
  - Register activation/deactivation hooks

### 3.2 พัฒนา API Integration Class
- [ ] **API Request Method**
  ```php
  - prepare_request()      // Build request body
  - send_request()         // cURL/wp_remote_post
  - parse_response()       // Parse JSON response
  - handle_errors()        // Error handling
  ```

- [ ] **City Search Implementation**
  - Validate cityId
  - Build request with criteria
  - Handle response (hotels list)
  - Error handling (400, 401, 403, 500, etc.)

- [ ] **Hotel List Search Implementation**
  - Validate hotelId array
  - Build request with hotel list
  - Handle response
  - Error handling

- [ ] **Request Headers**
  - Authorization: {siteId}:{apiKey}
  - Accept-Encoding: gzip,deflate
  - Content-Type: application/json

- [ ] **Response Parsing**
  - Parse hotel data (name, price, rating, image, URL)
  - Handle empty results
  - Handle error responses

### 3.3 พัฒนา Admin Settings
- [ ] **Settings Page**
  - Add menu item under Settings
  - Create settings form
  - Save settings (sanitize & validate)
  
- [ ] **Settings Fields**
  - Site ID (required, text input)
  - API Key (required, password input)
  - Default Language (dropdown)
  - Default Currency (dropdown)
  - API Endpoint (text input, readonly)
  - Cache Duration (number input)
  - Test Connection button

- [ ] **Settings Validation**
  - Validate Site ID format
  - Validate API Key format
  - Test API connection
  - Show success/error messages

### 3.4 พัฒนา Frontend Display
- [ ] **Search Form**
  - Check-in date (date picker)
  - Check-out date (date picker)
  - City selection (dropdown หรือ autocomplete)
  - Adults/Children input
  - Children ages (ถ้ามี children)
  - Search button
  
- [ ] **Results Display**
  - Hotel cards grid/list
  - Hotel image
  - Hotel name
  - Star rating
  - Review score
  - Price (daily rate, crossed out rate, discount %)
  - Amenities (free WiFi, breakfast)
  - Book button (redirect to landing URL)
  
- [ ] **AJAX Integration**
  - AJAX search (ไม่ reload page)
  - Loading indicator
  - Error messages
  - No results message

### 3.5 Input Validation & Sanitization
- [ ] **Date Validation**
  - Check-in date must be >= today
  - Check-out date must be > check-in date
  - Date format: YYYY-MM-DD
  
- [ ] **Occupancy Validation**
  - Number of adults >= 1
  - Number of children >= 0
  - Children ages array length = numberOfChildren
  
- [ ] **City/Hotel ID Validation**
  - City ID must be integer
  - Hotel ID must be array of integers
  
- [ ] **Sanitization**
  - Sanitize all user inputs
  - Escape output
  - Validate nonces

---

## 🛡️ Phase 4: Error Handling & Security (Error Handling & Security)

### 4.1 Error Handling
- [ ] **API Error Handling**
  - HTTP status codes (200, 400, 401, 403, 404, 500, 503)
  - Network errors (timeout, connection failed)
  - Invalid response format
  - Empty results
  
- [ ] **User-Friendly Error Messages**
  - "Invalid credentials" (401)
  - "Service temporarily unavailable" (503)
  - "No hotels found" (empty results)
  - "Please check your dates" (validation errors)
  
- [ ] **Logging**
  - Log API errors (optional, for debugging)
  - Log validation errors
  - Use WordPress debug log

### 4.2 Security Measures
- [ ] **API Credentials Security**
  - Store credentials in database (encrypted if possible)
  - Never expose in frontend
  - Use WordPress Options API
  
- [ ] **Input Security**
  - Sanitize all inputs
  - Validate data types
  - Use prepared statements (if using custom DB)
  - Escape all outputs
  
- [ ] **Nonce Verification**
  - Add nonces to forms
  - Verify nonces on submission
  - AJAX nonce verification
  
- [ ] **Capability Checks**
  - Check user capabilities for admin functions
  - Limit access to settings page

### 4.3 Rate Limiting & Caching
- [ ] **Caching Strategy**
  - Cache search results (Transients API)
  - Cache duration: configurable (default: 1 hour)
  - Cache key: based on search parameters hash
  - Clear cache on settings update
  
- [ ] **Rate Limiting**
  - Respect API rate limits
  - Implement request throttling (if needed)
  - Show appropriate messages if rate limited

---

## 🎨 Phase 5: UI/UX Development (User Interface)

### 5.1 Frontend Styling
- [ ] **Search Form Styling**
  - Responsive design (mobile, tablet, desktop)
  - Modern, clean design
  - Accessible (ARIA labels)
  - Date picker styling
  
- [ ] **Results Display Styling**
  - Hotel cards layout
  - Image optimization (lazy loading)
  - Price highlighting
  - Rating stars display
  - Responsive grid
  
- [ ] **Loading States**
  - Loading spinner
  - Skeleton screens (optional)
  
- [ ] **Error States**
  - Error message styling
  - Retry button

### 5.2 Admin Styling
- [ ] **Settings Page Styling**
  - WordPress admin style consistency
  - Form layout
  - Help text
  - Success/error message styling

### 5.3 JavaScript Functionality
- [ ] **Date Picker**
  - jQuery UI Datepicker หรือ modern alternative
  - Min date: today
  - Disable past dates
  - Validate date range
  
- [ ] **AJAX Search**
  - Prevent double submission
  - Show loading state
  - Handle success/error
  - Update results without page reload
  
- [ ] **Form Validation (Client-side)**
  - Real-time validation
  - Show validation errors
  - Disable submit if invalid

---

## 🧪 Phase 6: Testing (Testing Phase)

### 6.1 Unit Testing
- [ ] **API Class Testing**
  - Test request building
  - Test response parsing
  - Test error handling
  - Test caching
  
- [ ] **Validator Testing**
  - Test date validation
  - Test occupancy validation
  - Test input sanitization

### 6.2 Integration Testing
- [ ] **API Integration Testing**
  - Test with Sandbox credentials
  - Test City Search
  - Test Hotel List Search
  - Test error scenarios
  
- [ ] **Frontend Testing**
  - Test search form submission
  - Test AJAX functionality
  - Test results display
  - Test redirect to landing URL

### 6.3 User Acceptance Testing
- [ ] **Test Scenarios**
  - Search by city (success case)
  - Search by hotel list (success case)
  - Invalid dates (error case)
  - No results (empty case)
  - API error (error case)
  - Settings save/load
  
- [ ] **Cross-browser Testing**
  - Chrome, Firefox, Safari, Edge
  - Mobile browsers
  
- [ ] **Responsive Testing**
  - Mobile (320px+)
  - Tablet (768px+)
  - Desktop (1024px+)

### 6.4 Edge Cases Testing
- [ ] **Edge Cases**
  - Check-in = Check-out (should fail)
  - Past dates (should fail)
  - Very long stay (>30 days)
  - Many children with ages
  - Special characters in input
  - Very large hotel list
  - API timeout
  - Network failure

---

## 📚 Phase 7: Documentation & Code Quality (Documentation)

### 7.1 Code Documentation
- [ ] **Inline Comments**
  - PHPDoc for all functions
  - Explain complex logic
  - Document parameters and return values
  
- [ ] **Code Standards**
  - Follow WordPress Coding Standards
  - Proper indentation
  - Consistent naming conventions

### 7.2 User Documentation
- [ ] **README File**
  - Installation instructions
  - Configuration guide
  - Usage examples
  - Troubleshooting
  
- [ ] **Admin Help Text**
  - Tooltips for settings
  - Help tabs in admin
  - Links to documentation

### 7.3 Developer Documentation
- [ ] **Code Comments**
  - Architecture overview
  - API integration details
  - Hooks and filters documentation

---

## 🚀 Phase 8: Deployment Preparation (Pre-Launch)

### 8.1 Pre-Launch Checklist
- [ ] **Code Review**
  - Remove debug code
  - Remove commented code
  - Optimize queries
  - Minify CSS/JS (optional)
  
- [ ] **Security Audit**
  - Check all inputs sanitized
  - Check all outputs escaped
  - Check nonce verification
  - Check capability checks
  
- [ ] **Performance Optimization**
  - Enable caching
  - Optimize images
  - Minimize HTTP requests
  - Check database queries

### 8.2 Agoda Certification Preparation
- [ ] **Best Practices Compliance**
  - Follow Agoda Best Practices
  - Test in Sandbox thoroughly
  - Prepare for certification process
  - Document all features
  
- [ ] **API Compliance**
  - Correct request format
  - Correct response handling
  - Proper error handling
  - Surcharge display (if applicable)

### 8.3 Production Readiness
- [ ] **Environment Setup**
  - Production API credentials
  - HTTPS enabled
  - SSL certificate valid
  - DNS TTL ≤ 5 minutes (if applicable)
  
- [ ] **Monitoring Setup**
  - Error logging
  - Performance monitoring
  - API call tracking
  - User activity tracking (optional)

---

## 🔄 Phase 9: Launch & Maintenance (Launch)

### 9.1 Launch Steps
- [ ] **Final Testing**
  - Test with production credentials
  - Test all features
  - Test error scenarios
  
- [ ] **Go Live**
  - Activate plugin
  - Configure settings
  - Monitor for errors
  - Collect user feedback

### 9.2 Post-Launch
- [ ] **Monitoring**
  - Monitor error logs
  - Monitor API response times
  - Monitor user feedback
  - Track conversion rates (if applicable)
  
- [ ] **Maintenance**
  - Regular updates
  - Security patches
  - API changes (if Agoda updates API)
  - Bug fixes

---

## 📝 Phase 10: Future Enhancements (Optional)

### 10.1 Content API Integration (Data Feed)
- [ ] **Content API Integration**
  - Implement Content API class (`class-agoda-content-api.php`)
  - Fetch hotel content (Feed 5: Hotels)
  - Fetch city list (Feed 3: Cities)
  - Fetch country list (Feed 2: Countries)
  - Fetch hotel details (Feed 19: Hotel Information)
  - Daily updates (Feed 32: Hotel Changes)
  - Cache hotel content locally
  - Sync hotel data periodically
  
- [ ] **Hotel Content Management**
  - Store hotel information in WordPress database
  - Display hotel details page
  - Show hotel amenities, policies, images
  - Multi-language hotel descriptions
  - Hotel search by name/keyword (using local data)

### 10.2 Additional Features
- [ ] **Advanced Features**
  - Hotel favorites/bookmarks
  - Search history
  - Price alerts
  - Multi-language support (frontend)
  - Currency conversion
  - Hotel details page (with Content API data)
  - Reviews display
  - Hotel comparison feature
  
- [ ] **Integration Features**
  - Shortcodes for search form
  - Widget for search form
  - Gutenberg blocks
  - REST API endpoints
  - Hotel listing page (using Content API)

### 10.2 Performance Improvements
- [ ] **Optimization**
  - Database optimization
  - Caching improvements
  - CDN integration
  - Image optimization

---

## 🎯 สรุป Checklist หลัก

### Critical Path (ต้องทำก่อน)
1. ✅ อ่านและเข้าใจ API Documentation
2. ✅ สร้างโครงสร้าง Plugin
3. ✅ พัฒนา API Integration Class
4. ✅ พัฒนา Admin Settings
5. ✅ พัฒนา Frontend Search Form
6. ✅ พัฒนา Results Display
7. ✅ Error Handling
8. ✅ Security Implementation
9. ✅ Testing (Sandbox)
10. ✅ Documentation

### Important (ควรทำ)
- Caching
- Input Validation
- UI/UX Polish
- Cross-browser Testing
- Performance Optimization

### Nice to Have (ทำเพิ่มเติม)
- Advanced Features
- Widget/Shortcode Support
- Multi-language Frontend
- Analytics Integration

---

## ⚠️ จุดที่มักเกิด Error และวิธีป้องกัน

### 1. API Integration Errors
**ปัญหา:**
- Invalid request format
- Missing required headers
- Wrong credentials format

**วิธีป้องกัน:**
- ใช้ constants สำหรับ headers
- Validate request body ก่อนส่ง
- Test credentials format

### 2. Date Validation Errors
**ปัญหา:**
- Check-out <= Check-in
- Past dates
- Invalid date format

**วิธีป้องกัน:**
- Validate dates ทั้ง client-side และ server-side
- Use date picker ที่ป้องกัน invalid dates
- Clear error messages

### 3. Response Parsing Errors
**ปัญหา:**
- Invalid JSON response
- Missing expected fields
- Unexpected response structure

**วิธีป้องกัน:**
- Check response format ก่อน parse
- Use isset() checks
- Handle missing fields gracefully

### 4. Security Issues
**ปัญหา:**
- Exposed API credentials
- SQL injection
- XSS attacks

**วิธีป้องกัน:**
- Never expose credentials in frontend
- Sanitize all inputs
- Escape all outputs
- Use nonces

### 5. Caching Issues
**ปัญหา:**
- Stale cache
- Cache not clearing
- Cache key conflicts

**วิธีป้องกัน:**
- Use appropriate cache duration
- Clear cache on settings update
- Use unique cache keys
- Test cache behavior

---

## 📞 Resources & Links

### Agoda API Documentation
- **Affiliate Lite API V2.0**: `plan/Affiliate_Lite_API_V2.0.pdf`
- **Content API**: https://developer.agoda.com/demand/docs/content-api
- **Agoda Developer Portal**: https://developer.agoda.com/demand/docs/getting-started
- **Best Practices & Certification**: https://developer.agoda.com/demand/docs/best-practices-certification-process
- **Agoda Affiliate Dashboard**: https://partners.agoda.com

### WordPress Resources
- **WordPress Plugin Handbook**: https://developer.wordpress.org/plugins/
- **WordPress Coding Standards**: https://developer.wordpress.org/coding-standards/wordpress-coding-standards/

### Internal Documentation
- **API Information**: `plan/agoda_api.md`
- **Technical Specifications**: `plan/AGODA_PLUGIN_TECHNICAL_SPEC.md`
- **Quick Checklist**: `plan/AGODA_PLUGIN_CHECKLIST.md`

---

## 🎬 ขั้นตอนการเริ่มต้น (Quick Start)

1. **อ่านเอกสาร** - อ่าน PDF และ Agoda Developer Docs ให้เข้าใจ
2. **เตรียม Credentials** - ขอ Site ID และ API Key จาก Agoda
3. **สร้างโครงสร้าง** - สร้างโฟลเดอร์และไฟล์พื้นฐาน
4. **พัฒนา API Class** - เริ่มจาก API integration ก่อน
5. **ทดสอบ API** - Test กับ Sandbox ให้ทำงานได้ก่อน
6. **พัฒนา Frontend** - สร้าง search form และ results display
7. **เพิ่ม Security** - Implement security measures
8. **Testing** - Test ทุก scenario
9. **Documentation** - เขียน documentation
10. **Launch** - Deploy และ monitor

---

**หมายเหตุ:** แผนนี้เป็นแนวทางทั่วไป ควรปรับแต่งตามความต้องการเฉพาะของโปรเจค และตรวจสอบกับ Agoda Documentation ล่าสุดเสมอ
