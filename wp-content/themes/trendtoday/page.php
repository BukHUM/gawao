<?php
/**
 * The template for displaying static pages (e.g. Privacy Policy, About)
 * Uses same layout as single post: full content + sidebar for consistency.
 *
 * @package TrendToday
 * @since 1.0.0
 */

get_header();
?>

<!-- Progress Bar (Reading Progress) -->
<div class="h-1 bg-gray-200 w-full fixed top-16 z-40">
    <div class="h-full bg-accent w-0 transition-all duration-300" id="reading-progress"></div>
</div>

<main id="main-content" class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">

    <!-- Breadcrumb -->
    <?php get_template_part( 'template-parts/breadcrumb' ); ?>

    <?php $show_sidebar_single = ( get_option( 'trendtoday_sidebar_single_page_enabled', '1' ) === '1' ); ?>
    <div class="flex flex-col lg:flex-row gap-10">

        <!-- Page Content (white card like sidebar widget) -->
        <article class="<?php echo $show_sidebar_single ? 'lg:w-2/3' : 'lg:w-full'; ?> overflow-hidden bg-white p-6 sm:p-8 rounded-xl shadow-sm border border-gray-100">

            <?php
            while ( have_posts() ) :
                the_post();
                ?>

                <!-- Page Header -->
                <header class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-gray-500 text-sm">
                            <i class="far fa-calendar-alt mr-1"></i>
                            <?php
                            $updated = get_the_modified_date();
                            if ( get_the_modified_date() !== get_the_date() ) {
                                printf( __( 'อัปเดตเมื่อ %s', 'trendtoday' ), esc_html( $updated ) );
                            } else {
                                printf( __( 'เผยแพร่เมื่อ %s', 'trendtoday' ), esc_html( get_the_date() ) );
                            }
                            ?>
                        </span>
                    </div>

                    <h1 class="text-xl md:text-3xl lg:text-4xl font-bold text-gray-900 leading-tight mb-6">
                        <?php the_title(); ?>
                    </h1>

                    <?php
                    if ( get_option( 'trendtoday_social_show_on_page', '1' ) === '1' ) :
                        $display_positions = get_option( 'trendtoday_social_display_positions', array( 'single_bottom' ) );
                        if ( ! is_array( $display_positions ) ) {
                            $display_positions = array( 'single_bottom' );
                        }
                        if ( in_array( 'single_top', $display_positions ) ) :
                    ?>
                        <div class="mb-6">
                            <?php get_template_part( 'template-parts/social-share' ); ?>
                        </div>
                    <?php endif; endif; ?>

                    <?php if ( has_excerpt() ) : ?>
                        <p class="text-xl text-gray-600 leading-relaxed font-light mb-6 border-l-4 border-accent pl-4">
                            <?php the_excerpt(); ?>
                        </p>
                    <?php endif; ?>
                </header>

                <!-- Featured Image -->
                <?php if ( has_post_thumbnail() ) : ?>
                    <figure class="mb-8 rounded-xl overflow-hidden shadow-lg">
                        <?php the_post_thumbnail( 'trendtoday-hero', array( 'class' => 'w-full h-auto object-cover' ) ); ?>
                        <?php if ( get_the_post_thumbnail_caption() ) : ?>
                            <figcaption class="text-center text-sm text-gray-500 mt-2 italic">
                                <?php the_post_thumbnail_caption(); ?>
                            </figcaption>
                        <?php endif; ?>
                    </figure>
                <?php endif; ?>

                <?php
                $toc_enabled = get_option( 'trendtoday_toc_enabled', '1' );
                $toc_position = get_option( 'trendtoday_toc_position', 'top' );
                if ( $toc_enabled === '1' && $toc_position === 'top' ) :
                ?>
                    <div class="mb-8">
                        <?php get_template_part( 'template-parts/table-of-contents' ); ?>
                    </div>
                <?php endif; ?>

                <!-- Page Body -->
                <div class="prose prose-lg max-w-none text-gray-800 leading-relaxed overflow-hidden" id="article-content" data-toc-content="true">
                    <div class="trendtoday-article-content">
                    <?php
                    the_content();

                    wp_link_pages( array(
                        'before' => '<div class="page-links mt-8 pt-6 border-t border-gray-200">' . __( 'Pages:', 'trendtoday' ),
                        'after'  => '</div>',
                    ) );
                    ?>
                    </div>
                </div>

                <?php
                if ( get_option( 'trendtoday_social_show_on_page', '1' ) === '1' ) :
                    $display_positions_bottom = get_option( 'trendtoday_social_display_positions', array( 'single_bottom' ) );
                    if ( is_array( $display_positions_bottom ) && in_array( 'single_bottom', $display_positions_bottom ) ) :
                    ?>
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <?php get_template_part( 'template-parts/social-share' ); ?>
                    </div>
                <?php endif; endif; ?>

                <?php if ( is_active_sidebar( 'after-content' ) ) : ?>
                    <div class="mt-12">
                        <?php dynamic_sidebar( 'after-content' ); ?>
                    </div>
                <?php endif; ?>

                <?php
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;
                ?>

            <?php endwhile; ?>

        </article>

        <?php if ( $show_sidebar_single ) : ?>
            <!-- Sidebar (same as single post for consistency) -->
            <?php get_template_part( 'template-parts/sidebar-single' ); ?>
        <?php endif; ?>

    </div>
</main>

<script>
// Reading Progress Bar (same as single.php)
(function() {
    const progressBar = document.getElementById('reading-progress');
    if (!progressBar) return;

    window.addEventListener('scroll', function() {
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const scrollPercent = (scrollTop / (documentHeight - windowHeight)) * 100;
        progressBar.style.width = scrollPercent + '%';
    });
})();
</script>

<?php
get_footer();
