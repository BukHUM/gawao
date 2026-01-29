# Gawao - Trend Today WordPress Theme

เว็บไซต์ข่าว Trend Today ที่พัฒนาด้วย WordPress พร้อม Theme แบบ Custom ที่ทันสมัยและรองรับการแสดงผลหลายภาษา

**พัฒนาโดย:** [กาเหว่าดอทคอม เว็บข่าวทันทุกเทรนด์](https://gawao.com) · [https://gawao.com](https://gawao.com)

## 📋 ภาพรวมโปรเจกต์

**Gawao** เป็นโปรเจกต์ WordPress สำหรับเว็บไซต์ข่าว Trend Today ที่ออกแบบมาเพื่อ:
- แสดงข่าวสารและบทความที่ทันสมัย
- รองรับการแสดงผลหลายภาษา (Multilingual)
- มีระบบ Custom Post Types สำหรับ Gallery และ Video News
- UI/UX ที่ทันสมัยและ Responsive

## 🚀 คุณสมบัติหลัก

### Theme Features
- ✅ Responsive Design (รองรับ Mobile, Tablet, Desktop)
- ✅ Custom Post Types: Gallery, Video News
- ✅ Custom Widgets: Popular Posts, Recent Posts, Trending Tags, Newsletter
- ✅ AJAX Functionality: Load More, Category Filtering
- ✅ SEO Optimized
- ✅ Multilingual Support (Polylang)
- ✅ Custom Fields สำหรับบทความ
- ✅ Hero Section สำหรับข่าวด่วน
- ✅ Category Filtering
- ✅ Trending Tags/Hashtags

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

### Required Plugins
- **Polylang** - สำหรับระบบหลายภาษา

### Recommended Plugins
- **Yoast SEO** หรือ **Rank Math** - สำหรับ SEO
- **WP Super Cache** หรือ **W3 Total Cache** - สำหรับ Caching
- **Wordfence** - สำหรับ Security
- **UpdraftPlus** - สำหรับ Backup

## 🎨 Theme Structure (trendtoday)

### Template Files
- `front-page.php` — หน้าแรก (Landing Page)
- `home.php` — หน้าข่าวล่าสุด
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
- `theme-setup.php` · `enqueue-scripts.php` · `custom-post-types.php` · `custom-fields.php`
- `ajax-handlers.php` · `theme-helpers.php` · `navigation-functions.php` · `dynamic-content.php`
- `login-customizer.php` · `security.php` · `image-optimization.php`
- `menu-walker.php` · `menu-icons.php` · `menu-active-states.php` · `search-functions.php`
- `category-fields.php` · `cpt-helpers.php` · `register-widgets.php` · `widget-helpers.php` · `widget-styling.php`

### Assets
- **CSS:** `assets/css/` — custom.css, login.css, admin.css, print.css
- **JS:** `assets/js/` — main.js, custom.js, logo-uploader.js

### Widgets
- `class-popular-posts-widget.php` · `class-recent-posts-widget.php`
- `class-trending-tags-widget.php` · `class-newsletter-widget.php`

## 🎯 Custom Post Types

### Gallery
- Post Type: `gallery`
- Template: `single-gallery.php`, `archive-gallery.php`
- ใช้สำหรับแสดงรูปภาพ Gallery

### Video News
- Post Type: `video_news`
- Template: `single-video_news.php`, `archive-video_news.php`
- ใช้สำหรับแสดงข่าววิดีโอ

## 🎨 Customization

### Theme Customizer
Theme รองรับการปรับแต่งผ่าน WordPress Customizer:
- Logo · Color Scheme · Social Media Links
- **Login Customizer** — ปรับแต่งหน้าล็อกอิน (สี, ลogo, CSS)

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

## 👥 Contributors

- [Gawao](https://gawao.com) — ผู้พัฒนา (Developer)

## 📞 Support

สำหรับคำถามหรือปัญหาต่างๆ กรุณาเปิด Issue ใน [GitHub Repository](https://github.com/BukHUM/gawao) หรือติดต่อผู้พัฒนาผ่าน [Gawao](https://gawao.com)

## 🔗 Links

- **ผู้พัฒนา / Developer**: [https://gawao.com](https://gawao.com)
- **Repository**: [https://github.com/BukHUM/gawao](https://github.com/BukHUM/gawao)
- **Theme Name**: Trend Today
- **Version**: 1.0.0

---

**สร้างเมื่อ**: 2025 · **อัปเดต**: 2026  
**Theme Version**: 1.0.0  
**สถานะ**: Production Ready  

© [Gawao](https://gawao.com)
