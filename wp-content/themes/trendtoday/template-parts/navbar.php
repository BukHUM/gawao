<?php
/**
 * Template part for displaying navigation bar
 *
 * @package TrendToday
 * @since 1.0.0
 */
?>

<nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm" role="navigation" aria-label="Main navigation">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <?php if ( has_custom_logo() ) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex-shrink-0 flex items-center gap-2" aria-label="<?php bloginfo( 'name' ); ?>">
                        <div class="w-8 h-8 bg-black text-white flex items-center justify-center rounded font-bold text-lg">
                            T
                        </div>
                        <span class="font-bold text-2xl tracking-tight text-gray-900">
                            Trend<span class="text-accent">Today</span>
                        </span>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-6">
                <?php
                // Always use WordPress menu (no fallback)
                wp_nav_menu( array(
                    'theme_location'  => 'primary',
                    'menu_class'      => 'flex items-center space-x-6',
                    'container'       => false,
                    'fallback_cb'     => false,
                    'walker'          => new TrendToday_Walker_Nav_Menu(),
                    'depth'           => 1,
                ) );
                ?>
                <a href="<?php echo esc_url( home_url( '/?s=' ) ); ?>" 
                   class="text-gray-500 hover:text-accent transition-colors duration-200 p-2 rounded-md hover:bg-gray-50"
                   aria-label="<?php _e( 'ค้นหาข่าว', 'trendtoday' ); ?>">
                    <i class="fas fa-search text-lg"></i>
                </a>
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center md:hidden">
                <button onclick="toggleMobileMenu()" 
                        aria-label="Toggle menu" 
                        aria-expanded="false" 
                        id="mobile-menu-button"
                        class="text-gray-500 hover:text-gray-900 focus:outline-none p-2 rounded-md hover:bg-gray-50">
                    <i class="fas fa-bars text-2xl" id="menu-icon"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Panel -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100 w-full shadow-lg" role="menu">
        <?php
        // Always use WordPress menu (no fallback)
        wp_nav_menu( array(
            'theme_location' => 'primary',
            'menu_class'     => 'px-2 pt-2 pb-3 space-y-1 sm:px-3',
            'container'      => false,
            'fallback_cb'    => false,
            'walker'         => new TrendToday_Walker_Nav_Menu_Mobile(),
            'depth'          => 1,
        ) );
        ?>
    </div>
</nav>
