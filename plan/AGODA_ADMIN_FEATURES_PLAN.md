# แผนฟีเจอร์หลังบ้าน (Admin Features) สำหรับ Agoda Booking Plugin

## 📊 สถานะปัจจุบัน

### ✅ ที่มีอยู่แล้ว
- Settings Page (API credentials, default settings)
- Test Connection (ทดสอบการเชื่อมต่อ API)
- Rate Limiting Settings
- Cache Settings

### 🎯 ฟีเจอร์ที่ควรเพิ่มเติม (เรียงตามลำดับความสำคัญ)

---

## 🔥 Phase 1: ฟีเจอร์พื้นฐานที่ควรมีทันที (High Priority)

### 1.1 Dashboard/Overview Page
**วัตถุประสงค์**: แสดงภาพรวมและสถิติการใช้งาน

**ฟีเจอร์:**
- [ ] **API Status Widget**
  - แสดงสถานะการเชื่อมต่อ API (Connected/Disconnected)
  - แสดงจำนวนครั้งที่เรียก API วันนี้
  - แสดง Rate Limit usage (ถ้าเปิดใช้งาน)
  
- [ ] **Quick Stats**
  - จำนวน Search ที่ทำไปแล้ว (วันนี้/สัปดาห์นี้/เดือนนี้)
  - จำนวน Hotels ที่พบ (เฉลี่ย)
  - Cache Hit Rate
  - API Response Time (เฉลี่ย)

- [ ] **Quick Actions**
  - ปุ่ม Test Connection
  - ปุ่ม Clear Cache
  - ปุ่ม View Logs

**Menu Structure:**
```
Agoda Booking (Main Menu)
├── Dashboard (Overview) ← หน้าใหม่
├── Settings (ปัจจุบัน)
├── Hotel Search (Preview) ← หน้าใหม่
├── Cache Management ← หน้าใหม่
└── API Logs ← หน้าใหม่
```

---

### 1.2 Hotel Search Preview (Admin)
**วัตถุประสงค์**: ทดสอบค้นหาโรงแรมในหลังบ้านก่อนนำไปใช้จริง

**ฟีเจอร์:**
- [ ] **Search Form** (เหมือน frontend แต่ใน admin)
  - City selection (dropdown หรือ autocomplete)
  - Check-in/Check-out date picker
  - Adults/Children input
  - Filters (price, rating, discount)
  - Sort options

- [ ] **Results Preview**
  - แสดงผลลัพธ์ในรูปแบบ table/cards
  - แสดงข้อมูล: Hotel Name, Price, Rating, Image, Landing URL
  - ปุ่ม "Copy Landing URL" สำหรับแต่ละโรงแรม
  - ปุ่ม "View Details" (modal หรือ expand)

- [ ] **Test Different Scenarios**
  - ทดสอบ City Search
  - ทดสอบ Hotel List Search
  - ทดสอบ Filters
  - ทดสอบ Error cases

**Use Cases:**
- ทดสอบ API ก่อนนำไปใช้จริง
- ตรวจสอบว่า Landing URL ถูกต้อง
- ดูตัวอย่างผลลัพธ์ก่อนแสดงให้ผู้ใช้

---

### 1.3 Cache Management
**วัตถุประสงค์**: จัดการ cache อย่างมีประสิทธิภาพ

**ฟีเจอร์:**
- [ ] **Cache Overview**
  - แสดงจำนวน cache entries
  - แสดง cache size (ถ้าเป็นไปได้)
  - แสดง cache hit/miss statistics

- [ ] **Cache Operations**
  - ปุ่ม "Clear All Cache"
  - ปุ่ม "Clear Expired Cache"
  - ปุ่ม "Clear Cache by Pattern" (เช่น clear cache ของ city ID 9395)

- [ ] **Cache Settings**
  - Cache duration (มีอยู่แล้วใน Settings)
  - Enable/Disable cache
  - Cache statistics

- [ ] **Cache Preview** (Optional)
  - แสดงรายการ cache entries (paginated)
  - แสดง expiration time
  - ปุ่ม "View Cache Content" (debug)

---

### 1.4 API Logs Viewer
**วัตถุประสงค์**: ดู logs ของ API calls สำหรับ debugging

**ฟีเจอร์:**
- [ ] **Log List**
  - แสดง logs แบบ table (paginated)
  - Filter: Date range, Log level (ERROR/WARNING/INFO/DEBUG)
  - Search: Search in log messages
  - Sort: By date, by level

- [ ] **Log Details**
  - แสดง full log entry
  - Request details (endpoint, parameters, headers - mask sensitive data)
  - Response details (status code, response body)
  - Error details (ถ้ามี)

- [ ] **Log Actions**
  - Export logs (CSV/JSON)
  - Clear old logs (older than X days)
  - Download logs

- [ ] **Log Settings**
  - Enable/Disable logging
  - Log level (ERROR/WARNING/INFO/DEBUG)
  - Log retention (days)

---

## 🚀 Phase 2: ฟีเจอร์เพิ่มเติม (Medium Priority)

### 2.1 City Management (ถ้าใช้ Content API)
**วัตถุประสงค์**: จัดการ city list สำหรับค้นหา

**ฟีเจอร์:**
- [ ] **City List**
  - แสดงรายการ cities (จาก Content API Feed 3)
  - Search city by name
  - Filter by country
  - Cache city list locally

- [ ] **City Details**
  - แสดง city information
  - แสดงจำนวน hotels ใน city
  - แสดง popular hotels

**Note**: ต้องมี Content API credentials ก่อน

---

### 2.2 Statistics & Analytics
**วัตถุประสงค์**: วิเคราะห์การใช้งาน

**ฟีเจอร์:**
- [ ] **Usage Statistics**
  - Chart: Searches per day/week/month
  - Chart: Popular cities
  - Chart: Popular hotels
  - Chart: Average response time

- [ ] **Performance Metrics**
  - API response time (average, min, max)
  - Cache hit rate
  - Error rate
  - API quota usage

- [ ] **Export Reports**
  - Export statistics (CSV/PDF)
  - Scheduled reports (email)

---

### 2.3 Bulk Operations
**วัตถุประสงค์**: ดำเนินการหลายรายการพร้อมกัน

**ฟีเจอร์:**
- [ ] **Bulk Hotel Search**
  - Upload hotel ID list (CSV)
  - Search multiple hotels at once
  - Export results

- [ ] **Bulk Cache Clear**
  - Clear cache for multiple cities
  - Clear cache for date range

---

## 💡 Phase 3: ฟีเจอร์ขั้นสูง (Low Priority - Future)

### 3.1 Content API Integration
- [ ] Sync hotel data from Content API
- [ ] Store hotels in database
- [ ] Hotel management interface
- [ ] Hotel details editor

### 3.2 Advanced Settings
- [ ] API endpoint configuration
- [ ] Custom headers
- [ ] Proxy settings
- [ ] Webhook configuration

### 3.3 User Management
- [ ] Multiple API credentials (per user role)
- [ ] API key rotation
- [ ] Access control per feature

---

## 📋 สรุปลำดับความสำคัญ

### 🔥 ควรทำก่อน (Phase 1)
1. **Dashboard/Overview** - ให้ผู้ใช้เห็นภาพรวม
2. **Hotel Search Preview** - ทดสอบ API ก่อนใช้จริง
3. **Cache Management** - จัดการ cache
4. **API Logs Viewer** - Debug และ monitor

### ⚡ ควรทำต่อ (Phase 2)
5. City Management (ถ้ามี Content API)
6. Statistics & Analytics
7. Bulk Operations

### 💎 ทำเพิ่มเติม (Phase 3)
8. Content API Integration
9. Advanced Settings
10. User Management

---

## 🎯 Implementation Plan

### Step 1: Dashboard Page
- สร้าง `admin/views/dashboard.php`
- เพิ่ม menu item "Dashboard"
- แสดง API status, quick stats, quick actions

### Step 2: Hotel Search Preview
- สร้าง `admin/views/hotel-search.php`
- สร้าง `admin/js/hotel-search.js`
- ใช้ API class ที่มีอยู่แล้ว
- แสดงผลลัพธ์ใน admin

### Step 3: Cache Management
- สร้าง `admin/views/cache-management.php`
- เพิ่ม methods ใน `Agoda_Cache` class
- แสดง cache statistics

### Step 4: API Logs Viewer
- สร้าง `admin/views/api-logs.php`
- อ่าน logs จาก `Agoda_Logger`
- แสดง logs แบบ table with filters

---

## 📝 Notes

- ฟีเจอร์ทั้งหมดควรมี Security checks (nonce, capability)
- ควรมี Loading states และ Error handling
- UI ควรสอดคล้องกับ WordPress admin style
- ควรมี Help text และ Tooltips

---

**พร้อมเริ่มพัฒนาเมื่อไหร่ก็ได้! 🚀**
