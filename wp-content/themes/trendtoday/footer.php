<?php
/**
 * The footer template file
 *
 * @package TrendToday
 * @since 1.0.0
 */
?>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12 pt-12 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-0 md:gap-8 mb-8">
                <!-- Footer Widget 1 -->
                <div class="col-span-1 md:col-span-1 border-b border-gray-100 md:border-none pb-4 md:pb-0 mb-4 md:mb-0">
                    <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                        <?php dynamic_sidebar( 'footer-1' ); ?>
                    <?php else : ?>
                        <!-- Default: Brand Column -->
                        <div class="flex items-center gap-2 mb-4">
                            <?php 
                            $theme_logo_id = get_option( 'trendtoday_logo', '' );
                            $theme_logo_url = $theme_logo_id ? wp_get_attachment_image_url( $theme_logo_id, 'full' ) : '';
                            
                            if ( $theme_logo_url ) : 
                                // Use theme settings logo
                                ?>
                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php bloginfo( 'name' ); ?>">
                                    <img src="<?php echo esc_url( $theme_logo_url ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="h-8 w-auto" />
                                </a>
                            <?php elseif ( has_custom_logo() ) : ?>
                                <?php the_custom_logo(); ?>
                            <?php else : ?>
                                <div class="w-6 h-6 bg-black text-white flex items-center justify-center rounded font-bold text-sm">
                                    T
                                </div>
                                <span class="font-bold text-xl tracking-tight text-gray-900"><?php bloginfo( 'name' ); ?></span>
                            <?php endif; ?>
                        </div>
                        <p class="text-gray-500 text-sm leading-relaxed">
                            <?php bloginfo( 'description' ); ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Footer Widget 2 -->
                <div class="border-b border-gray-100 md:border-none">
                    <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                        <?php dynamic_sidebar( 'footer-2' ); ?>
                    <?php else : ?>
                        <!-- Default: Category Column -->
                        <button class="flex justify-between items-center w-full py-2 md:py-0 text-left md:cursor-default"
                                onclick="toggleFooter(this)">
                            <h4 class="font-bold text-gray-900 md:mb-4"><?php _e( 'หมวดหมู่', 'trendtoday' ); ?></h4>
                            <i class="fas fa-chevron-down text-gray-400 text-xs md:hidden transition-transform duration-300 transform"></i>
                        </button>
                        <ul class="footer-links space-y-2 text-sm text-gray-500 hidden md:block pb-4 md:pb-0">
                            <?php
                            wp_list_categories( array(
                                'title_li' => '',
                                'number'   => 5,
                            ) );
                            ?>
                        </ul>
                    <?php endif; ?>
                </div>

                <!-- Footer Widget 3 -->
                <div class="border-b border-gray-100 md:border-none">
                    <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                        <?php dynamic_sidebar( 'footer-3' ); ?>
                    <?php else : ?>
                        <!-- Default: About Column -->
                        <button class="flex justify-between items-center w-full py-2 md:py-0 text-left md:cursor-default"
                                onclick="toggleFooter(this)">
                            <h4 class="font-bold text-gray-900 md:mb-4"><?php _e( 'เกี่ยวกับเรา', 'trendtoday' ); ?></h4>
                            <i class="fas fa-chevron-down text-gray-400 text-xs md:hidden transition-transform duration-300 transform"></i>
                        </button>
                        <?php
                        if ( has_nav_menu( 'footer' ) ) {
                            wp_nav_menu( array(
                                'theme_location' => 'footer',
                                'menu_class'     => 'footer-links space-y-2 text-sm text-gray-500 hidden md:block pb-4 md:pb-0',
                                'container'      => false,
                                'fallback_cb'    => false,
                                'depth'          => 1,
                            ) );
                        } else {
                            // Fallback menu
                            ?>
                            <ul class="footer-links space-y-2 text-sm text-gray-500 hidden md:block pb-4 md:pb-0">
                                <li><a href="#" class="hover:text-accent"><?php _e( 'ติดต่อโฆษณา', 'trendtoday' ); ?></a></li>
                                <li><a href="#" class="hover:text-accent"><?php _e( 'ร่วมงานกับเรา', 'trendtoday' ); ?></a></li>
                                <li><a href="#" class="hover:text-accent"><?php _e( 'นโยบายความเป็นส่วนตัว', 'trendtoday' ); ?></a></li>
                            </ul>
                            <?php
                        }
                        ?>
                    <?php endif; ?>
                </div>

                <!-- Footer Widget 4 -->
                <div class="border-b border-gray-100 md:border-none">
                    <?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
                        <?php dynamic_sidebar( 'footer-4' ); ?>
                    <?php else : ?>
                        <!-- Default: Follow Column -->
                        <button class="flex justify-between items-center w-full py-2 md:py-0 text-left md:cursor-default"
                                onclick="toggleFooter(this)">
                            <h4 class="font-bold text-gray-900 md:mb-4"><?php _e( 'ติดตามเรา', 'trendtoday' ); ?></h4>
                            <i class="fas fa-chevron-down text-gray-400 text-xs md:hidden transition-transform duration-300 transform"></i>
                        </button>
                        <div class="footer-links hidden md:block pb-4 md:pb-0">
                            <div class="flex space-x-4">
                                <a href="#" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-blue-600 hover:text-white transition">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-sky-500 hover:text-white transition">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-pink-600 hover:text-white transition">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="border-t border-gray-100 pt-8 text-center text-sm text-gray-400">
                &copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. <?php _e( 'All rights reserved.', 'trendtoday' ); ?>
            </div>
        </div>
    </footer>

    <?php
    // Floating Social Share Buttons
    if ( is_single() ) {
        $display_positions = get_option( 'trendtoday_social_display_positions', array( 'single_bottom' ) );
        if ( in_array( 'floating', $display_positions ) ) {
            get_template_part( 'template-parts/social-share-floating' );
        }
        
        // Table of Contents - Floating
        $toc_enabled = get_option( 'trendtoday_toc_enabled', '1' );
        $toc_position = get_option( 'trendtoday_toc_position', 'top' );
        if ( $toc_enabled === '1' && $toc_position === 'floating' ) {
            get_template_part( 'template-parts/table-of-contents' );
        }
    }
    
    // Search Modal
    get_template_part( 'template-parts/search-modal' );
    ?>

    <!-- Back to Top Button -->
    <button id="back-to-top" 
            class="fixed bottom-8 right-8 bg-accent text-white p-4 rounded-full shadow-lg hover:bg-orange-600 transition-all duration-300 opacity-0 invisible z-50 hover:scale-110"
            aria-label="<?php _e( 'กลับขึ้นด้านบน', 'trendtoday' ); ?>"
            onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
        <i class="fas fa-arrow-up text-lg"></i>
    </button>

<?php wp_footer(); ?>
</body>
</html>
