<?php
/**
 * Home Page Template
 *
 * @package KonDernTang
 */

get_header();
?>

<!-- Hero Section -->
<?php get_template_part('template-parts/hero-section'); ?>

<!-- Ticker -->
<?php get_template_part('template-parts/ticker'); ?>

<!-- Home Content -->
<main class="container mx-auto px-4 py-12">
    <?php
    // Get domestic category
    $domestic_cat = get_category_by_slug('domestic');
    if (!$domestic_cat) {
        // Try to get any category
        $categories = get_categories(['number' => 1]);
        $domestic_cat = (!empty($categories) && isset($categories[0])) ? $categories[0] : null;
    }
    
    if ($domestic_cat):
        $domestic_posts = new WP_Query([
            'category__in' => [$domestic_cat->term_id],
            'posts_per_page' => 4,
            'post_status' => 'publish'
        ]);
    ?>
    <!-- Section 1: เที่ยวทั่วไทย -->
    <div class="flex justify-between items-end mb-8 border-l-4 border-primary pl-4">
        <div>
            <h2 class="text-3xl font-heading font-bold text-dark">เที่ยวทั่วไทย</h2>
            <p class="text-gray-500 mt-1">หลงรักเมืองไทย ไปกี่ครั้งก็ไม่เบื่อ</p>
        </div>
        <a href="<?php echo esc_url(get_category_link($domestic_cat->term_id)); ?>" class="text-primary hover:text-blue-700 font-medium hidden md:block">
            ดูทั้งหมด 
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 inline-block">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-16">
        <?php if ($domestic_posts->have_posts()): ?>
            <?php while ($domestic_posts->have_posts()): $domestic_posts->the_post(); ?>
                <article class="bg-white rounded-xl shadow-sm hover:shadow-lg transition overflow-hidden group border border-gray-100 cursor-pointer">
                    <a href="<?php the_permalink(); ?>" class="block">
                        <div class="relative h-48 overflow-hidden">
                            <?php if (has_post_thumbnail()): ?>
                                <?php the_post_thumbnail('medium', ['class' => 'w-full h-full object-cover transition duration-500 group-hover:scale-110']); ?>
                            <?php else: ?>
                                <img src="https://images.unsplash.com/photo-1596422846543-75c6a197f070?auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover transition duration-500 group-hover:scale-110" alt="<?php the_title(); ?>">
                            <?php endif; ?>
                            <?php
                            $categories = get_the_category();
                            if (!empty($categories) && isset($categories[0])):
                                $category = $categories[0];
                            ?>
                                <span class="absolute top-3 left-3 bg-white/90 text-dark text-xs font-bold px-2 py-1 rounded backdrop-blur-sm"><?php echo esc_html($category->name); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="p-5">
                            <h3 class="font-heading font-semibold text-lg text-dark mb-2 leading-snug group-hover:text-primary transition"><?php the_title(); ?></h3>
                            <div class="flex items-center gap-2 text-gray-400 text-xs mt-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                <?php echo get_the_date('j M Y'); ?>
                            </div>
                        </div>
                    </a>
                </article>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <!-- Affiliate Banner -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-400 rounded-2xl p-8 mb-16 text-center text-white shadow-xl">
        <h2 class="text-3xl font-heading font-bold mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 256 256" class="w-8 h-8 inline-block mr-2"><path d="M232,80V192a16,16,0,0,1-16,16H40a16,16,0,0,1-16-16V80A16,16,0,0,1,40,64H216A16,16,0,0,1,232,80Z"></path></svg>
            หาที่พักราคาดีที่สุด?
        </h2>
        <p class="mb-4">จองกับเราถูกกว่าจองเอง ดีลพิเศษสำหรับคนเดินทาง</p>
        <?php
        $hotels_cat = get_category_by_slug('hotels');
        if ($hotels_cat):
        ?>
            <a href="<?php echo esc_url(get_category_link($hotels_cat->term_id)); ?>" class="bg-secondary hover:bg-orange-600 text-white font-bold py-2 px-6 rounded-lg shadow-lg inline-block">
                ค้นหาที่พัก
            </a>
        <?php else: ?>
            <a href="#" class="bg-secondary hover:bg-orange-600 text-white font-bold py-2 px-6 rounded-lg shadow-lg inline-block">
                ค้นหาที่พัก
            </a>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
