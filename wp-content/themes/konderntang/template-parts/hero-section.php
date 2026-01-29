<?php
/**
 * Hero Section Template Part
 *
 * @package KonDernTang
 */

// Get featured post or latest post
$hero_post = null;
$featured_query = new WP_Query([
    'posts_per_page' => 1,
    'meta_key' => 'featured_post',
    'meta_value' => 'yes',
    'post_status' => 'publish'
]);

if ($featured_query->have_posts()) {
    $hero_post = $featured_query->posts[0];
} else {
    $latest_query = new WP_Query([
        'posts_per_page' => 1,
        'post_status' => 'publish'
    ]);
    if ($latest_query->have_posts()) {
        $hero_post = $latest_query->posts[0];
    }
}

if ($hero_post):
    setup_postdata($hero_post);
    $thumbnail_url = get_the_post_thumbnail_url($hero_post->ID, 'full') ?: 'https://images.unsplash.com/photo-1506665531195-3566af2b4dfa?auto=format&fit=crop&w=1600&q=80';
    $categories = get_the_category($hero_post->ID);
    $category_name = (!empty($categories) && isset($categories[0])) ? $categories[0]->name : '';
    ?>
    <header class="relative bg-dark h-[500px] flex items-end overflow-hidden group">
        <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title($hero_post->ID)); ?>" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-80">
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
        <div class="container mx-auto px-4 pb-12 relative z-10">
            <?php if ($category_name): ?>
                <span class="inline-block bg-secondary text-white text-xs font-bold px-3 py-1 rounded-full mb-4 uppercase tracking-wider"><?php echo esc_html($category_name); ?></span>
            <?php endif; ?>
            <h1 class="text-3xl md:text-5xl font-heading font-bold text-white mb-4 leading-tight drop-shadow-md">
                <?php echo esc_html(get_the_title($hero_post->ID)); ?>
            </h1>
            <p class="text-gray-300 text-lg mb-6 max-w-2xl font-light line-clamp-2">
                <?php echo esc_html(wp_trim_words(get_the_excerpt($hero_post->ID) ?: get_the_content($hero_post->ID), 30)); ?>
            </p>
            <a href="<?php echo esc_url(get_permalink($hero_post->ID)); ?>" class="inline-flex items-center gap-2 bg-white text-dark font-heading font-semibold px-6 py-3 rounded-lg hover:bg-gray-100 transition">
                อ่านรีวิวฉบับเต็ม 
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>
    </header>
    <?php
    wp_reset_postdata();
endif;
?>
