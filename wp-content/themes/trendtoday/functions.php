<?php
/**
 * Trend Today Theme Functions
 *
 * @package TrendToday
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Include theme files
 */
require_once get_template_directory() . '/inc/theme-setup.php';
require_once get_template_directory() . '/inc/theme-helpers.php';
require_once get_template_directory() . '/inc/enqueue-scripts.php';
require_once get_template_directory() . '/inc/custom-post-types.php';
require_once get_template_directory() . '/inc/custom-fields.php';
require_once get_template_directory() . '/inc/category-fields.php';
require_once get_template_directory() . '/inc/cpt-helpers.php';
require_once get_template_directory() . '/inc/dynamic-content.php';
require_once get_template_directory() . '/inc/ajax-handlers.php';
require_once get_template_directory() . '/inc/menu-walker.php';
require_once get_template_directory() . '/inc/navigation-functions.php';
require_once get_template_directory() . '/inc/menu-icons.php';
require_once get_template_directory() . '/inc/menu-active-states.php';
require_once get_template_directory() . '/inc/widget-helpers.php';
require_once get_template_directory() . '/inc/register-widgets.php';
require_once get_template_directory() . '/inc/widget-styling.php';

// Load widgets
require_once get_template_directory() . '/widgets/class-popular-posts-widget.php';
require_once get_template_directory() . '/widgets/class-recent-posts-widget.php';
require_once get_template_directory() . '/widgets/class-newsletter-widget.php';
require_once get_template_directory() . '/widgets/class-trending-tags-widget.php';

/**
 * Note: Widget areas are registered in inc/theme-setup.php
 * This file only includes necessary theme files.
 */
