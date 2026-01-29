# Gawao - Trend Today WordPress Theme

เว็บไซต์ข่าว Trend Today ที่พัฒนาด้วย WordPress พร้อม Theme แบบ Custom ที่ทันสมัยและรองรับการแสดงผลหลายภาษา

**พัฒนาโดย:** [กาเหว่าดอทคอม เว็บข่าวทันทุกเทรนด์](https://gawao.com) · [https://gawao.com](https://gawao.com)

## 📋 ภาพรวมโปรเจกต์

**Gawao** เป็นโปรเจกต์ WordPress สำหรับเว็บไซต์ข่าว Trend Today ที่ออกแบบมาเพื่อ:
- แสดงข่าวสารและบทความที่ทันสมัย
- รองรับการแสดงผลหลายภาษา (Multilingual)
- มีระบบ Custom Post Types สำหรับ Gallery และ Video News
- มี Automation Scripts สำหรับจัดการข้อมูลเริ่มต้น
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
- WordPress 6.0+ Compatible
- PHP 7.4+ Required
- Modern CSS Architecture
- JavaScript ES6+
- WordPress Coding Standards

## 📁 โครงสร้างโปรเจกต์

```
gawao/
├── automate/                    # Automation Scripts
│   ├── seed-posts.php          # สร้างโพสต์ตัวอย่าง
│   ├── setup-categories.php    # สร้างหมวดหมู่
│   └── setup-polylang.php     # ตั้งค่า Polylang
├── mockup/                      # Mockup HTML Files
│   ├── trendtoday_article.html
│   ├── trendtoday_category.html
│   ├── trendtoday_landing.html
│   ├── trendtoday_news.html
│   └── trendtoday_search.html
├── plan/                        # เอกสารแผนการพัฒนา
│   ├── idea.md
│   └── IMPLEMENTATION_PLAN.md
├── wp-content/
│   └── themes/
│       └── trendtoday/          # Custom Theme
│           ├── assets/          # CSS, JS, Images
│           ├── inc/             # PHP Includes
│           ├── template-parts/  # Reusable Components
│           └── widgets/         # Custom Widgets
└── wp-config.php
```

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
   - แก้ไข `wp-config.php` ตามข้อมูล Database ของคุณ:
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

5. **ตั้งค่าเริ่มต้น (Optional)**
   - ไปที่ `http://localhost/gawao/automate/setup-categories.php` (ต้อง Login เป็น Admin)
   - ไปที่ `http://localhost/gawao/automate/setup-polylang.php` (ถ้าใช้ Polylang)
   - ไปที่ `http://localhost/gawao/automate/seed-posts.php` (สร้างโพสต์ตัวอย่าง)

## 📦 Plugins ที่แนะนำ

### Required Plugins
- **Polylang** - สำหรับระบบหลายภาษา
- **Advanced Custom Fields (ACF)** - สำหรับ Custom Fields (ถ้าใช้)

### Recommended Plugins
- **Yoast SEO** หรือ **Rank Math** - สำหรับ SEO
- **WP Super Cache** หรือ **W3 Total Cache** - สำหรับ Caching
- **Wordfence** - สำหรับ Security
- **UpdraftPlus** - สำหรับ Backup

## 🎨 Theme Structure

### Template Files
- `front-page.php` - หน้าแรก (Landing Page)
- `home.php` - หน้าข่าวล่าสุด
- `single.php` - หน้าบทความเดี่ยว
- `archive.php` - หน้าหมวดหมู่
- `search.php` - หน้าค้นหา
- `404.php` - หน้าไม่พบ
- `header.php` - Header Template
- `footer.php` - Footer Template

### Custom Post Types
- `single-gallery.php` - หน้า Gallery เดี่ยว
- `archive-gallery.php` - Archive Gallery
- `single-video_news.php` - หน้า Video News เดี่ยว
- `archive-video_news.php` - Archive Video News

### Template Parts
- `template-parts/navbar.php` - Navigation Bar
- `template-parts/hero-section.php` - Hero Section
- `template-parts/news-card.php` - News Card Component
- `template-parts/sidebar.php` - Sidebar
- `template-parts/trending-tags.php` - Trending Tags
- `template-parts/category-filters.php` - Category Filters
- `template-parts/pagination.php` - Pagination

### Includes
- `inc/theme-setup.php` - Theme Setup & Configuration
- `inc/enqueue-scripts.php` - Scripts & Styles Enqueue
- `inc/custom-post-types.php` - Custom Post Types Registration
- `inc/custom-fields.php` - Custom Fields
- `inc/ajax-handlers.php` - AJAX Handlers
- `inc/theme-helpers.php` - Helper Functions
- `inc/navigation-functions.php` - Navigation Functions
- `inc/dynamic-content.php` - Dynamic Content Functions

### Widgets
- `widgets/class-popular-posts-widget.php` - Popular Posts Widget
- `widgets/class-recent-posts-widget.php` - Recent Posts Widget
- `widgets/class-trending-tags-widget.php` - Trending Tags Widget
- `widgets/class-newsletter-widget.php` - Newsletter Widget

## 🔧 การใช้งาน Automation Scripts

### 1. Setup Categories
สร้างหมวดหมู่เริ่มต้นสำหรับเว็บไซต์:
```
http://localhost/gawao/automate/setup-categories.php
```

### 2. Setup Polylang
ตั้งค่าระบบหลายภาษาด้วย Polylang:
```
http://localhost/gawao/automate/setup-polylang.php
```

### 3. Seed Posts
สร้างโพสต์ตัวอย่างสำหรับทดสอบ:
```
http://localhost/gawao/automate/seed-posts.php
```

**หมายเหตุ:** Scripts เหล่านี้ต้อง Login เป็น Administrator ก่อนใช้งาน

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
- Logo
- Color Scheme
- Social Media Links
- และอื่นๆ

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

**สร้างเมื่อ**: 2024  
**เวอร์ชัน**: 1.0.0  
**สถานะ**: Production Ready  

© [Gawao](https://gawao.com)
