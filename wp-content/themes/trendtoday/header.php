<?php
/**
 * The header template file
 *
 * @package TrendToday
 * @since 1.0.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ["Prompt", "sans-serif"],
                },
                colors: {
                    primary: "#1a1a1a",
                    accent: "#FF4500",
                    "news-tech": "#3B82F6",
                    "news-ent": "#EC4899",
                    "news-fin": "#10B981",
                    "news-sport": "#F59E0B",
                }
            }
        }
    }
    </script>
    <?php wp_head(); ?>
</head>

<body <?php body_class( 'bg-gray-50 text-gray-800 font-sans antialiased flex flex-col min-h-screen' ); ?>>
<?php wp_body_open(); ?>

<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-0 focus:left-0 focus:z-50 focus:px-4 focus:py-2 focus:bg-accent focus:text-white"><?php _e( 'Skip to content', 'trendtoday' ); ?></a>

<?php
// Show navbar, trending tags, and category filters on all pages
// (front-page.php is now disabled, so we show navbar everywhere)
get_template_part( 'template-parts/navbar' );
get_template_part( 'template-parts/trending-tags' );
get_template_part( 'template-parts/category-filters' );
?>
