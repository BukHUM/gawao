<?php
/**
 * Template part for displaying trending tags
 *
 * @package TrendToday
 * @since 1.0.0
 */

// Get trending tags
$tags = trendtoday_get_trending_tags( 10 );
?>

<?php if ( ! empty( $tags ) ) : ?>
    <div class="bg-white shadow-sm py-3 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <span class="text-accent font-bold whitespace-nowrap text-sm flex items-center">
                    <i class="fas fa-bolt mr-1 animate-pulse"></i>
                    มาแรง:
                </span>
                <div class="flex overflow-x-auto hide-scroll gap-2 pb-1 scroll-smooth" role="list" aria-label="Trending topics">
                    <?php foreach ( $tags as $tag ) : ?>
                        <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"
                           class="trending-tag inline-block bg-gray-100 hover:bg-accent hover:text-white text-gray-700 text-xs px-4 py-2 rounded-full whitespace-nowrap transition-all duration-200 font-medium"
                           role="listitem"
                           aria-label="Trending topic: <?php echo esc_attr( $tag->name ); ?>">
                            #<?php echo esc_html( $tag->name ); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
