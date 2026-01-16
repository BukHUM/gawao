<?php
/**
 * The template for displaying archive pages
 *
 * @package TrendToday
 * @since 1.0.0
 */

get_header();
?>

<!-- Category Header -->
<header class="bg-white border-b border-gray-200 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="text-sm text-gray-500 mb-2">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-accent"><?php _e( 'หน้าแรก', 'trendtoday' ); ?></a>
                    <i class="fas fa-chevron-right text-xs mx-1"></i>
                    <span><?php echo esc_html( get_the_archive_title() ); ?></span>
                </div>
                <h1 class="text-4xl font-bold text-gray-900">
                    <?php
                    if ( is_category() ) {
                        echo esc_html( single_cat_title( '', false ) );
                    } elseif ( is_tag() ) {
                        echo esc_html( single_tag_title( '', false ) );
                    } elseif ( is_author() ) {
                        the_author();
                    } elseif ( is_date() ) {
                        echo get_the_date();
                    } else {
                        _e( 'Archive', 'trendtoday' );
                    }
                    ?>
                    <span class="text-accent"><?php _e( 'Update', 'trendtoday' ); ?></span>
                </h1>
                <?php
                $description = get_the_archive_description();
                if ( $description ) :
                    ?>
                    <p class="text-gray-500 mt-2 font-light"><?php echo wp_kses_post( $description ); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<main id="main-content" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">

    <!-- Top Story of Category -->
    <?php
    $top_story = new WP_Query( array(
        'posts_per_page' => 1,
        'post_type'      => 'post',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ) );

    if ( $top_story->have_posts() ) :
        $top_story->the_post();
        ?>
        <section class="mb-12">
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 flex flex-col md:flex-row group cursor-pointer hover:shadow-md transition"
                 onclick="window.location.href='<?php the_permalink(); ?>'">
                <div class="md:w-3/5 relative overflow-hidden h-64 md:h-auto">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'trendtoday-hero', array(
                            'class' => 'w-full h-full object-cover transition duration-700 group-hover:scale-105',
                        ) ); ?>
                    <?php endif; ?>
                    <span class="absolute top-4 left-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded">
                        <?php _e( 'HOT Topic', 'trendtoday' ); ?>
                    </span>
                </div>
                <div class="p-6 md:p-8 md:w-2/5 flex flex-col justify-center">
                    <?php
                    $categories = get_the_category();
                    if ( ! empty( $categories ) ) :
                        ?>
                        <div class="text-accent font-bold text-sm mb-2"><?php echo esc_html( $categories[0]->name ); ?></div>
                    <?php endif; ?>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4 leading-tight group-hover:text-accent transition">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <?php if ( has_excerpt() ) : ?>
                        <p class="text-gray-500 mb-6 line-clamp-3 font-light">
                            <?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
                        </p>
                    <?php endif; ?>
                    <div class="flex items-center gap-3 text-sm text-gray-400 mt-auto">
                        <?php echo get_avatar( get_the_author_meta( 'ID' ), 32, '', '', array( 'class' => 'rounded-full' ) ); ?>
                        <span><?php _e( 'โดย', 'trendtoday' ); ?> <?php the_author(); ?></span>
                        <span>•</span>
                        <span><?php echo human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) . ' ที่แล้ว'; ?></span>
                    </div>
                </div>
            </div>
        </section>
        <?php
        wp_reset_postdata();
    endif;
    ?>

    <div class="flex flex-col lg:flex-row gap-10">

        <!-- Article List -->
        <div class="lg:w-3/4">
            <h3 class="font-bold text-xl mb-6 flex items-center gap-2">
                <i class="far fa-newspaper text-accent"></i> <?php _e( 'ล่าสุด', 'trendtoday' ); ?>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php
                if ( have_posts() ) :
                    while ( have_posts() ) :
                        the_post();
                        get_template_part( 'template-parts/news-card' );
                    endwhile;
                else :
                    get_template_part( 'template-parts/content', 'none' );
                endif;
                ?>
            </div>

            <!-- Pagination -->
            <?php get_template_part( 'template-parts/pagination' ); ?>
        </div>

        <!-- Sidebar -->
        <?php get_template_part( 'template-parts/sidebar' ); ?>

    </div>
</main>

<?php
get_footer();
?>
