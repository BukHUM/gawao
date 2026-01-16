# แผนการสร้าง WordPress Theme สำหรับ Trend Today

## ภาพรวมโปรเจค
พัฒนาเว็บข่าว Trend Today โดยใช้ WordPress โดยแปลง Mockup HTML ที่มีอยู่ให้เป็น WordPress Theme ที่ใช้งานได้จริง

---

## Phase 1: เตรียมโครงสร้าง Theme

### 1.1 สร้างโฟลเดอร์ Theme
- สร้างโฟลเดอร์ `trendtoday` ใน `wp-content/themes/`
- ตั้งชื่อ theme: **Trend Today**

### 1.2 สร้างไฟล์พื้นฐาน
- `style.css` - Header theme info (Theme Name, Description, Version, Author)
- `index.php` - Fallback template (required by WordPress)
- `functions.php` - Theme setup, enqueue scripts/styles, theme supports
- `screenshot.png` - Theme preview image (1200x900px)

### 1.3 สร้างโครงสร้างโฟลเดอร์
```
trendtoday/
├── assets/
│   ├── css/
│   │   ├── style.css (main stylesheet)
│   │   └── custom.css (custom styles)
│   ├── js/
│   │   ├── main.js (main JavaScript)
│   │   └── custom.js (custom functionality)
│   └── images/
│       └── (theme images, icons)
├── template-parts/
│   ├── navbar.php
│   ├── hero-section.php
│   ├── news-card.php
│   ├── sidebar.php
│   ├── trending-tags.php
│   └── category-filters.php
├── inc/
│   ├── theme-setup.php
│   ├── enqueue-scripts.php
│   ├── custom-post-types.php (ถ้าจำเป็น)
│   └── custom-fields.php
├── widgets/
│   └── (custom widgets)
└── (template files)
```

---

## Phase 2: แปลง Mockup เป็น WordPress Templates

### 2.1 Template Files ที่ต้องสร้าง

| Mockup File | WordPress Template | Description |
|------------|-------------------|-------------|
| `trendtoday_landing.html` | `front-page.php` | หน้าแรก (Landing Page) |
| `trendtoday_news.html` | `home.php` / `index.php` | หน้าข่าวล่าสุด (Blog/News Archive) |
| `trendtoday_article.html` | `single.php` | หน้าบทความเดี่ยว (Single Post) |
| `trendtoday_category.html` | `archive.php` | หน้าหมวดหมู่ (Category Archive) |
| `trendtoday_search.html` | `search.php` | หน้าค้นหา (Search Results) |
| - | `404.php` | หน้าไม่พบ (404 Error) |
| - | `header.php` | Header/Navbar (ใช้ร่วมกันทุกหน้า) |
| - | `footer.php` | Footer (ใช้ร่วมกันทุกหน้า) |
| - | `sidebar.php` | Sidebar (ใช้ร่วมกันทุกหน้า) |

### 2.2 Template Parts (Reusable Components)

#### `template-parts/navbar.php`
- Navigation menu
- Logo
- Search button
- Mobile menu toggle

#### `template-parts/hero-section.php`
- Breaking news hero section
- Featured post display
- Hero image with overlay

#### `template-parts/news-card.php`
- News article card component
- Category badge
- Thumbnail image
- Title, excerpt, meta info

#### `template-parts/sidebar.php`
- Popular posts widget
- Newsletter subscription
- Category list
- Social media links

#### `template-parts/trending-tags.php`
- Trending hashtags/topics
- Horizontal scrollable tags

#### `template-parts/category-filters.php`
- Category filter buttons
- Active state handling

---

## Phase 3: WordPress Integration

### 3.1 Theme Support Setup
```php
// ใน functions.php หรือ inc/theme-setup.php
add_theme_support('post-thumbnails');
add_theme_support('title-tag');
add_theme_support('custom-logo');
add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
add_theme_support('post-formats', array('aside', 'gallery', 'quote', 'image', 'video'));
```

### 3.2 Navigation Menus
- สร้าง menu locations:
  - Primary Menu (Main Navigation)
  - Footer Menu (ถ้าจำเป็น)
- ใช้ `wp_nav_menu()` ใน template

### 3.3 Widget Areas
- สร้าง widget areas:
  - Sidebar (Main Sidebar)
  - Footer Widget 1, 2, 3, 4
- ใช้ `register_sidebar()` และ `dynamic_sidebar()`

### 3.4 Custom Post Types (ถ้าจำเป็น)
- News Articles (ถ้าต้องการแยกจาก default Posts)
- Custom taxonomies สำหรับ News Categories

### 3.5 Custom Fields
**ตัวเลือก:**
- **Advanced Custom Fields (ACF)** - แนะนำ (ง่าย, มี UI)
- **Custom Meta Boxes** - เขียนเอง (ยืดหยุ่นกว่า)

**Fields ที่ต้องมี:**
- Breaking News (checkbox/boolean)
- Article Excerpt (textarea)
- Reading Time (number)
- Author Info (text/select)
- Category Color (color picker)
- Featured Image Alt Text

---

## Phase 4: Assets & Styling

### 4.1 CSS Framework
**ตัวเลือก:**
1. **Tailwind CSS (CDN)** - เหมือน mockup (ง่าย, แต่ต้องพึ่ง CDN)
2. **Tailwind CSS (Build)** - Build เป็น static file (ดีกว่า, ไม่พึ่ง CDN)
3. **WordPress Native + Custom CSS** - ใช้ WordPress classes + custom CSS

**แนะนำ:** ใช้ Tailwind CSS (Build) เพื่อ performance ที่ดีกว่า

### 4.2 Enqueue Scripts & Styles
```php
// ใน functions.php หรือ inc/enqueue-scripts.php
function trendtoday_enqueue_assets() {
    // Google Fonts
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Prompt:...');
    
    // Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/.../font-awesome.min.css');
    
    // Tailwind CSS (หรือ custom CSS)
    wp_enqueue_style('trendtoday-style', get_stylesheet_uri());
    
    // Custom CSS
    wp_enqueue_style('trendtoday-custom', get_template_directory_uri() . '/assets/css/custom.css');
    
    // JavaScript
    wp_enqueue_script('trendtoday-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'trendtoday_enqueue_assets');
```

### 4.3 Responsive Design
- Mobile-first approach
- Breakpoints:
  - Mobile: < 640px
  - Tablet: 640px - 1024px
  - Desktop: > 1024px

### 4.4 Image Sizes
```php
// กำหนด image sizes สำหรับ theme
add_image_size('trendtoday-hero', 1920, 800, true);
add_image_size('trendtoday-card', 600, 400, true);
add_image_size('trendtoday-thumbnail', 300, 200, true);
```

---

## Phase 5: Features & Functionality

### 5.1 Dynamic Content

#### News Loop
- ใช้ `WP_Query()` หรือ `get_posts()` สำหรับ custom queries
- แสดงข่าวล่าสุดในหน้า home
- Pagination สำหรับข่าวเก่า

#### Popular Posts
- สร้าง widget หรือ function สำหรับแสดง popular posts
- ใช้ post views หรือ custom field สำหรับนับจำนวน views

#### Trending Tags/Topics
- ใช้ WordPress Tags
- แสดง tags ที่มี posts มากที่สุด
- หรือใช้ custom taxonomy

#### Category Filtering
- AJAX filtering โดย category
- ใช้ `wp_ajax_` hooks

### 5.2 Custom Widgets

#### Popular Posts Widget
- แสดงรายการข่าวยอดนิยม
- ตั้งค่าจำนวน posts ที่แสดง

#### Newsletter Widget
- Form สำหรับสมัครรับ newsletter
- Integration กับ email service (Mailchimp, etc.)

#### Category List Widget
- แสดงรายการหมวดหมู่
- Custom styling

#### Trending Tags Widget
- แสดง trending tags/hashtags

### 5.3 AJAX Functionality

#### Load More Posts
```php
// AJAX handler สำหรับ load more
add_action('wp_ajax_load_more_posts', 'trendtoday_load_more_posts');
add_action('wp_ajax_nopriv_load_more_posts', 'trendtoday_load_more_posts');
```

#### Category Filtering
```php
// AJAX handler สำหรับ filter by category
add_action('wp_ajax_filter_posts', 'trendtoday_filter_posts');
add_action('wp_ajax_nopriv_filter_posts', 'trendtoday_filter_posts');
```

#### Search Suggestions
- Live search suggestions (optional)

---

## Phase 6: WordPress Standards

### 6.1 WordPress Functions
- ใช้ `wp_nav_menu()` สำหรับ navigation
- ใช้ `WP_Query()` สำหรับ post loops
- ใช้ `get_template_part()` สำหรับ reusable parts
- ใช้ `wp_enqueue_script()` / `wp_enqueue_style()` สำหรับ assets
- ใช้ `add_theme_support()` สำหรับ theme features
- ใช้ `the_post_thumbnail()` สำหรับ featured images
- ใช้ `wp_head()` และ `wp_footer()` hooks

### 6.2 SEO Optimization
- Proper heading structure (H1, H2, H3)
- Meta tags (description, keywords)
- Schema markup (Article, NewsArticle)
- Open Graph tags
- Twitter Card tags
- Clean URLs (permalinks)

### 6.3 Performance Optimization
- Image lazy loading
- Minify CSS/JS (production)
- Cache-friendly structure
- Optimize database queries
- Use `wp_enqueue_script()` with dependencies
- Remove unused scripts/styles

### 6.4 Security
- Sanitize user inputs
- Escape outputs
- Use nonces สำหรับ forms
- Validate data
- Use `wp_kses()` สำหรับ HTML content

---

## Phase 7: Customization Options

### 7.1 Theme Customizer
```php
// ใน functions.php
function trendtoday_customize_register($wp_customize) {
    // Logo
    $wp_customize->add_setting('trendtoday_logo');
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'trendtoday_logo', array(
        'label' => 'Logo',
        'section' => 'title_tagline',
        'settings' => 'trendtoday_logo',
    )));
    
    // Color Scheme
    $wp_customize->add_setting('trendtoday_accent_color', array(
        'default' => '#FF4500',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'trendtoday_accent_color', array(
        'label' => 'Accent Color',
        'section' => 'colors',
    )));
    
    // Social Media Links
    // Newsletter Settings
    // etc.
}
add_action('customize_register', 'trendtoday_customize_register');
```

### 7.2 Admin Options (Optional)
- Custom settings page สำหรับ:
  - Breaking news selector
  - Featured posts manager
  - Trending tags manager
  - Newsletter API settings

---

## Phase 8: Testing & Polish

### 8.1 Testing Checklist
- [ ] ทดสอบทุก template (front-page, home, single, archive, search, 404)
- [ ] Responsive design (mobile, tablet, desktop)
- [ ] Cross-browser compatibility (Chrome, Firefox, Safari, Edge)
- [ ] WordPress compatibility (test กับ WordPress version ล่าสุด)
- [ ] Performance testing (PageSpeed, GTmetrix)
- [ ] SEO testing (Yoast SEO หรือ Rank Math)
- [ ] Accessibility testing (WCAG guidelines)
- [ ] Form submissions (newsletter, search)
- [ ] AJAX functionality (load more, filtering)
- [ ] Widget functionality
- [ ] Menu functionality
- [ ] Image display (various sizes)
- [ ] Pagination
- [ ] Comments (ถ้ามี)

### 8.2 Code Quality
- Follow WordPress Coding Standards
- Proper indentation และ formatting
- Code comments สำหรับ complex functions
- Remove debug code
- Optimize queries
- Validate HTML/CSS

### 8.3 Documentation
- `README.md` - Theme description, installation, features
- Code comments - Inline documentation
- User guide (ถ้าจำเป็น) - สำหรับ end users

---

## Phase 9: Deployment

### 9.1 Pre-deployment
- [ ] Remove unused code
- [ ] Minify CSS/JS
- [ ] Optimize images
- [ ] Test in staging environment
- [ ] Backup database

### 9.2 Deployment Steps
1. Upload theme folder ไปยัง `wp-content/themes/`
2. Activate theme ใน WordPress Admin
3. Configure settings (menus, widgets, customizer)
4. Import content (ถ้ามี)
5. Test functionality
6. Monitor performance

---

## Mapping: Mockup Files → WordPress Templates

### `trendtoday_landing.html` → `front-page.php`
- Region selection page
- Hero section
- Call-to-action

### `trendtoday_news.html` → `home.php` / `index.php`
- Hero/Breaking news section
- Latest news grid
- Category filters
- Trending tags
- Sidebar (popular posts, newsletter)
- Load more button

### `trendtoday_article.html` → `single.php`
- Article header (title, meta, featured image)
- Article content
- Author info
- Related posts
- Comments section
- Share buttons

### `trendtoday_category.html` → `archive.php`
- Category header
- Category description
- Posts grid/list
- Pagination

### `trendtoday_search.html` → `search.php`
- Search form
- Search results
- No results message
- Search suggestions

---

## Dependencies & Requirements

### Required
- WordPress 6.0+
- PHP 7.4+
- MySQL 5.6+

### Recommended Plugins
- **Advanced Custom Fields (ACF)** - สำหรับ custom fields
- **Yoast SEO** หรือ **Rank Math** - สำหรับ SEO
- **WP Super Cache** หรือ **W3 Total Cache** - สำหรับ caching
- **Contact Form 7** - สำหรับ contact forms (ถ้าจำเป็น)

### Optional Plugins
- **WP Mail SMTP** - สำหรับ email delivery
- **Wordfence** - สำหรับ security
- **UpdraftPlus** - สำหรับ backups

---

## Timeline Estimate

| Phase | Tasks | Estimated Time |
|-------|-------|----------------|
| Phase 1 | Theme structure setup | 2-3 hours |
| Phase 2 | Convert mockups to templates | 8-12 hours |
| Phase 3 | WordPress integration | 4-6 hours |
| Phase 4 | Assets & styling | 3-4 hours |
| Phase 5 | Features & functionality | 6-8 hours |
| Phase 6 | WordPress standards | 3-4 hours |
| Phase 7 | Customization options | 4-5 hours |
| Phase 8 | Testing & polish | 4-6 hours |
| **Total** | | **34-48 hours** |

---

## Notes & Considerations

1. **Tailwind CSS**: ต้องตัดสินใจว่าจะใช้ CDN หรือ build เป็น static file
2. **Custom Post Types**: ต้องพิจารณาว่าจะใช้ default Posts หรือสร้าง Custom Post Type
3. **Custom Fields**: แนะนำใช้ ACF เพื่อความสะดวก
4. **Performance**: ต้อง optimize images และ queries
5. **SEO**: ต้องใส่ใจเรื่อง meta tags และ schema markup
6. **Accessibility**: ต้องทำให้ theme เข้าถึงได้สำหรับทุกคน
7. **Multilingual**: ถ้าต้องการรองรับหลายภาษา ต้องพิจารณาใช้ WPML หรือ Polylang

---

## Next Steps

1. เริ่มจาก Phase 1: สร้างโครงสร้าง theme
2. แปลง mockup เป็น templates ทีละหน้า
3. Integrate กับ WordPress functions
4. เพิ่ม features และ functionality
5. Test และ polish
6. Deploy

---

**สร้างเมื่อ:** {{ current_date }}  
**เวอร์ชัน:** 1.0  
**สถานะ:** Ready for Implementation
