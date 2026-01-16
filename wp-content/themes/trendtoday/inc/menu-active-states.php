<?php
/**
 * Menu Active States
 *
 * @package TrendToday
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add active class to current menu item
 *
 * @param array $classes CSS classes.
 * @param object $item Menu item.
 * @param array $args Menu arguments.
 * @return array Modified CSS classes.
 */
function trendtoday_nav_menu_active_classes( $classes, $item, $args ) {
    // Add active class for current page
    if ( in_array( 'current-menu-item', $classes ) ) {
        $classes[] = 'active';
        $classes[] = 'text-gray-900';
    }
    
    // Add active class for current page parent
    if ( in_array( 'current-menu-parent', $classes ) ) {
        $classes[] = 'active';
    }
    
    // Add active class for current page ancestor
    if ( in_array( 'current-menu-ancestor', $classes ) ) {
        $classes[] = 'active';
    }

    return $classes;
}
add_filter( 'nav_menu_css_class', 'trendtoday_nav_menu_active_classes', 10, 3 );

/**
 * Add active class to menu item link
 *
 * @param array $atts Link attributes.
 * @param object $item Menu item.
 * @param array $args Menu arguments.
 * @return array Modified link attributes.
 */
function trendtoday_nav_menu_link_active_class( $atts, $item, $args ) {
    $classes = empty( $item->classes ) ? array() : (array) $item->classes;
    
    if ( in_array( 'current-menu-item', $classes ) ) {
        $atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' active text-gray-900 font-medium' : 'active text-gray-900 font-medium';
    }
    
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'trendtoday_nav_menu_link_active_class', 10, 3 );
