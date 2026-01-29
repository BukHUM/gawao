# Theme Unit Test Checklist – Trend Today

อ้างอิง: [WordPress Theme Unit Test (Codex)](https://codex.wordpress.org/Theme_Unit_Test)

---

## สรุปภาพรวม

| หัวข้อ | สถานะ | หมายเหตุ |
|--------|--------|----------|
| Template Hierarchy | ✅ ผ่าน | มี index.php, home.php, 404.php, search.php, single.php, page.php, archive.php, comments.php |
| Static Front Page | ✅ ผ่าน | มี home.php รองรับ Blog Posts Index |
| 404 Page | ✅ ผ่าน | มีข้อความช่วยเหลือ + ช่องค้นหา + ลิงก์กลับ + รายการโพสต์ |
| Search Results | ✅ ผ่าน | แสดงคำค้น + ผลลัพธ์ + pagination |
| Blog Posts Index | ✅ ผ่าน | รองรับ sticky, read more, post formats, content-none |
| Single Post | ✅ ผ่าน | แก้แล้ว: โพสต์ไม่มีชื่อใช้ permalink เป็นวันที่ |
| Pages | ✅ ผ่าน | ไม่แสดง categories/tags ในหน้า |
| Comments | ✅ ผ่าน | แก้แล้ว: แสดง "Comments are closed" เมื่อปิดความเห็นแม้ไม่มีความเห็น |
| Menus | ✅ ผ่าน | register_nav_menus (primary, footer) |
| Widgets | ✅ ผ่าน | แก้แล้ว: ซ่อนบล็อก Popular เมื่อมี widget ใน sidebar |
| Post Formats | ✅ ผ่าน | แก้แล้ว: เพิ่ม 'audio' ใน post-formats |
| Screenshot | ✅ ผ่าน | มี screenshot.png |
| Anchor/Credit Links | ✅ ผ่าน | Footer ใช้ชื่อไซต์ ไม่มีลิงก์ SEO ไม่เหมาะสม |

---

## รายละเอียดตาม Codex

### 1. Template Hierarchy Index Pages
- ✅ โพสต์เรียงและจำนวนถูก (ใช้การตั้งค่า Reading)
- ✅ มี pagination (template-parts/pagination.php, the_posts_pagination)
- ✅ ใช้ content-none เมื่อไม่มีโพสต์

### 2. Static Front Page
- ✅ มี home.php สำหรับ Blog Posts Index
- ไม่มี front-page.php (ไม่บังคับ)

### 3. 404 Page
- ✅ แสดงข้อความชัดเจน
- ✅ มีฟอร์มค้นหา
- ✅ ลิงก์กลับหน้าแรก / กลับก่อนหน้า
- ✅ แสดงรายการโพสต์ (ข่าวล่าสุด)

### 4. Search Results Page
- ✅ แสดงคำค้น (get_search_query())
- ✅ แสดงผลการค้นหาและจำนวน
- ✅ มี pagination / Load More

### 5. Blog Posts Index
- ✅ Sticky: ใช้ loop ปกติ (WordPress จัดการ)
- ✅ Read More: ใช้ the_excerpt / get_the_excerpt ใน news-card
- ✅ Post formats: add_theme_support('post-formats', …) มี aside, gallery, quote, image, video, **audio**
- ✅ Content none: template-parts/content-none.php + get_search_form()
- ✅ **(no title)**: แก้แล้ว – แสดงวันที่ในลิงก์ permalink (news-card.php)

### 6. Single Post
- ✅ wp_link_pages สำหรับหลายหน้า
- ✅ Categories และ Tags
- ✅ Comments: comments_template(), รองรับ threaded (callback trendtoday_comment)
- ✅ Password protected: comments.php return เมื่อ post_password_required()
- ✅ **(no title)**: แก้แล้ว – แสดงวันที่เป็นลิงก์ permalink (single.php)

### 7. Pages
- ✅ ไม่แสดง categories/tags
- ✅ แสดงความเห็นเมื่อเปิด และซ่อนฟอร์มเมื่อปิด (comments_template)

### 8. Comments
- ✅ รองรับ threaded (comment_reply_link, max_depth จาก settings)
- ✅ แสดง "Comments are closed." เมื่อปิดความเห็นและมีความเห็น (single เท่านั้น)
- ✅ **Comments Disabled**: แก้แล้ว – แสดง "Comments are closed." เฉพาะ single post; หน้า Page ไม่แสดง (Codex 8.2)

### 9. Widgets
- ✅ ลงทะเบียน sidebar-1, after-content, footer-1–4
- ✅ **Default content**: แก้แล้ว – แสดงบล็อก Popular เฉพาะเมื่อไม่มี widget ใน sidebar-1 (sidebar.php)

### 10. General
- ✅ $content_width = 1200 (theme-setup.php)
- ✅ add_theme_support: title-tag, post-thumbnails, html5, custom-logo, automatic-feed-links, responsive-embeds, align-wide, editor-styles, selective-refresh, custom-background, custom-header
- ✅ Screenshot มีอยู่
- ✅ Footer ไม่มี credit/SEO links ไม่เหมาะสม

---

## การแก้ไขที่ดำเนินการแล้ว (2025-01-29)

1. **โพสต์ไม่มีชื่อ (no title)**  
   - single.php: ถ้า get_the_title() ว่าง แสดงลิงก์ permalink เป็นวันที่  
   - template-parts/news-card.php: ถ้าไม่มีชื่อ แสดงวันที่ในลิงก์; aria-label และ alt ใช้ fallback วันที่  

2. **Comments Disabled**  
   - comments.php: แสดง "Comments are closed." เฉพาะ **single post** (is_single()); หน้า Page ไม่แสดง (Codex 8.2)  

3. **Post format: audio**  
   - inc/theme-setup.php: เพิ่ม 'audio' ใน add_theme_support('post-formats')  

4. **Sidebar default content**  
   - template-parts/sidebar.php: แสดงบล็อก "Popular" เฉพาะเมื่อ !is_active_sidebar('sidebar-1')  

---

## ตรวจสอบหลัง Import (รอบ 2)

หลังนำเข้า [themeunittestdata.wordpress.xml](https://raw.githubusercontent.com/WPTT/theme-unit-test/master/themeunittestdata.wordpress.xml) และตั้งค่า WordPress ตาม Codex แล้ว ให้ตรวจตามรายการใน **docs/THEME-UNIT-TEST-CHECKLIST.md** (ส่วน "ตรวจสอบหลัง Import")

---

## สิ่งที่อาจพิจารณาเพิ่ม (ไม่บังคับ)

- **Page – date**: ตาม Codex หน้า Page ไม่ควรแสดง "Post date/time stamp" อาจพิจารณาซ่อนวันที่ใน header ของ page.php  

---

*ตรวจสอบเมื่อ: 2025-01-29 | อัปเดตรอบ 2 หลัง Import*
