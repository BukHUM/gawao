// Navigation Configuration
const breadcrumbConfig = {
    'home': { text: 'หน้าแรก', show: false },
    'archive': { text: 'เที่ยวทั่วไทย', show: true },
    'international': { text: 'เที่ยวต่างประเทศ', show: true },
    'single': { text: 'รายละเอียดบทความ', show: true },
    'hotels': { text: 'จองที่พัก', show: true },
    'flights': { text: 'จองตั๋วเครื่องบิน', show: true },
    'guide': { text: 'คู่มือเดินทาง', show: true },
    'news': { text: 'รายละเอียดข่าวสาร', show: true },
    'promotion': { text: 'โปรโมชั่น', show: true },
    'search': { text: 'ค้นหา', show: true },
    'about': { text: 'เกี่ยวกับเรา', show: true },
    'contact': { text: 'ติดต่อเรา', show: true },
    'news-archive': { text: 'ข่าวสารท่องเที่ยว', show: true },
    'login': { text: 'เข้าสู่ระบบ', show: true },
    '404': { text: 'ไม่พบหน้า', show: true },
    '500': { text: 'ข้อผิดพลาดเซิร์ฟเวอร์', show: true },
    'seasonal': { text: 'เที่ยวตามฤดูกาล', show: true }
};

// Export for use in other files
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { breadcrumbConfig };
}
