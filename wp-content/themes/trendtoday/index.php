<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 *
 * @package TrendToday
 * @since 1.0.0
 */

get_header();
?>

<main id="main-content" class="site-main flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
    <?php
    if ( have_posts() ) :
        ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php
            while ( have_posts() ) :
                the_post();
                get_template_part( 'template-parts/news-card' );
            endwhile;
            ?>
        </div>
        <?php
        get_template_part( 'template-parts/pagination' );
    else :
        get_template_part( 'template-parts/content', 'none' );
    endif;
    ?>
</main>

<?php
get_footer();
