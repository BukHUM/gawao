// Navigation Configuration
const breadcrumbConfig = {
    'home': { text: 'หน้าแรก', show: false },
    'archive': { text: 'เที่ยวทั่วไทย', show: false },
    'international': { text: 'เที่ยวต่างประเทศ', show: false },
    'single': { text: 'รายละเอียดบทความ', show: true },
    'hotels': { text: 'จองที่พัก', show: false },
    'flights': { text: 'จองตั๋วเครื่องบิน', show: false },
    'guide': { text: 'คู่มือเดินทาง', show: false },
    'news': { text: 'รายละเอียดข่าวสาร', show: true },
    'promotion': { text: 'โปรโมชั่น', show: false },
    'search': { text: 'ค้นหา', show: false },
    'about': { text: 'เกี่ยวกับเรา', show: false },
    'contact': { text: 'ติดต่อเรา', show: false },
    'news-archive': { text: 'ข่าวสารท่องเที่ยว', show: false },
    'login': { text: 'เข้าสู่ระบบ', show: false },
    '404': { text: 'ไม่พบหน้า', show: false },
    '500': { text: 'ข้อผิดพลาดเซิร์ฟเวอร์', show: false },
    'seasonal': { text: 'เที่ยวตามฤดูกาล', show: false }
};

// Export for use in other files
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { breadcrumbConfig };
}
