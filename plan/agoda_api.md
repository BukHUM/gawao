# Agoda API - Resources & Documentation

## 📚 เอกสารที่เกี่ยวข้อง

### เอกสารหลัก (Demand APIs - สำหรับ Affiliate Partners)
- **Affiliate Lite API V2.0**: `plan/Affiliate_Lite_API_V2.0.pdf`
- **Content API**: https://developer.agoda.com/demand/docs/content-api
- **Agoda Developer Portal**: https://developer.agoda.com/demand/docs/getting-started
- **Best Practices**: https://developer.agoda.com/demand/docs/best-practices-certification-process

### เอกสารที่ไม่เกี่ยวข้อง (Supply APIs - สำหรับโรงแรม/Channel Manager)
- **Supply API Authentication (OAuth 2.0)**: https://developer.agoda.com/supply/docs/authentication-2025
  - ⚠️ **ไม่เกี่ยวข้องกับ plugin นี้** - ใช้สำหรับ Supply APIs (YCS API, OTA API, etc.)
  - Plugin นี้ใช้ Demand APIs (Affiliate Lite API, Content API)

### แผนการทำงาน
- **แผนการทำงานหลัก**: `plan/AGODA_PLUGIN_PLAN.md`
- **Technical Specifications**: `plan/AGODA_PLUGIN_TECHNICAL_SPEC.md`
- **Quick Checklist**: `plan/AGODA_PLUGIN_CHECKLIST.md`

## 🔑 API Information

### ⚠️ สำคัญ: มี API หลายตัวที่แตกต่างกัน - Supply vs Demand

**Agoda แบ่ง API เป็น 2 ส่วนหลัก:**

1. **Supply APIs** (Direct Supply) - สำหรับโรงแรม/Channel Manager
   - YCS API, OTA API, Content Push API, Promotion API
   - Authentication: OAuth 2.0 (Token-Based) - เริ่มใช้ 2025
   - Documentation: https://developer.agoda.com/supply/docs/authentication-2025
   - **ไม่เกี่ยวข้องกับ plugin นี้**

2. **Demand APIs** - สำหรับ Affiliate Partners (ใช้ใน plugin นี้)
   - Affiliate Lite API, Content API, Search API, Book API
   - Authentication: ตามที่ระบุด้านล่าง
   - Documentation: https://developer.agoda.com/demand/docs/getting-started

### ⚠️ สำคัญ: มี API หลายตัวที่แตกต่างกัน

#### 1. Affiliate Lite API V2.0 (Search API) - ใช้ใน Plugin นี้
- **Endpoint**: `http://affiliateapi7643.agoda.com/affiliateservice/lt_v1`
- **Method**: HTTP POST
- **Authentication**: 
  - **Header**: `Authorization: {siteId}:{apiKey}`
  - **Required Headers**:
    - `Accept-Encoding: gzip,deflate`
    - `Content-Type: application/json`
- **Purpose**: ค้นหาโรงแรม (Search hotels)
- **API Types**:
  - City Search (ค้นหาจาก city ID)
  - Hotel List Search (ค้นหาจาก hotel ID array)

#### 2. Content API (Data Feed) - สำหรับดึงข้อมูล Hotel Content
- **Endpoint**: `https://[baseURL]/datafeeds/feed/getfeed`
- **Method**: HTTP GET
- **Authentication**: 
  - **Query Parameters**: `token` + `site_id`
  - **Example**: `?feed_id=1&token={{token}}&site_id={{siteid}}`
- **Purpose**: ดึงข้อมูล hotel content (Data Feed)
- **Feed Types**:
  - Feed 1: Continents
  - Feed 2: Countries
  - Feed 3: Cities
  - Feed 4: Areas
  - Feed 5: Hotels
  - Feed 19: Hotel Information (full details)
  - Feed 32: Hotel Changes (daily updates)
- **Documentation**: https://developer.agoda.com/demand/docs/content-api

## 🔐 Credentials & Authentication

### Site ID vs CID (Customer ID)

⚠️ **สำคัญ**: Site ID ใน Affiliate Dashboard ≠ Site ID สำหรับ API authentication!

- **CID (Customer ID)**:
  - หาได้จาก: Agoda Affiliate Dashboard → Profile → Manage Your Sites
  - ใช้สำหรับ: Affiliate tracking ใน landing URLs
  - ตัวอย่าง: 1425703 (จาก Dashboard)

- **Site ID (สำหรับ Affiliate Lite API)**:
  - หาได้จาก: Agoda Developer Portal หรือ Account Manager
  - ใช้สำหรับ: API authentication (Authorization header)
  - อาจเป็นตัวเดียวกับ CID หรือต่างกัน (ต้องตรวจสอบกับ Account Manager)

- **API Key (สำหรับ Affiliate Lite API)**:
  - หาได้จาก: Agoda Developer Portal หรือ Account Manager
  - ใช้สำหรับ: API authentication (Authorization header)

- **Token (สำหรับ Content API)**:
  - หาได้จาก: Agoda Developer Portal หรือ Account Manager
  - ใช้สำหรับ: Content API authentication (query parameter)

## 📋 API Types (Affiliate Lite API)

### 1. City Search
- Search hotels by city ID
- Supports filters (price, rating, discount)
- Supports sorting options
- Returns up to 30 results (configurable)

### 2. Hotel List Search
- Search specific hotels by hotel ID array
- No filters or sorting
- Returns results for specified hotels only

## 🎯 Next Steps

1. อ่านเอกสาร PDF ให้เข้าใจ
2. ขอ Site ID และ API Key จาก Agoda (สำหรับ Affiliate Lite API)
3. ขอ Token และ Site ID จาก Agoda (สำหรับ Content API - ถ้าต้องการ)
4. เริ่มพัฒนาตามแผนใน `AGODA_PLUGIN_PLAN.md`