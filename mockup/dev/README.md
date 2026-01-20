# KonDernTang Mockup - Phase 2 (Multi-file Structure)

โครงสร้างไฟล์ที่แยกออกมาจาก `konderntang_original.html` เพื่อให้จัดการง่ายขึ้น

## โครงสร้างโฟลเดอร์

```
dev/
├── index.html              # Entry point - หน้าแรก
├── pages/                  # หน้าแต่ละหน้า
│   ├── home.html
│   ├── archive.html
│   ├── international.html
│   ├── single.html
│   ├── hotels.html
│   ├── flights.html
│   ├── guide.html
│   ├── news.html
│   ├── promotion.html
│   ├── search.html
│   ├── about.html
│   ├── contact.html
│   ├── news-archive.html
│   ├── login.html
│   ├── seasonal.html
│   ├── 404.html
│   └── 500.html
├── components/             # Components ที่ใช้ซ้ำ
│   ├── header.html
│   ├── footer.html
│   ├── navigation.html
│   └── breadcrumb.html
├── assets/
│   ├── css/
│   │   └── main.css        # Custom styles
│   ├── js/
│   │   ├── main.js         # Main navigation & utilities
│   │   ├── hero-slider.js  # Hero slider functionality
│   │   ├── search.js       # Search functionality
│   │   ├── cookie-consent.js # Cookie consent
│   │   └── utils.js        # Utility functions
│   └── images/             # Images
└── config/
    └── navigation.js       # Navigation configuration
```

## วิธีใช้งาน

### Development
เปิด `index.html` ใน browser หรือใช้ local server:
```bash
# Python
python -m http.server 8000

# Node.js (http-server)
npx http-server
```

### Navigation
ใช้ JavaScript routing แบบ SPA (Single Page Application)
- หน้าแต่ละหน้าจะถูกโหลดแบบ dynamic
- ใช้ `navigateTo('page-name')` เพื่อเปลี่ยนหน้า

## หมายเหตุ

- ไฟล์นี้ยังเป็น **prototype/mockup** ไม่ใช่ production code
- ใช้ Tailwind CSS CDN (ควรเปลี่ยนเป็น production build)
- ใช้ placeholder images (ควรเปลี่ยนเป็น real images)
- Navigation ใช้ JavaScript แบบ simple (ควรใช้ routing library)
