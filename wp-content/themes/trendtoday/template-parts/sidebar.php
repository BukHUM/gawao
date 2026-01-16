<?php
/**
 * Template part for displaying sidebar
 *
 * @package TrendToday
 * @since 1.0.0
 */
?>

<aside class="lg:w-1/3 space-y-8 sticky-sidebar" aria-label="Sidebar content">
    <?php 
    // Display widgets from sidebar-1 if any are active
    if ( is_active_sidebar( 'sidebar-1' ) ) {
        dynamic_sidebar( 'sidebar-1' );
    } else {
        // Only show default sidebar content if no widgets are active
        ?>
        
        <!-- Default sidebar content (only shown when no widgets) -->
        
        <!-- Popular Posts -->
    <?php
    $popular_query = trendtoday_get_popular_posts( 4, 'views' );
    
    if ( $popular_query->have_posts() ) :
        ?>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
            <h3 class="font-bold text-xl mb-5 flex items-center gap-2">
                <i class="fas fa-fire text-accent"></i>
                <?php _e( 'ยอดนิยม', 'trendtoday' ); ?>
            </h3>
            <div class="space-y-4" role="list" aria-label="<?php _e( 'Popular articles', 'trendtoday' ); ?>">
                <?php
                // Store current post to restore later
                global $post;
                $current_post = $post;
                
                $index = 0;
                while ( $popular_query->have_posts() ) :
                    $popular_query->the_post();
                    $index++;
                    
                    // Get post data directly from query post
                    $post_obj = $popular_query->post;
                    $post_title = $post_obj->post_title;
                    $post_permalink = get_permalink( $post_obj->ID );
                    $post_date = get_post_time( 'U', false, $post_obj->ID );
                    $thumbnail_id = get_post_thumbnail_id( $post_obj->ID );
                    
                    // Skip if no title
                    if ( empty( $post_title ) ) {
                        continue;
                    }
                    ?>
                    <a href="<?php echo esc_url( $post_permalink ); ?>" 
                       class="popular-item flex gap-4 items-start group p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200"
                       role="listitem"
                       aria-label="<?php echo esc_attr( sprintf( __( 'Popular article %d: %s', 'trendtoday' ), $index, $post_title ) ); ?>">
                        <span class="popular-number text-2xl font-bold text-gray-200 group-hover:text-accent transition-all duration-300 flex-shrink-0">
                            <?php echo str_pad( $index, 2, '0', STR_PAD_LEFT ); ?>
                        </span>
                        <div class="flex-grow">
                            <h4 class="text-sm font-medium text-gray-700 group-hover:text-accent transition-colors line-clamp-2 leading-snug">
                                <?php echo esc_html( $post_title ); ?>
                            </h4>
                        </div>
                    </a>
                <?php
                endwhile;
                
                // Restore original post
                $post = $current_post;
                wp_reset_postdata();
                ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Newsletter -->
    <div class="bg-gradient-to-br from-gray-900 to-gray-800 text-white p-6 rounded-xl relative overflow-hidden shadow-lg">
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-2">
                <i class="fas fa-envelope text-accent text-xl"></i>
                <h3 class="font-bold text-xl"><?php _e( 'ไม่พลาดทุกเทรนด์', 'trendtoday' ); ?></h3>
            </div>
            <p class="text-gray-300 text-sm mb-4 leading-relaxed">
                <?php _e( 'สมัครรับข่าวสารสรุปประจำวันส่งตรงถึงอีเมลของคุณ', 'trendtoday' ); ?>
            </p>
            <form class="space-y-3" onsubmit="event.preventDefault(); handleNewsletterSubmit(event);" aria-label="<?php _e( 'Newsletter subscription', 'trendtoday' ); ?>">
                <input type="email" 
                       placeholder="<?php _e( 'ใส่อีเมลของคุณ', 'trendtoday' ); ?>" 
                       required
                       aria-label="<?php _e( 'Email address', 'trendtoday' ); ?>"
                       class="newsletter-input w-full px-4 py-3 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-accent transition-all duration-200"
                       id="newsletter-email">
                <button type="submit"
                        class="w-full bg-accent hover:bg-orange-600 text-white font-bold py-3 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-paper-plane mr-2"></i><?php _e( 'ติดตาม', 'trendtoday' ); ?>
                </button>
            </form>
        </div>
        <!-- Decorative elements -->
        <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute -top-10 -left-10 w-24 h-24 bg-accent/20 rounded-full blur-xl"></div>
    </div>
    <?php } // End check for is_active_sidebar ?>

</aside>
