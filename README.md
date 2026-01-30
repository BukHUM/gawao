# Gawao - Trend Today WordPress Theme

เว็บไซต์ข่าว Trend Today ที่พัฒนาด้วย WordPress พร้อม Theme แบบ Custom ที่ทันสมัยและรองรับการแสดงผลหลายภาษา (ไทย/อังกฤษ)

**ทีมผู้พัฒนา:** [ต้นกล้าไอที (Tonkla IT)](https://tonkla.co)  
**เว็บตัวอย่าง:** [กาเหว่า (Gawao)](https://gawao.com) — เว็บข่าวทันทุกเทรนด์

## 📋 ภาพรวมโปรเจกต์

**Gawao** เป็นโปรเจกต์ WordPress สำหรับเว็บไซต์ข่าว Trend Today ที่ออกแบบมาเพื่อ:
- แสดงข่าวสารและบทความที่ทันสมัย
- รองรับการแสดงผลหลายภาษา (Theme translation-ready: ไทย/อังกฤษ ผ่าน `languages/en_US.mo`)
- มีระบบ Custom Post Types: Video News, Gallery, Featured Story
- Theme Settings แบบครบ (Logo, Pagination, Footer, Social Sharing, Search, TOC, Widgets)
- UI/UX ที่ทันสมัยและ Responsive

## 🚀 คุณสมบัติหลัก

### Theme Features
- ✅ **Theme Settings** (เมนู Trend Today) — Logo, ประเภท Pagination, จำนวนคอลัมน์ข่าวล่าสุด, หน้า Login แบบธีม, Footer (Newsletter/Tags, คอลัมน์ Footer1–4, Copyright), Social Sharing, Search, Table of Contents, Widgets
- ✅ Responsive Design (รองรับ Mobile, Tablet, Desktop)
- ✅ Custom Post Types: **Video News**, **Gallery**, **Featured Story**
- ✅ Custom Widgets: Popular Posts, Recent Posts, Trending Tags, Related Posts, Newsletter, Social Follow, Search, Archive, Categories, Custom HTML/Ad
- ✅ AJAX: Load More, Category Filtering, Live Search, Search Suggestions
- ✅ SEO Optimized · Custom Fields (Breaking News, Excerpt, Reading Time, Author, SEO Meta)
- ✅ **Translation ready** (ไทย/อังกฤษ) — ตั้ง Site Language เป็น English ได้
- ✅ Hero Section · Category Filtering · Trending Tags/Hashtags
- ✅ Login Customizer (ใช้สไตล์ธีมที่ wp-login) · Menu locations: Primary, Footer, Footer Copyright

### Technical Features
- WordPress 6.0+ (ทดสอบถึง 6.9)
- PHP 7.4+
- Modern CSS · JavaScript ES6+
- WordPress Coding Standards

## 📁 โครงสร้างโปรเจกต์

```
gawao/
├── .gitignore
├── README.md
├── mockup/                      # Mockup HTML
│   ├── trendtoday_article.html
│   ├── trendtoday_category.html
│   ├── trendtoday_landing.html
│   ├── trendtoday_news.html
│   └── trendtoday_search.html
├── package-lock.json
├── wp-content/
│   └── themes/
│       └── trendtoday/          # Theme หลัก (Trend Today)
│           ├── assets/          # CSS, JS
│           ├── inc/             # PHP Includes
│           ├── template-parts/  # ส่วนประกอบเทมเพลต
│           └── widgets/         # Custom Widgets
├── wp-admin/
├── wp-includes/
└── (ไฟล์หลัก WordPress: index.php, wp-config-sample.php ฯลฯ)
```

**หมายเหตุ:** `wp-config.php` และ `.htaccess` อยู่ใน `.gitignore` — หลัง clone ให้คัดลอกจาก `wp-config-sample.php` แล้วแก้ค่าตาม environment ของคุณ

## 🛠️ การติดตั้ง

### ความต้องการของระบบ
- WordPress 6.0 หรือสูงกว่า
- PHP 7.4 หรือสูงกว่า
- MySQL 5.6 หรือสูงกว่า
- Apache/Nginx Web Server

### ขั้นตอนการติดตั้ง

1. **Clone Repository**
   ```bash
   git clone https://github.com/BukHUM/gawao.git
   cd gawao
   ```

2. **ตั้งค่า Database**
   - สร้าง Database ใหม่ใน MySQL
   - คัดลอก `wp-config-sample.php` เป็น `wp-config.php` แล้วแก้ค่าตามข้อมูล Database ของคุณ:
     ```php
     define( 'DB_NAME', 'your_database_name' );
     define( 'DB_USER', 'your_username' );
     define( 'DB_PASSWORD', 'your_password' );
     define( 'DB_HOST', 'localhost' );
     ```

3. **ติดตั้ง WordPress**
   - เปิดเบราว์เซอร์ไปที่ `http://localhost/gawao`
   - ทำตามขั้นตอนการติดตั้ง WordPress

4. **Activate Theme**
   - ไปที่ `Appearance > Themes`
   - เลือก Theme "Trend Today" และ Activate

## 📦 Plugins ที่แนะนำ

### Optional
- **Polylang** — ถ้าต้องการหลายภาษามากกว่า 2 ภาษา (Theme มีภาษาไทย/อังกฤษในตัวแล้ว)

### Recommended Plugins
- **Yoast SEO** หรือ **Rank Math** - สำหรับ SEO
- **WP Super Cache** หรือ **W3 Total Cache** - สำหรับ Caching
- **Wordfence** - สำหรับ Security
- **UpdraftPlus** - สำหรับ Backup

## 🎨 Theme Structure (trendtoday)

### Template Files
- `home.php` — หน้าข่าวล่าสุด (หน้าแรก)
- `index.php` — Fallback
- `single.php` — หน้าบทความเดี่ยว
- `page.php` — หน้าเพจ
- `archive.php` — หน้าหมวดหมู่
- `search.php` — หน้าค้นหา
- `404.php` — หน้าไม่พบ
- `header.php` / `footer.php` — Header & Footer
- `comments.php` — ความคิดเห็น

### Custom Post Types
- `single-gallery.php` / `archive-gallery.php` — Gallery
- `single-video_news.php` / `archive-video_news.php` — Video News

### Template Parts
- `navbar.php` · `hero-section.php` · `news-card.php` · `sidebar.php` · `sidebar-single.php`
- `trending-tags.php` · `category-filters.php` · `pagination.php`
- `breadcrumb.php` · `post-meta.php` · `content-none.php`
- `search-modal.php` · `social-share.php` · `social-share-floating.php` · `table-of-contents.php`

### Includes (inc/)
- `theme-setup.php` · `enqueue-scripts.php` · `custom-post-types.php` (Theme Settings + CPT) · `custom-fields.php`
- `ajax-handlers.php` · `theme-helpers.php` · `navigation-functions.php` · `dynamic-content.php`
- `login-customizer.php` · `security.php` · `search-functions.php`
- `menu-walker.php` · `menu-icons.php` · `menu-active-states.php`
- `category-fields.php` · `cpt-helpers.php` · `register-widgets.php` · `widget-helpers.php` · `widget-styling.php`

### Assets
- **CSS:** `assets/css/` — custom.css, login.css, admin.css, print.css
- **JS:** `assets/js/` — main.js, custom.js, logo-uploader.js

### Widgets
- `class-popular-posts-widget.php` · `class-recent-posts-widget.php` · `class-related-posts-widget.php`
- `class-trending-tags-widget.php` · `class-newsletter-widget.php` · `class-social-follow-widget.php`
- `class-search-widget.php` · `class-archive-widget.php` · `class-categories-widget.php` · `class-custom-html-widget.php` · `class-most-commented-widget.php`

## 🎯 Custom Post Types

### Video News
- Post Type: `video_news`
- Template: `single-video_news.php`, `archive-video_news.php`
- ใช้สำหรับแสดงข่าววิดีโอ

### Gallery
- Post Type: `gallery`
- Template: `single-gallery.php`, `archive-gallery.php`
- ใช้สำหรับแสดงรูปภาพ Gallery

### Featured Story
- Post Type: `featured_story`
- ใช้สำหรับข่าวเด่น/ฟีเจอร์

## 🎨 Customization

### Theme Settings (Trend Today เมนูในแอดมิน)
- **General** — Logo, Pagination (หมายเลขหน้า / โหลดข่าวเพิ่มเติม), จำนวนคอลัมน์ข่าวล่าสุด, หน้า Login ใช้สไตล์ธีม
- **Footer** — เปิด/ปิด Newsletter, Tags, ตั้งค่า Footer1–4 (Sidebar/Menu/Social), Copyright text และเมนู
- **Social Sharing** — เปิด/ปิดปุ่มแชร์, ตำแหน่ง, สไตล์ปุ่ม, แพลตฟอร์ม
- **Search** — Live Search, Suggestions, Post types/fields
- **Table of Contents** — สไตล์, ตำแหน่ง, Mobile
- **Widgets** — เลือก Widget ที่แสดงใน Appearance → Widgets

### Custom Fields
Theme ใช้ Custom Fields สำหรับ:
- Breaking News Flag
- Article Excerpt
- Reading Time
- Author Info
- Category Color

## 📱 Responsive Breakpoints

- **Mobile**: < 640px
- **Tablet**: 640px - 1024px
- **Desktop**: > 1024px

## 🔍 SEO Features

- Proper Heading Structure (H1, H2, H3)
- Meta Tags Support
- Schema Markup Ready
- Open Graph Tags
- Twitter Card Tags
- Clean URLs (Permalinks)

## 🚀 Performance Optimization

- Image Lazy Loading
- Optimized Database Queries
- Cache-friendly Structure
- Minified CSS/JS (Production)
- Optimized Image Sizes

## 🛡️ Security

- Sanitized User Inputs
- Escaped Outputs
- Nonces for Forms
- Data Validation
- WordPress Security Best Practices

## 📝 การพัฒนา

### Coding Standards
- WordPress Coding Standards
- PHP 7.4+ Syntax
- Modern JavaScript (ES6+)
- CSS Best Practices

### Development Workflow
1. Create Feature Branch
2. Develop & Test
3. Commit Changes
4. Push to Repository
5. Create Pull Request

## 📄 License

GNU General Public License v2 or later

## 👥 Credits (ตรงกับ Theme Settings → Dashboard)

- **ทีมผู้พัฒนา:** [ต้นกล้าไอที (Tonkla IT)](https://tonkla.co)
- **เว็บตัวอย่าง:** [กาเหว่า (Gawao)](https://gawao.com)

ธีมนี้ใช้งานได้ฟรี ตามเงื่อนไขสัญญาอนุญาตของ WordPress (GPL)

## 📞 Support

สำหรับคำถามหรือปัญหาต่างๆ กรุณาเปิด Issue ใน [GitHub Repository](https://github.com/BukHUM/gawao) หรือติดต่อ [ต้นกล้าไอที](https://tonkla.co)

## 🔗 Links

- **ทีมผู้พัฒนา:** [https://tonkla.co](https://tonkla.co)
- **เว็บตัวอย่าง:** [https://gawao.com](https://gawao.com)
- **Repository:** [https://github.com/BukHUM/gawao](https://github.com/BukHUM/gawao)
- **Theme:** Trend Today · **Version:** 1.0.0

---

**สร้างเมื่อ**: 2025 · **อัปเดต**: 2026  
**Theme Version**: 1.0.0 · **สถานะ**: Production Ready  

© [ต้นกล้าไอที](https://tonkla.co) · [กาเหว่า](https://gawao.com)
