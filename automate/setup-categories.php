<?php
/**
 * Setup Categories (English/Google Trends)
 * Place this file in your WordPress root directory
 * Access via: http://localhost/trendtoday/setup-categories.php
 */

require_once( dirname( __FILE__ ) . '/wp-load.php' );

if ( !current_user_can( 'manage_options' ) ) {
    die( 'Access denied. Administrator only.' );
}

echo "<h2>🔥 Setting up Trending Categories (English)</h2>";

$categories = [
    'Politics',
    'Technology',
    'Business',
    'Entertainment',
    'Sports',
    'Science',
    'Health',
    'World',
    'Lifestyle'
];

foreach ($categories as $cat_name) {
    // Simple slug: 'politics', 'technology'
    $slug = sanitize_title($cat_name);
    
    $term = term_exists($slug, 'category');
    
    if ( !$term ) {
        $result = wp_insert_term( $cat_name, 'category', ['slug' => $slug] );
        
        if ( !is_wp_error($result) ) {
            $term_id = $result['term_id'];
            echo "✅ Created: <b>$cat_name</b> (ID: $term_id)<br>";
            
            // Attempt to assign to 'en' language if Polylang is active and 'en' language exists
            if ( function_exists('pll_set_term_language') ) {
                // We default to 'en' (which usually maps to the primary English)
                // In your setup-polylang.php, 'en' is English (AU).
                pll_set_term_language($term_id, 'en');
                echo "&nbsp;&nbsp;🇬🇧 Assigned to Language: 'en'<br>";
            }
        } else {
             echo "❌ Error '$cat_name': " . $result->get_error_message() . "<br>";
        }
    } else {
        $term_id = is_array($term) ? $term['term_id'] : $term;
        echo "⚠️ Exists: <b>$cat_name</b> (ID: $term_id)<br>";
        
        // Ensure language is set even if it exists
        if ( function_exists('pll_set_term_language') ) {
            pll_set_term_language($term_id, 'en');
        }
    }
}

echo "<hr><h3>🎉 Category Setup Completed!</h3>";
