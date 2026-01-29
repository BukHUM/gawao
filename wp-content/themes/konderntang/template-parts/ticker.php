<?php
/**
 * Ticker Template Part
 *
 * @package KonDernTang
 */
?>
<!-- Ticker -->
<div class="bg-primary text-white py-2 overflow-hidden relative">
    <div class="container mx-auto px-4 flex items-center">
        <span class="font-heading font-bold bg-white text-primary px-2 py-0.5 rounded text-sm mr-3 whitespace-nowrap">🔥 อัปเดต</span>
        <div class="marquee-container overflow-hidden w-full relative h-6">
            <span class="absolute whitespace-nowrap animate-marquee font-medium text-sm pt-0.5">
                <?php
                // Get latest posts for ticker
                $ticker_posts = new WP_Query([
                    'posts_per_page' => 5,
                    'post_status' => 'publish',
                    'orderby' => 'date',
                    'order' => 'DESC'
                ]);
                
                $ticker_items = [];
                if ($ticker_posts->have_posts()) {
                    while ($ticker_posts->have_posts()) {
                        $ticker_posts->the_post();
                        $ticker_items[] = get_the_title();
                    }
                    wp_reset_postdata();
                }
                
                if (empty($ticker_items)) {
                    $ticker_items = [
                        'ญี่ปุ่นประกาศฟรีวีซ่าถาวรสำหรับคนไทยแล้ว',
                        'โปรฯ AirAsia 0 บาท จองด่วนคืนนี้',
                        'โรงแรมพัทยาลด 50%'
                    ];
                }
                
                echo '• ' . implode(' • ', array_map('esc_html', $ticker_items)) . ' •';
                ?>
            </span>
        </div>
    </div>
</div>
