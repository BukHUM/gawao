<?php
/**
 * Template part for displaying hero/breaking news section
 *
 * @package TrendToday
 * @since 1.0.0
 */

// Get breaking news post
$breaking_query = trendtoday_get_breaking_news( 1 );

// Fallback to latest post if no breaking news
if ( ! $breaking_query->have_posts() ) {
    $breaking_query = new WP_Query( array(
        'posts_per_page' => 1,
        'post_type'      => 'post',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ) );
}

if ( $breaking_query->have_posts() ) :
    $breaking_query->the_post();
    ?>
    <section class="mb-12" aria-label="Breaking news">
        <article class="relative rounded-2xl overflow-hidden shadow-xl group cursor-pointer h-96 md:h-[500px] transition-all duration-300 hover:shadow-2xl focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2"
                 onclick="window.location.href='<?php the_permalink(); ?>'"
                 onkeypress="if(event.key === 'Enter') window.location.href='<?php the_permalink(); ?>'"
                 role="article"
                 tabindex="0"
                 aria-label="<?php echo esc_attr( get_the_title() ); ?>">
            <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'trendtoday-hero', array(
                    'class' => 'w-full h-full object-cover transition duration-700 group-hover:scale-105',
                    'alt'   => get_the_title(),
                ) ); ?>
            <?php endif; ?>
            <div class="absolute inset-0 hero-overlay"></div>
            <div class="absolute bottom-0 left-0 p-6 md:p-10 w-full md:w-3/4">
                <span class="category-badge bg-accent text-white text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider mb-3 inline-block shadow-lg">
                    <i class="fas fa-fire mr-1"></i>Breaking News
                </span>
                <h1 class="text-2xl md:text-4xl lg:text-5xl font-bold text-white leading-tight mb-3 drop-shadow-lg">
                    <a href="<?php the_permalink(); ?>" class="text-white hover:text-gray-200">
                        <?php the_title(); ?>
                    </a>
                </h1>
                <?php if ( has_excerpt() || get_the_excerpt() ) : ?>
                    <p class="text-gray-200 text-sm md:text-base mb-4 line-clamp-2 drop-shadow-md">
                        <?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
                    </p>
                <?php endif; ?>
                <div class="flex flex-wrap items-center text-gray-300 text-xs md:text-sm gap-4">
                    <span class="flex items-center">
                        <i class="far fa-clock mr-1.5"></i>
                        <?php echo human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) . ' ที่แล้ว'; ?>
                    </span>
                    <span class="flex items-center">
                        <i class="far fa-user mr-1.5"></i>
                        <?php the_author(); ?>
                    </span>
                </div>
            </div>
        </article>
    </section>
    <?php
    wp_reset_postdata();
endif;
?>
