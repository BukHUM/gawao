<?php
/**
 * Template part for displaying breadcrumb navigation
 *
 * @package TrendToday
 * @since 1.0.0
 */

if ( is_front_page() ) {
    return;
}
?>

<nav class="trendtoday-breadcrumb hidden md:block mb-6 text-sm" aria-label="Breadcrumb">
    <ol class="flex flex-wrap items-center gap-x-1 md:gap-x-2 text-gray-500">
        <li class="inline-flex items-center shrink-0">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center gap-1.5 py-1 rounded hover:text-accent transition-colors">
                <i class="fas fa-home text-gray-400" aria-hidden="true"></i>
                <span><?php _e( 'หน้าแรก', 'trendtoday' ); ?></span>
            </a>
        </li>

        <?php if ( is_category() ) : ?>
            <?php
            $cat_title = single_cat_title( '', false );
            $cat_title = strip_tags( $cat_title );
            $cat_title = preg_replace( '/^(หมวดหมู่|Category):\s*/i', '', $cat_title );
            $cat_title = trim( $cat_title );
            ?>
            <li class="inline-flex items-center shrink-0" aria-current="page">
                <span class="trendtoday-breadcrumb-sep"><i class="fas fa-chevron-right" aria-hidden="true"></i></span>
                <span class="trendtoday-breadcrumb-current text-gray-800 font-medium"><?php echo esc_html( $cat_title ); ?></span>
            </li>
        <?php elseif ( is_tag() ) : ?>
            <li class="inline-flex items-center shrink-0" aria-current="page">
                <span class="trendtoday-breadcrumb-sep"><i class="fas fa-chevron-right" aria-hidden="true"></i></span>
                <span class="trendtoday-breadcrumb-current text-gray-800 font-medium"><?php echo esc_html( strip_tags( single_tag_title( '', false ) ) ); ?></span>
            </li>
        <?php elseif ( is_single() ) : ?>
            <?php
            $categories = get_the_category();
            if ( ! empty( $categories ) ) :
                $category = $categories[0];
                $category_name = strip_tags( $category->name );
                $category_name = preg_replace( '/^(หมวดหมู่|Category):\s*/i', '', $category_name );
                $category_name = trim( $category_name );
                ?>
                <li class="inline-flex items-center shrink-0">
                    <span class="trendtoday-breadcrumb-sep"><i class="fas fa-chevron-right" aria-hidden="true"></i></span>
                    <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="py-1 rounded hover:text-accent transition-colors">
                        <?php echo esc_html( $category_name ); ?>
                    </a>
                </li>
            <?php endif; ?>
            <li class="inline-flex items-center min-w-0 flex-1" aria-current="page">
                <span class="trendtoday-breadcrumb-sep"><i class="fas fa-chevron-right" aria-hidden="true"></i></span>
                <span class="trendtoday-breadcrumb-current trendtoday-breadcrumb-title text-gray-800 font-medium"><?php echo esc_html( strip_tags( get_the_title() ) ); ?></span>
            </li>
        <?php elseif ( is_page() ) : ?>
            <li class="inline-flex items-center min-w-0 flex-1" aria-current="page">
                <span class="trendtoday-breadcrumb-sep"><i class="fas fa-chevron-right" aria-hidden="true"></i></span>
                <span class="trendtoday-breadcrumb-current trendtoday-breadcrumb-title text-gray-800 font-medium"><?php echo esc_html( strip_tags( get_the_title() ) ); ?></span>
            </li>
        <?php elseif ( is_search() ) : ?>
            <li class="inline-flex items-center shrink-0" aria-current="page">
                <span class="trendtoday-breadcrumb-sep"><i class="fas fa-chevron-right" aria-hidden="true"></i></span>
                <span class="trendtoday-breadcrumb-current text-gray-800 font-medium"><?php _e( 'ผลการค้นหา', 'trendtoday' ); ?></span>
            </li>
        <?php elseif ( is_404() ) : ?>
            <li class="inline-flex items-center shrink-0" aria-current="page">
                <span class="trendtoday-breadcrumb-sep"><i class="fas fa-chevron-right" aria-hidden="true"></i></span>
                <span class="trendtoday-breadcrumb-current text-gray-800 font-medium"><?php _e( 'ไม่พบหน้า', 'trendtoday' ); ?></span>
            </li>
        <?php endif; ?>
    </ol>
</nav>
