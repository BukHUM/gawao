<?php
/**
 * Template Name: RankMath
 * Template for Rank Math HTML Sitemap page. Clean layout with collapsible sections.
 *
 * @package TrendToday
 * @since 1.0.0
 */

get_header();
?>

<main id="main-content" class="trendtoday-sitemap-page flex-grow max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">

    <?php get_template_part( 'template-parts/breadcrumb' ); ?>

    <div class="trendtoday-sitemap-wrapper bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <?php
        while ( have_posts() ) :
            the_post();
            ?>
            <header class="trendtoday-sitemap-header px-6 sm:px-8 pt-8 pb-4 border-b border-gray-100">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                    <?php the_title(); ?>
                </h1>
                <?php if ( has_excerpt() ) : ?>
                    <p class="text-gray-600 text-base max-w-2xl mb-2">
                        <?php the_excerpt(); ?>
                    </p>
                <?php endif; ?>
                <p class="text-gray-500 text-sm max-w-2xl">
                    <?php _e( 'หน้านี้รวมลิงก์เนื้อหาหลักของเว็บไซต์ คลิกหัวข้อแต่ละส่วนเพื่อย่อ/ขยาย', 'trendtoday' ); ?>
                </p>
            </header>

            <div class="trendtoday-sitemap-content px-6 sm:px-8 py-6">
                <div class="trendtoday-article-content trendtoday-sitemap-lists">
                    <?php the_content(); ?>
                </div>
            </div>
            <?php
        endwhile;
        ?>
    </div>

    <p class="trendtoday-sitemap-back mt-6 text-center">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-accent hover:underline">
                            <?php _e( 'กลับหน้าแรก', 'trendtoday' ); ?>
        </a>
    </p>
</main>

<?php get_footer(); ?>
