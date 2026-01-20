<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>คนเดินทาง (KonDernTang) - Full Prototype</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&family=Sarabun:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Sarabun', 'sans-serif'],
                        heading: ['Kanit', 'sans-serif'],
                    },
                    colors: {
                        primary: '#0ea5e9',   
                        secondary: '#f97316', 
                        dark: '#1e293b',
                        light: '#f8fafc',
                    }
                }
            }
        }
    </script>
    
    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        .animate-marquee { display: inline-block; animation: marquee 25s linear infinite; }
        
        /* Utility for switching views */
        .page-view { display: none; animation: fadeIn 0.3s ease-in-out; }
        .page-view.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans flex flex-col min-h-screen">

    <!-- Navigation Bar (Shared) -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="#" onclick="navigateTo('home')" class="flex-shrink-0 flex items-center gap-2">
                    <img src="https://placehold.co/200x60/transparent/0ea5e9?text=KonDernTang&font=roboto" alt="KonDernTang Logo" class="h-10 w-auto object-contain">
                </a>

                <!-- Desktop Menu -->
                <div class="hidden xl:flex space-x-1 items-center font-heading font-medium text-gray-600">
                    <button onclick="navigateTo('home')" class="px-3 py-2 text-primary hover:bg-blue-50 rounded-md transition">หน้าแรก</button>
                    <button onclick="navigateTo('archive')" class="px-3 py-2 hover:text-primary hover:bg-gray-50 rounded-md transition">เที่ยวทั่วไทย</button>
                    <button onclick="navigateTo('international')" class="px-3 py-2 hover:text-primary hover:bg-gray-50 rounded-md transition">เที่ยวต่างประเทศ</button>
                    <button onclick="navigateTo('hotels')" class="px-3 py-2 hover:text-primary hover:bg-gray-50 rounded-md transition">ที่พักแนะนำ</button>
                    <button onclick="navigateTo('flights')" class="px-3 py-2 hover:text-primary hover:bg-gray-50 rounded-md transition">จองตั๋วเครื่องบิน</button>
                    <a href="#" class="px-3 py-2 hover:text-primary hover:bg-gray-50 rounded-md transition">คู่มือเดินทาง</a>
                    <a href="#" class="ml-2 px-4 py-2 bg-secondary text-white rounded-full hover:bg-orange-600 transition shadow-sm flex items-center gap-1">
                        <i class="ph ph-tag"></i> โปรโมชั่น
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="xl:hidden text-gray-600 hover:text-primary p-2">
                    <i class="ph ph-list text-3xl"></i>
                </button>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden xl:hidden bg-white border-t border-gray-100 px-4 pt-2 pb-4 space-y-1 shadow-lg font-heading">
            <button onclick="navigateTo('home')" class="block w-full text-left px-3 py-2 text-primary font-medium bg-blue-50 rounded-md">หน้าแรก</button>
            <button onclick="navigateTo('archive')" class="block w-full text-left px-3 py-2 text-gray-600 hover:bg-gray-50 rounded-md">เที่ยวทั่วไทย</button>
            <button onclick="navigateTo('international')" class="block w-full text-left px-3 py-2 text-gray-600 hover:bg-gray-50 rounded-md">เที่ยวต่างประเทศ</button>
            <button onclick="navigateTo('hotels')" class="block w-full text-left px-3 py-2 text-gray-600 hover:bg-gray-50 rounded-md">ที่พักแนะนำ</button>
            <button onclick="navigateTo('flights')" class="block w-full text-left px-3 py-2 text-gray-600 hover:bg-gray-50 rounded-md">จองตั๋วเครื่องบิน</button>
        </div>
    </nav>

    <!-- ================= VIEW 1: HOME PAGE ================= -->
    <div id="view-home" class="page-view active">
        <!-- Hero Section -->
        <header class="relative bg-dark h-[500px] flex items-end overflow-hidden group">
            <img src="https://images.unsplash.com/photo-1506665531195-3566af2b4dfa?auto=format&fit=crop&w=1600&q=80" alt="Travel Hero" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-80">
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
            <div class="container mx-auto px-4 pb-12 relative z-10">
                <span class="inline-block bg-secondary text-white text-xs font-bold px-3 py-1 rounded-full mb-4 uppercase tracking-wider">Highlight</span>
                <h1 class="text-3xl md:text-5xl font-heading font-bold text-white mb-4 leading-tight drop-shadow-md">
                    แจกแพลนเที่ยว "น่าน" 3 วัน 2 คืน<br>หน้าฝนฉบับคนไม่มีรถ
                </h1>
                <p class="text-gray-300 text-lg mb-6 max-w-2xl font-light line-clamp-2">
                    พาไปสัมผัสความเขียวขจีของเมืองน่าน ในบรรยากาศสุดชิลล์ พักโฮมสเตย์กลางทุ่งนา...
                </p>
                <button onclick="navigateTo('single')" class="inline-flex items-center gap-2 bg-white text-dark font-heading font-semibold px-6 py-3 rounded-lg hover:bg-gray-100 transition">
                    อ่านรีวิวฉบับเต็ม <i class="ph ph-arrow-right"></i>
                </button>
            </div>
        </header>

        <!-- Ticker -->
        <div class="bg-primary text-white py-2 overflow-hidden relative">
            <div class="container mx-auto px-4 flex items-center">
                <span class="font-heading font-bold bg-white text-primary px-2 py-0.5 rounded text-sm mr-3 whitespace-nowrap">🔥 อัปเดต</span>
                <div class="marquee-container overflow-hidden w-full relative h-6">
                    <span class="absolute whitespace-nowrap animate-marquee font-medium text-sm pt-0.5">
                        • ญี่ปุ่นประกาศฟรีวีซ่าถาวรสำหรับคนไทยแล้ว • โปรฯ AirAsia 0 บาท จองด่วนคืนนี้ • โรงแรมพัทยาลด 50%
                    </span>
                </div>
            </div>
        </div>

        <!-- Home Content -->
        <main class="container mx-auto px-4 py-12">
            <!-- Section 1 -->
            <div class="flex justify-between items-end mb-8 border-l-4 border-primary pl-4">
                <div>
                    <h2 class="text-3xl font-heading font-bold text-dark">เที่ยวทั่วไทย</h2>
                    <p class="text-gray-500 mt-1">หลงรักเมืองไทย ไปกี่ครั้งก็ไม่เบื่อ</p>
                </div>
                <button onclick="navigateTo('archive')" class="text-primary hover:text-blue-700 font-medium hidden md:block">ดูทั้งหมด <i class="ph ph-arrow-right inline-block"></i></button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-16">
                <!-- Card Item -->
                <article class="bg-white rounded-xl shadow-sm hover:shadow-lg transition overflow-hidden group border border-gray-100 cursor-pointer" onclick="navigateTo('single')">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1596422846543-75c6a197f070?auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                        <span class="absolute top-3 left-3 bg-white/90 text-dark text-xs font-bold px-2 py-1 rounded backdrop-blur-sm">เชียงใหม่</span>
                    </div>
                    <div class="p-5">
                        <h3 class="font-heading font-semibold text-lg text-dark mb-2 leading-snug group-hover:text-primary transition">ม่อนแจ่ม 2026 เปลี่ยนไปแค่ไหน?</h3>
                        <div class="flex items-center gap-2 text-gray-400 text-xs mt-3"><i class="ph ph-calendar"></i> 19 ม.ค. 2026</div>
                    </div>
                </article>
                 <!-- Card Item -->
                 <article class="bg-white rounded-xl shadow-sm hover:shadow-lg transition overflow-hidden group border border-gray-100 cursor-pointer" onclick="navigateTo('single')">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1589394815804-989b372401db?auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                        <span class="absolute top-3 left-3 bg-white/90 text-dark text-xs font-bold px-2 py-1 rounded backdrop-blur-sm">ภูเก็ต</span>
                    </div>
                    <div class="p-5">
                        <h3 class="font-heading font-semibold text-lg text-dark mb-2 leading-snug group-hover:text-primary transition">เดินเล่นย่านเมืองเก่าภูเก็ต 5 คาเฟ่ลับ</h3>
                        <div class="flex items-center gap-2 text-gray-400 text-xs mt-3"><i class="ph ph-calendar"></i> 18 ม.ค. 2026</div>
                    </div>
                </article>
                 <!-- Card Item -->
                 <article class="bg-white rounded-xl shadow-sm hover:shadow-lg transition overflow-hidden group border border-gray-100 cursor-pointer" onclick="navigateTo('single')">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1528181304800-259b08848526?auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                        <span class="absolute top-3 left-3 bg-white/90 text-dark text-xs font-bold px-2 py-1 rounded backdrop-blur-sm">กาญจนบุรี</span>
                    </div>
                    <div class="p-5">
                        <h3 class="font-heading font-semibold text-lg text-dark mb-2 leading-snug group-hover:text-primary transition">นั่งรถไฟไปเที่ยวน้ำตกไทรโยค งบ 500 บาท</h3>
                        <div class="flex items-center gap-2 text-gray-400 text-xs mt-3"><i class="ph ph-calendar"></i> 15 ม.ค. 2026</div>
                    </div>
                </article>
                 <!-- Card Item -->
                 <article class="bg-white rounded-xl shadow-sm hover:shadow-lg transition overflow-hidden group border border-gray-100 cursor-pointer" onclick="navigateTo('single')">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1563492065599-3520f775eeed?auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                        <span class="absolute top-3 left-3 bg-white/90 text-dark text-xs font-bold px-2 py-1 rounded backdrop-blur-sm">กรุงเทพฯ</span>
                    </div>
                    <div class="p-5">
                        <h3 class="font-heading font-semibold text-lg text-dark mb-2 leading-snug group-hover:text-primary transition">One Day Trip เจริญกรุง ถ่ายรูป Street Art</h3>
                        <div class="flex items-center gap-2 text-gray-400 text-xs mt-3"><i class="ph ph-calendar"></i> 12 ม.ค. 2026</div>
                    </div>
                </article>
            </div>
            
            <!-- Affiliate Banner -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-400 rounded-2xl p-8 mb-16 text-center text-white shadow-xl">
                 <h2 class="text-3xl font-heading font-bold mb-2"><i class="ph ph-bed"></i> หาที่พักราคาดีที่สุด?</h2>
                 <p class="mb-4">จองกับเราถูกกว่าจองเอง ดีลพิเศษสำหรับคนเดินทาง</p>
                 <button onclick="navigateTo('hotels')" class="bg-secondary hover:bg-orange-600 text-white font-bold py-2 px-6 rounded-lg shadow-lg">ค้นหาที่พัก</button>
            </div>
        </main>
    </div>

    <!-- ================= VIEW 2: ARCHIVE/CATEGORY PAGE (Domestic) ================= -->
    <div id="view-archive" class="page-view bg-gray-50 pb-12">
        <!-- Category Header -->
        <div class="bg-dark text-white py-12 mb-8">
            <div class="container mx-auto px-4 text-center">
                <span class="text-secondary font-bold tracking-wider uppercase text-sm">Category</span>
                <h1 class="text-4xl font-heading font-bold mt-2">เที่ยวทั่วไทย</h1>
                <p class="text-gray-400 mt-2 max-w-2xl mx-auto">รวมรีวิวที่เที่ยวในประเทศไทย ครบทั้ง 77 จังหวัด ตั้งแต่เหนือจรดใต้</p>
            </div>
        </div>

        <div class="container mx-auto px-4 grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar (Left) -->
            <aside class="hidden lg:block lg:col-span-1 space-y-8">
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="font-heading font-bold text-lg mb-4">ภาค (Regions)</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><label class="flex items-center gap-2"><input type="checkbox" class="rounded text-primary"> ภาคเหนือ (120)</label></li>
                        <li><label class="flex items-center gap-2"><input type="checkbox" class="rounded text-primary"> ภาคใต้ (85)</label></li>
                        <li><label class="flex items-center gap-2"><input type="checkbox" class="rounded text-primary"> ภาคอีสาน (60)</label></li>
                        <li><label class="flex items-center gap-2"><input type="checkbox" class="rounded text-primary"> ภาคตะวันออก (45)</label></li>
                    </ul>
                </div>
            </aside>

            <!-- Main Grid (Right) -->
            <main class="lg:col-span-3">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Loop Cards -->
                    <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md transition" onclick="navigateTo('single')">
                        <img src="https://images.unsplash.com/photo-1596422846543-75c6a197f070?w=600" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <span class="text-xs text-primary font-bold">เชียงใหม่</span>
                            <h3 class="font-heading font-bold text-lg mt-1 mb-2 hover:text-primary">ม่อนแจ่มหน้าฝน</h3>
                        </div>
                    </article>
                    <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md transition" onclick="navigateTo('single')">
                        <img src="https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?w=600" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <span class="text-xs text-primary font-bold">เชียงราย</span>
                            <h3 class="font-heading font-bold text-lg mt-1 mb-2 hover:text-primary">ไหว้พระวัดร่องขุ่น</h3>
                        </div>
                    </article>
                    <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md transition" onclick="navigateTo('single')">
                        <img src="https://images.unsplash.com/photo-1590523277543-a94d2e4eb00b?w=600" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <span class="text-xs text-primary font-bold">ภูเก็ต</span>
                            <h3 class="font-heading font-bold text-lg mt-1 mb-2 hover:text-primary">จุดชมวิวแหลมพรหมเทพ</h3>
                        </div>
                    </article>
                    <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md transition" onclick="navigateTo('single')">
                        <img src="https://images.unsplash.com/photo-1504214208698-ea1916a2195a?w=600" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <span class="text-xs text-primary font-bold">กระบี่</span>
                            <h3 class="font-heading font-bold text-lg mt-1 mb-2 hover:text-primary">ไร่เลย์ ทะเลแหวก</h3>
                        </div>
                    </article>
                     <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md transition" onclick="navigateTo('single')">
                        <img src="https://images.unsplash.com/photo-1563492065599-3520f775eeed?w=600" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <span class="text-xs text-primary font-bold">กรุงเทพ</span>
                            <h3 class="font-heading font-bold text-lg mt-1 mb-2 hover:text-primary">วัดอรุณยามเย็น</h3>
                        </div>
                    </article>
                     <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md transition" onclick="navigateTo('single')">
                        <img src="https://images.unsplash.com/photo-1528181304800-259b08848526?w=600" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <span class="text-xs text-primary font-bold">กาญจนบุรี</span>
                            <h3 class="font-heading font-bold text-lg mt-1 mb-2 hover:text-primary">ล่องแพแม่น้ำแคว</h3>
                        </div>
                    </article>
                </div>
            </main>
        </div>
    </div>

    <!-- ================= VIEW 3: INTERNATIONAL PAGE ================= -->
    <div id="view-international" class="page-view bg-gray-50 pb-12">
        <!-- Category Header -->
        <div class="relative bg-dark text-white py-16 mb-8 overflow-hidden">
            <!-- Background Image -->
            <img src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=1600&q=80" class="absolute inset-0 w-full h-full object-cover opacity-30">
            <div class="container mx-auto px-4 text-center relative z-10">
                <span class="text-secondary font-bold tracking-wider uppercase text-sm">International</span>
                <h1 class="text-4xl font-heading font-bold mt-2">เที่ยวต่างประเทศ</h1>
                <p class="text-gray-300 mt-2 max-w-2xl mx-auto">เปิดประสบการณ์ใหม่ในต่างแดน สัมผัสวัฒนธรรมจากทั่วทุกมุมโลก</p>
            </div>
        </div>

        <div class="container mx-auto px-4 grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar (Left) -->
            <aside class="hidden lg:block lg:col-span-1 space-y-8">
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="font-heading font-bold text-lg mb-4">โซนยอดฮิต</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><label class="flex items-center gap-2"><input type="checkbox" class="rounded text-primary"> ญี่ปุ่น (Japan) (150)</label></li>
                        <li><label class="flex items-center gap-2"><input type="checkbox" class="rounded text-primary"> ยุโรป (Europe) (85)</label></li>
                        <li><label class="flex items-center gap-2"><input type="checkbox" class="rounded text-primary"> เอเชีย (Asia) (200)</label></li>
                        <li><label class="flex items-center gap-2"><input type="checkbox" class="rounded text-primary"> อเมริกา (USA) (45)</label></li>
                    </ul>
                </div>
                
                 <!-- Agoda Widget for International -->
                 <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl p-6 text-white text-center">
                    <i class="ph ph-airplane-tilt text-4xl mb-2"></i>
                    <h4 class="font-bold text-xl mb-2">จองตั๋วไปญี่ปุ่น?</h4>
                    <p class="text-sm opacity-90 mb-4">ตั๋วเครื่องบินราคาพิเศษ พร้อมที่พักใกล้สถานีรถไฟ</p>
                    <button class="bg-white text-blue-700 font-bold py-2 px-4 rounded-full w-full hover:bg-gray-100">ดูโปรโมชั่น</button>
                </div>
            </aside>

            <!-- Main Grid (Right) -->
            <main class="lg:col-span-3">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- International Cards -->
                    <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md transition" onclick="navigateTo('single')">
                        <img src="https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?w=600" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <span class="text-xs text-primary font-bold">ญี่ปุ่น</span>
                            <h3 class="font-heading font-bold text-lg mt-1 mb-2 hover:text-primary">ตะลุย Osaka-Kyoto</h3>
                            <div class="flex items-center gap-2 text-gray-400 text-xs"><i class="ph ph-calendar"></i> 20 ม.ค. 2026</div>
                        </div>
                    </article>
                    <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md transition" onclick="navigateTo('single')">
                        <img src="https://images.unsplash.com/photo-1524492412937-b28074a5d7da?w=600" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <span class="text-xs text-primary font-bold">อินเดีย</span>
                            <h3 class="font-heading font-bold text-lg mt-1 mb-2 hover:text-primary">ทัชมาฮาลครั้งหนึ่งในชีวิต</h3>
                             <div class="flex items-center gap-2 text-gray-400 text-xs"><i class="ph ph-calendar"></i> 18 ม.ค. 2026</div>
                        </div>
                    </article>
                    <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md transition" onclick="navigateTo('single')">
                        <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=600" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <span class="text-xs text-primary font-bold">ตุรกี</span>
                            <h3 class="font-heading font-bold text-lg mt-1 mb-2 hover:text-primary">นอนดูบอลลูนที่ Cappadocia</h3>
                             <div class="flex items-center gap-2 text-gray-400 text-xs"><i class="ph ph-calendar"></i> 15 ม.ค. 2026</div>
                        </div>
                    </article>
                    <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md transition" onclick="navigateTo('single')">
                        <img src="https://images.unsplash.com/photo-1528702748617-c64d49f918af?w=600" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <span class="text-xs text-primary font-bold">สวิสเซอร์แลนด์</span>
                            <h3 class="font-heading font-bold text-lg mt-1 mb-2 hover:text-primary">Zermatt เมืองไร้มลพิษ</h3>
                             <div class="flex items-center gap-2 text-gray-400 text-xs"><i class="ph ph-calendar"></i> 12 ม.ค. 2026</div>
                        </div>
                    </article>
                     <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md transition" onclick="navigateTo('single')">
                        <img src="https://images.unsplash.com/photo-1555400038-63f5ba517a47?w=600" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <span class="text-xs text-primary font-bold">อินโดนีเซีย</span>
                            <h3 class="font-heading font-bold text-lg mt-1 mb-2 hover:text-primary">Bali 4 วัน 3 คืน</h3>
                             <div class="flex items-center gap-2 text-gray-400 text-xs"><i class="ph ph-calendar"></i> 10 ม.ค. 2026</div>
                        </div>
                    </article>
                     <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md transition" onclick="navigateTo('single')">
                        <img src="https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=600" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <span class="text-xs text-primary font-bold">เวียดนาม</span>
                            <h3 class="font-heading font-bold text-lg mt-1 mb-2 hover:text-primary">เดินเล่น Hoi An ยามค่ำ</h3>
                             <div class="flex items-center gap-2 text-gray-400 text-xs"><i class="ph ph-calendar"></i> 05 ม.ค. 2026</div>
                        </div>
                    </article>
                </div>

                <!-- Pagination -->
                <div class="flex justify-center mt-12 gap-2">
                    <button class="px-4 py-2 bg-primary text-white rounded">1</button>
                    <button class="px-4 py-2 bg-white border hover:bg-gray-50 rounded">2</button>
                    <button class="px-4 py-2 bg-white border hover:bg-gray-50 rounded">Next</button>
                </div>
            </main>
        </div>
    </div>

    <!-- ================= VIEW 4: SINGLE POST PAGE ================= -->
    <div id="view-single" class="page-view bg-white pb-16">
        <!-- Post Hero -->
        <div class="relative h-[60vh] w-full">
            <img src="https://images.unsplash.com/photo-1506665531195-3566af2b4dfa?auto=format&fit=crop&w=1600&q=80" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
            <div class="absolute bottom-0 left-0 w-full p-8 md:p-16 text-white container mx-auto">
                <div class="flex items-center gap-3 mb-4 text-sm font-medium">
                    <span class="bg-secondary px-3 py-1 rounded-full">เที่ยวทั่วไทย</span>
                    <span><i class="ph ph-calendar"></i> 19 ม.ค. 2026</span>
                    <span><i class="ph ph-user"></i> โดย แอดมินคนเดินทาง</span>
                </div>
                <h1 class="text-3xl md:text-5xl font-heading font-bold leading-tight drop-shadow-lg">
                    แจกแพลนเที่ยว "น่าน" 3 วัน 2 คืน<br>หน้าฝนฉบับคนไม่มีรถ
                </h1>
            </div>
        </div>

        <div class="container mx-auto px-4 py-12 grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Article Content (Left) -->
            <article class="lg:col-span-2 prose prose-lg prose-blue max-w-none font-sans text-gray-700">
                <p class="lead text-xl text-gray-600 font-light italic border-l-4 border-secondary pl-4">
                    "น่าน... จังหวัดเล็กๆ ที่เต็มไปด้วยเสน่ห์ของขุนเขาและทุ่งนา วันนี้เราจะพาไปสัมผัสความสโลว์ไลฟ์แบบไม่ต้องง้อรถส่วนตัว"
                </p>
                
                <h3 class="font-heading font-bold text-2xl text-dark mt-8 mb-4">วันที่ 1: เดินทางสู่อำเภอปัว</h3>
                <p>เริ่มต้นการเดินทางที่สถานีขนส่งหมอชิต เราเลือกใช้บริการรถทัวร์รอบดึกเพื่อไปถึงน่านตอนเช้าตรู่ ค่าตั๋วประมาณ 600 บาท เมื่อถึงสถานีขนส่งน่านแล้ว สามารถต่อรถสองแถวสีฟ้าไปอำเภอปัวได้เลยในราคา 50 บาท</p>
                
                <img src="https://images.unsplash.com/photo-1588260699056-a9c4033b0060?auto=format&fit=crop&w=1000&q=80" class="rounded-xl w-full my-6 shadow-sm" alt="Nan Rice Field">
                
                <h3 class="font-heading font-bold text-2xl text-dark mt-8 mb-4">ที่พักแนะนำ: ตูบนา โฮมสเตย์</h3>
                <p>ไฮไลท์ของทริปนี้คือการนอนดูทุ่งนาเขียวขจี ที่นี่ราคาเริ่มต้นคืนละ 1,200 บาท รวมอาหารเช้า บรรยากาศดีมาก เงียบสงบ เหมาะกับการมาพักผ่อนจริงๆ</p>

                <!-- In-Article Affiliate Widget -->
                <div class="my-8 p-6 bg-blue-50 border border-blue-100 rounded-xl flex flex-col md:flex-row items-center gap-6">
                    <div class="flex-1">
                        <h4 class="font-heading font-bold text-lg text-primary mb-1">สนใจที่พักนี้?</h4>
                        <p class="text-sm text-gray-600">เช็คราคาล่าสุดและโปรโมชั่นพิเศษผ่าน Agoda</p>
                    </div>
                    <button class="bg-primary hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg shadow whitespace-nowrap">
                        เช็คราคาที่พัก <i class="ph ph-arrow-square-out"></i>
                    </button>
                </div>

                <h3 class="font-heading font-bold text-2xl text-dark mt-8 mb-4">สรุปค่าใช้จ่าย</h3>
                <ul class="list-disc pl-5 space-y-2">
                    <li>ค่ารถทัวร์ไป-กลับ: 1,200 บาท</li>
                    <li>ค่าที่พัก 2 คืน (หาร 2): 1,200 บาท</li>
                    <li>ค่ากินและค่ารถสองแถว: 1,500 บาท</li>
                    <li><strong>รวมทั้งหมด: 3,900 บาท/คน</strong></li>
                </ul>

                <div class="flex gap-4 mt-12 border-t pt-8">
                    <button class="flex items-center gap-2 bg-[#1877F2] text-white px-4 py-2 rounded hover:opacity-90"><i class="ph ph-facebook-logo text-xl"></i> Share</button>
                    <button class="flex items-center gap-2 bg-[#1DA1F2] text-white px-4 py-2 rounded hover:opacity-90"><i class="ph ph-twitter-logo text-xl"></i> Tweet</button>
                    <button class="flex items-center gap-2 bg-[#06C755] text-white px-4 py-2 rounded hover:opacity-90"><i class="ph ph-line-logo text-xl"></i> Line</button>
                </div>
            </article>

            <!-- Sidebar (Right) -->
            <aside class="space-y-8">
                <!-- Author Box -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm text-center">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Felix" class="w-20 h-20 rounded-full mx-auto mb-4 bg-gray-100">
                    <h4 class="font-heading font-bold text-lg">แอดมินคนเดินทาง</h4>
                    <p class="text-gray-500 text-sm mt-2">นักเดินทางสายลุย ชอบกาแฟ และการถ่ายรูป หลงรักภูเขามากกว่าทะเล</p>
                    <div class="flex justify-center gap-3 mt-4 text-gray-400">
                        <i class="ph ph-facebook-logo text-xl cursor-pointer hover:text-primary"></i>
                        <i class="ph ph-instagram-logo text-xl cursor-pointer hover:text-pink-600"></i>
                    </div>
                </div>

                <!-- Related Posts -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <h4 class="font-heading font-bold text-lg mb-4 border-l-4 border-secondary pl-3">บทความแนะนำ</h4>
                    <div class="space-y-4">
                        <div class="flex gap-3 cursor-pointer group" onclick="navigateTo('single')">
                            <img src="https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?w=100" class="w-20 h-20 object-cover rounded-lg">
                            <div>
                                <h5 class="font-bold text-sm text-dark group-hover:text-primary leading-tight">เชียงราย 3 วัน 2 คืน เที่ยววัดร่องขุ่น</h5>
                                <span class="text-xs text-gray-400 mt-1 block">15 ม.ค. 2026</span>
                            </div>
                        </div>
                        <div class="flex gap-3 cursor-pointer group" onclick="navigateTo('single')">
                            <img src="https://images.unsplash.com/photo-1528181304800-259b08848526?w=100" class="w-20 h-20 object-cover rounded-lg">
                            <div>
                                <h5 class="font-bold text-sm text-dark group-hover:text-primary leading-tight">นอนแพเมืองกาญฯ งบประหยัด</h5>
                                <span class="text-xs text-gray-400 mt-1 block">10 ม.ค. 2026</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sticky Ad / Promo -->
                <div class="sticky top-24 bg-gradient-to-br from-secondary to-orange-500 rounded-xl p-6 text-white text-center">
                    <i class="ph ph-ticket text-4xl mb-2"></i>
                    <h4 class="font-bold text-xl mb-2">จองตั๋วเครื่องบิน?</h4>
                    <p class="text-sm opacity-90 mb-4">เปรียบเทียบราคาตั๋วเครื่องบินทั่วโลก ราคาดีที่สุดที่นี่</p>
                    <button class="bg-white text-secondary font-bold py-2 px-4 rounded-full w-full hover:bg-gray-100">เช็คราคา</button>
                </div>
            </aside>
        </div>
    </div>
    
    <!-- ================= VIEW 5: HOTELS PAGE ================= -->
    <div id="view-hotels" class="page-view bg-gray-50 pb-12">
        <!-- Hero Search Section (Agoda Style) -->
        <div class="relative bg-blue-900 h-[500px] flex items-center justify-center bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=1600&q=80');">
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="relative z-10 w-full max-w-4xl px-4">
                <div class="text-center text-white mb-8">
                    <h1 class="text-3xl md:text-5xl font-heading font-bold mb-2">ค้นหาที่พักราคาดีที่สุด</h1>
                    <p class="text-lg opacity-90">โรงแรม รีสอร์ท โฮสเทล และบ้านพักตากอากาศทั่วโลก</p>
                </div>
                
                <!-- Simulated Agoda Search Box Container -->
                <div class="bg-white p-4 md:p-6 rounded-xl shadow-2xl">
                    <!-- Banner/Badge -->
                    <div class="flex items-center gap-2 mb-4 text-sm font-bold text-gray-500">
                         <span class="text-primary border-b-2 border-primary pb-1 flex items-center gap-1 cursor-pointer"><i class="ph ph-bed"></i> ค้นหาที่พัก</span>
                         <span onclick="navigateTo('flights')" class="text-gray-400 hover:text-primary pl-4 flex items-center gap-1 cursor-pointer"><i class="ph ph-airplane-tilt"></i> จองตั๋วเครื่องบิน</span>
                    </div>

                    <!-- Search Form Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-2">
                        <!-- Destination Input -->
                        <div class="md:col-span-4 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ph ph-magnifying-glass text-gray-400 text-xl"></i>
                            </div>
                            <input type="text" class="w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" placeholder="ไปเที่ยวที่ไหนดี? (เช่น โตเกียว, ภูเก็ต)">
                        </div>

                        <!-- Date Inputs -->
                        <div class="md:col-span-3 relative">
                             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ph ph-calendar-blank text-gray-400 text-xl"></i>
                            </div>
                            <input type="text" class="w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-primary" placeholder="เช็คอิน - เช็คเอาท์">
                        </div>

                        <!-- Guests Input -->
                         <div class="md:col-span-3 relative">
                             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ph ph-users text-gray-400 text-xl"></i>
                            </div>
                            <select class="w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-primary bg-white appearance-none cursor-pointer">
                                <option>2 ผู้ใหญ่, 1 ห้อง</option>
                                <option>1 ผู้ใหญ่, 1 ห้อง</option>
                                <option>ครอบครัว (2 ผู้ใหญ่, 2 เด็ก)</option>
                            </select>
                        </div>

                        <!-- Search Button -->
                        <div class="md:col-span-2">
                            <button class="w-full h-full bg-secondary hover:bg-orange-600 text-white font-bold py-3 px-4 rounded-lg transition shadow-md flex items-center justify-center gap-2 text-lg">
                                ค้นหา
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Sections -->
        <div class="container mx-auto px-4 py-12">
            <!-- Promotion Banners -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                 <div class="bg-blue-50 rounded-xl p-6 flex items-center justify-between border border-blue-100 cursor-pointer hover:shadow-md transition">
                    <div>
                        <h3 class="font-heading font-bold text-xl text-dark">ส่วนลด Early Bird 20%</h3>
                        <p class="text-gray-500 text-sm mt-1">จองล่วงหน้า 60 วัน สำหรับโรงแรมในญี่ปุ่น</p>
                        <span class="inline-block mt-3 text-primary font-semibold text-sm">เก็บโค้ดเลย <i class="ph ph-arrow-right"></i></span>
                    </div>
                    <i class="ph ph-airplane-tilt text-5xl text-blue-200"></i>
                 </div>
                 <div class="bg-orange-50 rounded-xl p-6 flex items-center justify-between border border-orange-100 cursor-pointer hover:shadow-md transition">
                    <div>
                        <h3 class="font-heading font-bold text-xl text-dark">Flash Sale พัทยา</h3>
                        <p class="text-gray-500 text-sm mt-1">โรงแรมติดทะเล เริ่มต้น 999 บาท</p>
                        <span class="inline-block mt-3 text-secondary font-semibold text-sm">ดูดีลพิเศษ <i class="ph ph-arrow-right"></i></span>
                    </div>
                    <i class="ph ph-sun text-5xl text-orange-200"></i>
                 </div>
            </div>

            <!-- Popular Destinations -->
            <h2 class="font-heading font-bold text-2xl text-dark mb-6 border-l-4 border-primary pl-3">จุดหมายยอดฮิตสำหรับพักผ่อน</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-16">
                <!-- City 1 -->
                <div class="group cursor-pointer">
                    <div class="rounded-full w-32 h-32 mx-auto overflow-hidden border-4 border-white shadow-md mb-3 group-hover:scale-105 transition">
                        <img src="https://images.unsplash.com/photo-1563492065599-3520f775eeed?w=200" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-center font-bold text-dark group-hover:text-primary">กรุงเทพฯ</h3>
                </div>
                 <!-- City 2 -->
                 <div class="group cursor-pointer">
                    <div class="rounded-full w-32 h-32 mx-auto overflow-hidden border-4 border-white shadow-md mb-3 group-hover:scale-105 transition">
                        <img src="https://images.unsplash.com/photo-1596422846543-75c6a197f070?w=200" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-center font-bold text-dark group-hover:text-primary">เชียงใหม่</h3>
                </div>
                 <!-- City 3 -->
                 <div class="group cursor-pointer">
                    <div class="rounded-full w-32 h-32 mx-auto overflow-hidden border-4 border-white shadow-md mb-3 group-hover:scale-105 transition">
                        <img src="https://images.unsplash.com/photo-1589394815804-989b372401db?w=200" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-center font-bold text-dark group-hover:text-primary">ภูเก็ต</h3>
                </div>
                 <!-- City 4 -->
                 <div class="group cursor-pointer">
                    <div class="rounded-full w-32 h-32 mx-auto overflow-hidden border-4 border-white shadow-md mb-3 group-hover:scale-105 transition">
                        <img src="https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?w=200" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-center font-bold text-dark group-hover:text-primary">โตเกียว</h3>
                </div>
                 <!-- City 5 -->
                 <div class="group cursor-pointer">
                    <div class="rounded-full w-32 h-32 mx-auto overflow-hidden border-4 border-white shadow-md mb-3 group-hover:scale-105 transition">
                        <img src="https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=200" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-center font-bold text-dark group-hover:text-primary">ดานัง</h3>
                </div>
                 <!-- City 6 -->
                 <div class="group cursor-pointer">
                    <div class="rounded-full w-32 h-32 mx-auto overflow-hidden border-4 border-white shadow-md mb-3 group-hover:scale-105 transition">
                        <img src="https://images.unsplash.com/photo-1528181304800-259b08848526?w=200" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-center font-bold text-dark group-hover:text-primary">กาญจนบุรี</h3>
                </div>
            </div>

            <!-- Recommended Hotels List -->
            <h2 class="font-heading font-bold text-2xl text-dark mb-6 border-l-4 border-secondary pl-3">ที่พักแนะนำ (Review 9.0+)</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Hotel Card 1 -->
                <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition overflow-hidden border border-gray-100 group">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?w=600" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <span class="absolute top-2 right-2 bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded">9.2 ยอดเยี่ยม</span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-dark truncate">Grande Centre Point Pattaya</h3>
                        <p class="text-xs text-gray-500 mb-2"><i class="ph ph-map-pin"></i> พัทยาเหนือ, ชลบุรี</p>
                        <div class="flex items-end justify-between mt-3">
                            <div>
                                <span class="text-xs text-gray-400 line-through">฿4,500</span>
                                <div class="text-secondary font-bold text-lg">฿3,200</div>
                            </div>
                            <button class="bg-primary text-white text-sm px-3 py-1.5 rounded hover:bg-blue-600">ดูห้องพัก</button>
                        </div>
                    </div>
                </div>

                <!-- Hotel Card 2 -->
                <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition overflow-hidden border border-gray-100 group">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=600" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <span class="absolute top-2 right-2 bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded">8.9 ดีมาก</span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-dark truncate">Sala Ayutthaya</h3>
                        <p class="text-xs text-gray-500 mb-2"><i class="ph ph-map-pin"></i> ริมแม่น้ำ, อยุธยา</p>
                        <div class="flex items-end justify-between mt-3">
                            <div>
                                <span class="text-xs text-gray-400 line-through">฿5,200</span>
                                <div class="text-secondary font-bold text-lg">฿4,100</div>
                            </div>
                            <button class="bg-primary text-white text-sm px-3 py-1.5 rounded hover:bg-blue-600">ดูห้องพัก</button>
                        </div>
                    </div>
                </div>

                <!-- Hotel Card 3 -->
                <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition overflow-hidden border border-gray-100 group">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=600" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <span class="absolute top-2 right-2 bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded">9.5 สุดยอด</span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-dark truncate">Keemala Phuket</h3>
                        <p class="text-xs text-gray-500 mb-2"><i class="ph ph-map-pin"></i> กมลา, ภูเก็ต</p>
                        <div class="flex items-end justify-between mt-3">
                            <div>
                                <span class="text-xs text-gray-400 line-through">฿12,000</span>
                                <div class="text-secondary font-bold text-lg">฿9,500</div>
                            </div>
                            <button class="bg-primary text-white text-sm px-3 py-1.5 rounded hover:bg-blue-600">ดูห้องพัก</button>
                        </div>
                    </div>
                </div>

                <!-- Hotel Card 4 -->
                <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition overflow-hidden border border-gray-100 group">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <span class="absolute top-2 right-2 bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded">9.0 ยอดเยี่ยม</span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-dark truncate">The Standard Hua Hin</h3>
                        <p class="text-xs text-gray-500 mb-2"><i class="ph ph-map-pin"></i> หัวหิน, ประจวบฯ</p>
                        <div class="flex items-end justify-between mt-3">
                            <div>
                                <span class="text-xs text-gray-400 line-through">฿6,500</span>
                                <div class="text-secondary font-bold text-lg">฿4,800</div>
                            </div>
                            <button class="bg-primary text-white text-sm px-3 py-1.5 rounded hover:bg-blue-600">ดูห้องพัก</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ================= VIEW 6: FLIGHTS PAGE (NEW) ================= -->
    <div id="view-flights" class="page-view bg-gray-50 pb-12">
        <!-- Hero Search Section (Flights) -->
        <div class="relative bg-sky-900 h-[550px] flex items-center justify-center bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=1600&q=80');">
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="relative z-10 w-full max-w-5xl px-4">
                <div class="text-center text-white mb-8">
                    <h1 class="text-3xl md:text-5xl font-heading font-bold mb-2">จองตั๋วเครื่องบิน ราคาถูกที่สุด</h1>
                    <p class="text-lg opacity-90">เปรียบเทียบราคาจากทุกสายการบิน จองง่าย ได้ตั๋วชัวร์</p>
                </div>
                
                <!-- Flight Search Box Container -->
                <div class="bg-white p-4 md:p-6 rounded-xl shadow-2xl">
                    <!-- Tabs -->
                    <div class="flex items-center gap-6 mb-6 border-b border-gray-100 pb-3">
                         <span onclick="navigateTo('hotels')" class="text-gray-400 hover:text-primary flex items-center gap-2 cursor-pointer transition font-medium"><i class="ph ph-bed text-xl"></i> ค้นหาที่พัก</span>
                         <span class="text-secondary border-b-2 border-secondary pb-1 flex items-center gap-2 cursor-pointer font-bold"><i class="ph ph-airplane-tilt text-xl"></i> จองตั๋วเครื่องบิน</span>
                    </div>

                    <!-- Search Form Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                        <!-- Origin Input -->
                        <div class="md:col-span-3">
                            <label class="block text-xs font-bold text-gray-500 mb-1 ml-1">ต้นทาง</label>
                            <div class="relative">
                                <i class="ph ph-airplane-takeoff absolute left-3 top-3.5 text-gray-400 text-lg"></i>
                                <input type="text" class="w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-secondary bg-gray-50" value="กรุงเทพฯ (BKK)">
                            </div>
                        </div>

                        <!-- Destination Input -->
                        <div class="md:col-span-3">
                            <label class="block text-xs font-bold text-gray-500 mb-1 ml-1">ปลายทาง</label>
                            <div class="relative">
                                <i class="ph ph-airplane-landing absolute left-3 top-3.5 text-gray-400 text-lg"></i>
                                <input type="text" class="w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-secondary" placeholder="ไปไหนดี?">
                            </div>
                        </div>

                        <!-- Date Inputs -->
                        <div class="md:col-span-2">
                             <label class="block text-xs font-bold text-gray-500 mb-1 ml-1">วันเดินทาง</label>
                             <div class="relative">
                                <i class="ph ph-calendar-blank absolute left-3 top-3.5 text-gray-400 text-lg"></i>
                                <input type="text" class="w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-secondary" placeholder="วว/ดด/ปป">
                            </div>
                        </div>
                        
                        <!-- Passengers -->
                         <div class="md:col-span-2">
                             <label class="block text-xs font-bold text-gray-500 mb-1 ml-1">ผู้โดยสาร/ชั้น</label>
                             <select class="w-full px-3 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-secondary bg-white text-sm">
                                <option>1 ผู้ใหญ่, ชั้นประหยัด</option>
                                <option>2 ผู้ใหญ่, ชั้นประหยัด</option>
                                <option>ชั้นธุรกิจ</option>
                            </select>
                        </div>

                        <!-- Search Button -->
                        <div class="md:col-span-2">
                            <button class="w-full bg-secondary hover:bg-orange-600 text-white font-bold py-3 px-4 rounded-lg transition shadow-md flex items-center justify-center gap-2">
                                <i class="ph ph-magnifying-glass font-bold"></i> ค้นหา
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-3 flex gap-4 text-xs text-gray-500">
                        <label class="flex items-center gap-1 cursor-pointer"><input type="radio" name="trip" checked> เที่ยวเดียว</label>
                        <label class="flex items-center gap-1 cursor-pointer"><input type="radio" name="trip"> ไป-กลับ</label>
                        <label class="flex items-center gap-1 cursor-pointer"><input type="checkbox"> บินตรงเท่านั้น</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flights Content -->
        <div class="container mx-auto px-4 py-12">
            <!-- Promo Banners -->
             <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                 <a href="#" class="block rounded-xl overflow-hidden shadow-sm hover:shadow-md transition relative group h-40">
                     <img src="https://images.unsplash.com/photo-1542296332-2e44a4037213?w=600" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                     <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-transparent p-5 flex flex-col justify-center">
                         <span class="text-yellow-400 font-bold text-xs uppercase tracking-wider mb-1">Domestic Deals</span>
                         <h3 class="text-white font-bold text-lg leading-tight">โปรฯ 0 บาท<br>บินทั่วไทย</h3>
                         <span class="text-white text-xs mt-2 underline">จองด่วน ></span>
                     </div>
                 </a>
                 <a href="#" class="block rounded-xl overflow-hidden shadow-sm hover:shadow-md transition relative group h-40">
                     <img src="https://images.unsplash.com/photo-1526481280693-3bfa7568e0f3?w=600" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                     <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-transparent p-5 flex flex-col justify-center">
                         <span class="text-pink-400 font-bold text-xs uppercase tracking-wider mb-1">Japan Lovers</span>
                         <h3 class="text-white font-bold text-lg leading-tight">ไปญี่ปุ่น<br>เริ่ม 9,xxx บาท</h3>
                         <span class="text-white text-xs mt-2 underline">ดูราคา ></span>
                     </div>
                 </a>
                 <a href="#" class="block rounded-xl overflow-hidden shadow-sm hover:shadow-md transition relative group h-40">
                     <img src="https://images.unsplash.com/photo-1517400508447-f8dd518b86db?w=600" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                     <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-transparent p-5 flex flex-col justify-center">
                         <span class="text-blue-400 font-bold text-xs uppercase tracking-wider mb-1">Full Service</span>
                         <h3 class="text-white font-bold text-lg leading-tight">การบินไทย<br>บินสบาย กระเป๋าฟรี</h3>
                         <span class="text-white text-xs mt-2 underline">เช็คราคา ></span>
                     </div>
                 </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Popular Domestic Routes -->
                <div class="lg:col-span-1">
                    <h2 class="font-heading font-bold text-xl text-dark mb-4 flex items-center gap-2">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a9/Flag_of_Thailand.svg/255px-Flag_of_Thailand.svg.png" class="w-6 h-4 shadow-sm border border-gray-100"> 
                        เส้นทางในประเทศยอดฮิต
                    </h2>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 divide-y divide-gray-100">
                        <div class="p-4 flex justify-between items-center hover:bg-gray-50 cursor-pointer">
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">กรุงเทพฯ - เชียงใหม่</h4>
                                <span class="text-xs text-gray-400">ไป-กลับ</span>
                            </div>
                            <div class="text-right">
                                <div class="text-secondary font-bold">฿1,200</div>
                                <span class="text-[10px] text-gray-400">เริ่มต้น</span>
                            </div>
                        </div>
                        <div class="p-4 flex justify-between items-center hover:bg-gray-50 cursor-pointer">
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">กรุงเทพฯ - ภูเก็ต</h4>
                                <span class="text-xs text-gray-400">ไป-กลับ</span>
                            </div>
                            <div class="text-right">
                                <div class="text-secondary font-bold">฿1,500</div>
                                <span class="text-[10px] text-gray-400">เริ่มต้น</span>
                            </div>
                        </div>
                        <div class="p-4 flex justify-between items-center hover:bg-gray-50 cursor-pointer">
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">กรุงเทพฯ - หาดใหญ่</h4>
                                <span class="text-xs text-gray-400">ไป-กลับ</span>
                            </div>
                            <div class="text-right">
                                <div class="text-secondary font-bold">฿1,100</div>
                                <span class="text-[10px] text-gray-400">เริ่มต้น</span>
                            </div>
                        </div>
                        <div class="p-4 flex justify-between items-center hover:bg-gray-50 cursor-pointer">
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">กรุงเทพฯ - ขอนแก่น</h4>
                                <span class="text-xs text-gray-400">ไป-กลับ</span>
                            </div>
                            <div class="text-right">
                                <div class="text-secondary font-bold">฿990</div>
                                <span class="text-[10px] text-gray-400">เริ่มต้น</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Popular International Routes -->
                <div class="lg:col-span-2">
                    <h2 class="font-heading font-bold text-xl text-dark mb-4 flex items-center gap-2">
                        <i class="ph ph-globe-hemisphere-east text-primary text-xl"></i>
                        เส้นทางต่างประเทศราคาสุดคุ้ม
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Route Card -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4 hover:shadow-md transition cursor-pointer">
                            <img src="https://images.unsplash.com/photo-1526481280693-3bfa7568e0f3?w=100" class="w-16 h-16 rounded-lg object-cover">
                            <div class="flex-1">
                                <h4 class="font-bold text-dark">กรุงเทพฯ - โตเกียว</h4>
                                <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                                    <span class="bg-green-100 text-green-700 px-1.5 rounded">บินตรง</span>
                                    <span>5 ชม. 30 นาที</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-secondary">฿12,500</div>
                                <span class="text-xs text-gray-400">ไป-กลับ</span>
                            </div>
                        </div>
                         <!-- Route Card -->
                         <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4 hover:shadow-md transition cursor-pointer">
                            <img src="https://images.unsplash.com/photo-1555400038-63f5ba517a47?w=100" class="w-16 h-16 rounded-lg object-cover">
                            <div class="flex-1">
                                <h4 class="font-bold text-dark">กรุงเทพฯ - บาหลี</h4>
                                <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                                    <span class="bg-green-100 text-green-700 px-1.5 rounded">บินตรง</span>
                                    <span>4 ชม. 15 นาที</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-secondary">฿6,900</div>
                                <span class="text-xs text-gray-400">ไป-กลับ</span>
                            </div>
                        </div>
                         <!-- Route Card -->
                         <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4 hover:shadow-md transition cursor-pointer">
                            <img src="https://images.unsplash.com/photo-1480796927426-f609979314bd?w=100" class="w-16 h-16 rounded-lg object-cover">
                            <div class="flex-1">
                                <h4 class="font-bold text-dark">กรุงเทพฯ - สิงคโปร์</h4>
                                <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                                    <span class="bg-green-100 text-green-700 px-1.5 rounded">บินตรง</span>
                                    <span>2 ชม. 20 นาที</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-secondary">฿4,500</div>
                                <span class="text-xs text-gray-400">ไป-กลับ</span>
                            </div>
                        </div>
                         <!-- Route Card -->
                         <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4 hover:shadow-md transition cursor-pointer">
                            <img src="https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?w=100" class="w-16 h-16 rounded-lg object-cover">
                            <div class="flex-1">
                                <h4 class="font-bold text-dark">กรุงเทพฯ - ลอนดอน</h4>
                                <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                                    <span class="bg-gray-100 text-gray-600 px-1.5 rounded">1 จุดพัก</span>
                                    <span>13 ชม.</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-secondary">฿28,900</div>
                                <span class="text-xs text-gray-400">ไป-กลับ</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 text-center bg-gray-100 rounded-lg p-6 border border-gray-200">
                <h3 class="font-bold text-gray-600 mb-2">สายการบินพันธมิตร</h3>
                <div class="flex justify-center gap-8 grayscale opacity-50 flex-wrap">
                    <!-- Placeholders for Airline Logos -->
                    <span class="text-xl font-black">AirAsia</span>
                    <span class="text-xl font-black">Thai Airways</span>
                    <span class="text-xl font-black">Nok Air</span>
                    <span class="text-xl font-black">VietJet</span>
                    <span class="text-xl font-black">Bangkok Airways</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer (Shared) -->
    <footer class="bg-dark text-white pt-12 pb-8 border-t border-gray-700 mt-auto">
        <div class="container mx-auto px-4 text-center">
            <h2 class="font-heading font-bold text-2xl mb-4">คนเดินทาง<span class="text-secondary">.com</span></h2>
            <div class="flex justify-center gap-6 mb-6 text-gray-400">
                <a href="#" class="hover:text-white">เกี่ยวกับเรา</a>
                <a href="#" class="hover:text-white">ติดต่อโฆษณา</a>
                <a href="#" class="hover:text-white">นโยบายความเป็นส่วนตัว</a>
            </div>
            <p class="text-gray-500 text-sm">&copy; 2026 KonDernTang.com - เพื่อนเดินทางของคุณ</p>
        </div>
    </footer>

    <!-- Script for Navigation Interaction -->
    <script>
        function navigateTo(pageName) {
            // Scroll to top
            window.scrollTo(0, 0);
            
            // Hide all views
            const views = document.querySelectorAll('.page-view');
            views.forEach(view => {
                view.classList.remove('active');
            });

            // Show target view
            const target = document.getElementById('view-' + pageName);
            if (target) {
                target.classList.add('active');
            }
        }
    </script>
</body>
</html>