# Agoda Plugin - Quick Checklist

## ✅ ขั้นตอนการทำงาน (Step-by-Step)

### 📖 Phase 1: เตรียมความพร้อม
- [ ] อ่านเอกสาร Agoda API ให้เข้าใจ
  - [ ] Affiliate Lite API V2.0 (PDF)
  - [ ] Content API (optional, for future)
- [ ] ขอ Credentials จาก Agoda:
  - [ ] Site ID และ API Key (สำหรับ Affiliate Lite API)
  - [ ] CID (Customer ID) จาก Affiliate Dashboard
  - [ ] Token และ Site ID (สำหรับ Content API - optional)
- [ ] ขอ Sandbox access (ถ้ามี)
- [ ] กำหนดฟีเจอร์ที่ต้องการ

### 🏗️ Phase 2: สร้างโครงสร้าง
- [ ] สร้างโฟลเดอร์ plugin: `wp-content/plugins/agoda-booking/`
- [ ] สร้างไฟล์หลัก: `agoda-booking.php`
- [ ] สร้างโครงสร้างโฟลเดอร์ (includes/, admin/, public/)
- [ ] สร้างไฟล์ readme.txt

### 💻 Phase 3: พัฒนา Core
- [ ] สร้าง Class: `Agoda_API` (API integration)
- [ ] สร้าง Class: `Agoda_Admin` (Settings page)
- [ ] สร้าง Class: `Agoda_Frontend` (Search form & results)
- [ ] สร้าง Class: `Agoda_Validator` (Input validation)
- [ ] สร้าง Class: `Agoda_Cache` (Caching)

### 🔌 Phase 4: API Integration
- [ ] Implement City Search
- [ ] Implement Hotel List Search
- [ ] Handle API responses
- [ ] Handle API errors
- [ ] Test API connection

### ⚙️ Phase 5: Admin Settings
- [ ] สร้าง Settings page
- [ ] Fields: Site ID, API Key, Language, Currency
- [ ] Validate และ save settings
- [ ] Test connection button
- [ ] Help text และ documentation

### 🎨 Phase 6: Frontend
- [ ] สร้าง Search Form
  - [ ] Date pickers (check-in/out)
  - [ ] City selection
  - [ ] Adults/Children input
  - [ ] Search button
- [ ] สร้าง Results Display
  - [ ] Hotel cards
  - [ ] Hotel information
  - [ ] Book button (redirect)
- [ ] AJAX functionality
- [ ] Loading states
- [ ] Error messages

### 🛡️ Phase 7: Security & Validation
- [ ] Sanitize all inputs
- [ ] Escape all outputs
- [ ] Nonce verification
- [ ] Capability checks
- [ ] Date validation
- [ ] Occupancy validation

### 🧪 Phase 8: Testing
- [ ] Test City Search (success)
- [ ] Test Hotel List Search (success)
- [ ] Test error scenarios
- [ ] Test edge cases
- [ ] Cross-browser testing
- [ ] Responsive testing

### 📚 Phase 9: Documentation
- [ ] Code comments (PHPDoc)
- [ ] README file
- [ ] Admin help text
- [ ] User guide

### 🚀 Phase 10: Launch
- [ ] Code review
- [ ] Security audit
- [ ] Performance optimization
- [ ] Final testing
- [ ] Deploy
- [ ] Monitor

---

## 🎯 Priority Order (ทำตามลำดับ)

### 🔴 Critical (ทำก่อน)
1. **API Integration** - ต้องทำงานได้ก่อน
2. **Admin Settings** - ต้องตั้งค่าได้
3. **Basic Search** - ต้องค้นหาได้
4. **Error Handling** - ต้องจัดการ error ได้

### 🟡 Important (ทำต่อ)
5. **Frontend UI** - ให้ใช้งานได้ดี
6. **Input Validation** - ป้องกัน errors
7. **Caching** - เพิ่มประสิทธิภาพ
8. **Security** - ป้องกัน vulnerabilities

### 🟢 Enhancement (ทำเพิ่ม)
9. **Advanced Features** - ฟีเจอร์เพิ่มเติม
10. **Performance** - ปรับปรุงประสิทธิภาพ
11. **Documentation** - เอกสารครบถ้วน

---

## ⚠️ จุดที่ต้องระวัง

### 1. API Integration
- ✅ ตรวจสอบ headers ให้ถูกต้อง
- ✅ ตรวจสอบ request format
- ✅ ตรวจสอบ response parsing
- ✅ Handle errors ทุกกรณี

### 2. Date Validation
- ✅ Check-out ต้อง > Check-in
- ✅ ไม่อนุญาต past dates
- ✅ Format: YYYY-MM-DD

### 3. Security
- ✅ Never expose API credentials
- ✅ Sanitize inputs
- ✅ Escape outputs
- ✅ Verify nonces

### 4. Error Handling
- ✅ User-friendly error messages
- ✅ Log errors (for debugging)
- ✅ Handle network errors
- ✅ Handle API timeouts

---

## 📋 Quick Reference

### API Endpoint
```
http://affiliateapi7643.agoda.com/affiliateservice/lt_v1
```

### Required Headers
```
Accept-Encoding: gzip,deflate
Authorization: {siteId}:{apiKey}
Content-Type: application/json
```

### Required Parameters
- `cityId` หรือ `hotelId` (array)
- `checkInDate` (YYYY-MM-DD)
- `checkOutDate` (YYYY-MM-DD)

### Default Values
- Language: `en-us`
- Currency: `USD`
- Max Results: `10`
- Sort By: `Recommended`

---

## 🔗 เอกสารที่เกี่ยวข้อง

### Internal Documentation
1. **แผนการทำงานหลัก**: `plan/AGODA_PLUGIN_PLAN.md`
2. **Technical Specifications**: `plan/AGODA_PLUGIN_TECHNICAL_SPEC.md`
3. **API Information**: `plan/agoda_api.md`
4. **Quick Checklist**: `plan/AGODA_PLUGIN_CHECKLIST.md`

### Agoda Documentation
1. **Affiliate Lite API V2.0**: `plan/Affiliate_Lite_API_V2.0.pdf`
2. **Content API**: https://developer.agoda.com/demand/docs/content-api
3. **Agoda Developer Portal**: https://developer.agoda.com/demand/docs/getting-started
4. **Best Practices**: https://developer.agoda.com/demand/docs/best-practices-certification-process
5. **Agoda Affiliate Dashboard**: https://partners.agoda.com

---

## 💡 Tips

1. **เริ่มจาก API Integration ก่อน** - Test ให้ทำงานได้ก่อนทำส่วนอื่น
2. **ใช้ Sandbox** - Test ใน Sandbox ก่อนใช้ Production
3. **Test ทุก Scenario** - Success, Error, Edge cases
4. **Follow WordPress Standards** - Coding standards, security best practices
5. **Document as you go** - เขียน documentation ไปพร้อมกับ code
6. **เข้าใจความแตกต่างของ API**:
   - Affiliate Lite API: ใช้ Authorization header (POST)
   - Content API: ใช้ token + site_id ใน query (GET)
7. **Site ID vs CID**:
   - CID: หาได้จาก Affiliate Dashboard (สำหรับ affiliate tracking)
   - Site ID: หาได้จาก Developer Portal (สำหรับ API authentication)
   - อาจเป็นตัวเดียวกันหรือต่างกัน (ตรวจสอบกับ Account Manager)

---

**เริ่มต้นได้เลย! 🚀**
