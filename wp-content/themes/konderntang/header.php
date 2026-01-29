<?php
/**
 * Theme header template.
 *
 * @package TailPress
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <?php wp_head(); ?>
</head>
<body <?php body_class('bg-gray-50 text-gray-800 flex flex-col min-h-screen'); ?> style="font-family: 'Sarabun', sans-serif;">
<?php do_action('tailpress_site_before'); ?>

<div id="page" class="min-h-screen flex flex-col">
    <?php do_action('tailpress_header'); ?>

    <!-- Navigation Bar -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="<?php echo esc_url(home_url('/')); ?>" class="flex-shrink-0 flex items-center gap-2">
                    <?php if (has_custom_logo()): ?>
                        <?php the_custom_logo(); ?>
                    <?php else: ?>
                        <span class="text-2xl font-heading font-bold text-primary"><?php bloginfo('name'); ?></span>
                    <?php endif; ?>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden xl:flex space-x-1 items-center font-heading font-medium text-gray-600">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="px-3 py-2 text-primary hover:bg-blue-50 rounded-md transition">หน้าแรก</a>
                    <?php
                    // Get category by slug
                    $domestic_cat = get_category_by_slug('domestic');
                    $international_cat = get_category_by_slug('international');
                    $hotels_cat = get_category_by_slug('hotels');
                    $flights_cat = get_category_by_slug('flights');
                    ?>
                    <?php if ($domestic_cat): ?>
                        <a href="<?php echo esc_url(get_category_link($domestic_cat->term_id)); ?>" class="px-3 py-2 hover:text-primary hover:bg-gray-50 rounded-md transition">เที่ยวทั่วไทย</a>
                    <?php endif; ?>
                    <?php if ($international_cat): ?>
                        <a href="<?php echo esc_url(get_category_link($international_cat->term_id)); ?>" class="px-3 py-2 hover:text-primary hover:bg-gray-50 rounded-md transition">เที่ยวต่างประเทศ</a>
                    <?php endif; ?>
                    <?php if ($hotels_cat): ?>
                        <a href="<?php echo esc_url(get_category_link($hotels_cat->term_id)); ?>" class="px-3 py-2 hover:text-primary hover:bg-gray-50 rounded-md transition">ที่พักแนะนำ</a>
                    <?php endif; ?>
                    <?php if ($flights_cat): ?>
                        <a href="<?php echo esc_url(get_category_link($flights_cat->term_id)); ?>" class="px-3 py-2 hover:text-primary hover:bg-gray-50 rounded-md transition">จองตั๋วเครื่องบิน</a>
                    <?php endif; ?>
                    <a href="#" class="px-3 py-2 hover:text-primary hover:bg-gray-50 rounded-md transition">คู่มือเดินทาง</a>
                    <a href="#" class="ml-2 px-4 py-2 bg-secondary text-white rounded-full hover:bg-orange-600 transition shadow-sm flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 256 256" class="w-4 h-4"><path d="M184,128a56,56,0,1,1-56-56A56.06,56.06,0,0,1,184,128Zm-56-40a40,40,0,1,0,40,40A40,40,0,0,0,128,88Zm72,0a16,16,0,1,1-16-16A16,16,0,0,1,200,88Zm-144,0a16,16,0,1,1-16-16A16,16,0,0,1,56,88Zm144,80a16,16,0,1,1-16-16A16,16,0,0,1,200,168Zm-144,0a16,16,0,1,1-16-16A16,16,0,0,1,56,168Zm72,0a16,16,0,1,1-16-16A16,16,0,0,1,128,168Z"></path></svg>
                        โปรโมชั่น
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button type="button" aria-label="Toggle navigation" id="primary-menu-toggle" class="xl:hidden text-gray-600 hover:text-primary p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden xl:hidden bg-white border-t border-gray-100 px-4 pt-2 pb-4 space-y-1 shadow-lg font-heading">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="block w-full text-left px-3 py-2 text-primary font-medium bg-blue-50 rounded-md">หน้าแรก</a>
            <?php if ($domestic_cat): ?>
                <a href="<?php echo esc_url(get_category_link($domestic_cat->term_id)); ?>" class="block w-full text-left px-3 py-2 text-gray-600 hover:bg-gray-50 rounded-md">เที่ยวทั่วไทย</a>
            <?php endif; ?>
            <?php if ($international_cat): ?>
                <a href="<?php echo esc_url(get_category_link($international_cat->term_id)); ?>" class="block w-full text-left px-3 py-2 text-gray-600 hover:bg-gray-50 rounded-md">เที่ยวต่างประเทศ</a>
            <?php endif; ?>
            <?php if ($hotels_cat): ?>
                <a href="<?php echo esc_url(get_category_link($hotels_cat->term_id)); ?>" class="block w-full text-left px-3 py-2 text-gray-600 hover:bg-gray-50 rounded-md">ที่พักแนะนำ</a>
            <?php endif; ?>
            <?php if ($flights_cat): ?>
                <a href="<?php echo esc_url(get_category_link($flights_cat->term_id)); ?>" class="block w-full text-left px-3 py-2 text-gray-600 hover:bg-gray-50 rounded-md">จองตั๋วเครื่องบิน</a>
            <?php endif; ?>
        </div>
    </nav>

    <div id="content" class="site-content grow">
        <?php do_action('tailpress_content_start'); ?>
        <main>
