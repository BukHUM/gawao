<?php
/**
 * Setup Polylang & Categories for TrendToday (Fixed)
 * Place this file in your WordPress root directory
 * Access via: http://trendtoday.local/setup-polylang.php
 */

define( 'WP_MW_NO_SESSION', true ); // Optimize loading
require_once( dirname( __FILE__ ) . '/wp-load.php' );
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

if ( !current_user_can( 'manage_options' ) ) {
    die( 'Access denied. Please log in as Administrator.' );
}

echo "<h2>🌐 Polylang Setup & Category Creation (v2)</h2>";

// 1. Ensure Plugin Active
if ( !is_plugin_active( 'polylang/polylang.php' ) ) {
    activate_plugin( 'polylang/polylang.php' );
    echo "✅ Activated Polylang.<br>";
} else {
    echo "✅ Polylang is active.<br>";
}

// 2. Define Supported Languages
// Format: code => [locale, name, flag_code]
$languages_to_add = [
    'th'    => ['locale' => 'th',    'name' => 'ไทย',       'flag' => 'th'],
    'en'    => ['locale' => 'en_AU', 'name' => 'English',    'flag' => 'au'], // Default to AU based on screenshot
    'en-us' => ['locale' => 'en_US', 'name' => 'English',    'flag' => 'us'],
    'en-nz' => ['locale' => 'en_NZ', 'name' => 'English',    'flag' => 'nz'],
    'de'    => ['locale' => 'de_DE', 'name' => 'Deutsch',    'flag' => 'de'],
    'fr'    => ['locale' => 'fr_FR', 'name' => 'Français',   'flag' => 'fr'],
    'ja'    => ['locale' => 'ja',    'name' => '日本語',      'flag' => 'jp'],
    'lo'    => ['locale' => 'lo',    'name' => 'ພາສາລາວ', 'flag' => 'la'],
];

echo "<h3>1️⃣ Checking Languages...</h3>";

// --- Fix for Polylang 3.x+ Strict Typing ---
$model = null;

// Try to get global instance first (Best Practice)
if ( function_exists('PLL') && is_object(PLL()) && isset(PLL()->model) ) {
    $model = PLL()->model;
} 
// Fallback: Try to instantiate PLL_Admin_Model manually
elseif ( class_exists('PLL_Admin_Model') ) {
    try {
        // Check if Options class exists (Polylang 3.x namespace)
        if ( class_exists('WP_Syntex\Polylang\Options\Options') ) {
            $options_obj = new \WP_Syntex\Polylang\Options\Options( get_option( 'polylang' ) );
            $model = new PLL_Admin_Model( $options_obj );
        } else {
            // Old Polylang version fallback
            $model = new PLL_Admin_Model( get_option( 'polylang' ) );
        }
    } catch (Exception $e) {
        echo "⚠️ Model Instantiation Warning: " . $e->getMessage() . "<br>";
    }
}

if ( $model ) {
    // Get existing languages
    $existing_slugs = [];
    $langs = $model->get_languages_list();
    foreach($langs as $l) {
        $existing_slugs[] = $l->slug;
    }

    foreach ($languages_to_add as $slug => $info) {
        if ( in_array($slug, $existing_slugs) ) {
            echo "✅ Language '$slug' ({$info['name']}) already exists.<br>";
        } else {
            // Add Language
            $args = [
                'name'       => $info['name'],
                'slug'       => $slug,
                'locale'     => $info['locale'],
                'rtl'        => 0,
                'term_group' => 0,
                'flag'       => $info['flag']
            ];
            
            try {
                if ( method_exists($model, 'add_language') ) {
                    $model->add_language($args);
                    echo "✨ Added Language: {$info['name']} ($slug)<br>";
                } else {
                     echo "❌ Method 'add_language' not found in model.<br>";
                }
            } catch (Exception $e) {
                echo "❌ Failed to add '$slug': " . $e->getMessage() . "<br>";
            }
        }
    }
} else {
    echo "❌ <b>Critical:</b> Could not access Polylang Model. Please add languages manually in WP Admin > Languages.<br>";
    // We can still try to proceed to categories if languages were added manually
}

echo "<hr>";
echo "<h3>2️⃣ Creating Localized Categories...</h3>";

// --- DELETE OLD CATEGORIES FIRST (User Request) ---
echo "<b>⚠️ Resetting Categories...</b><br>";
$all_cats = get_categories(['hide_empty' => false]);
foreach ($all_cats as $cat) {
    if ( $cat->slug === 'uncategorized' || $cat->term_id == 1 ) {
        continue; // Skip default category
    }
    wp_delete_term($cat->term_id, 'category');
    echo "&nbsp;&nbsp;🗑️ Deleted: {$cat->name}<br>";
}
echo "<br>";
// --------------------------------------------------

$category_map = [
    'th'    => ['News', 'Politics', 'Entertainment', 'Business', 'Tech'],
    'en'    => ['News', 'Business', 'Technology', 'Sports', 'Lifestyle'], // AU
    'en-us' => ['News', 'Technology', 'Business', 'Entertainment', 'Sports', 'Science'],
    'en-nz' => ['News', 'Business', 'Sports', 'Lifestyle'],
    'de'    => ['Nachrichten', 'Wirtschaft', 'Technologie', 'Unterhaltung', 'Sport'], // German
    'fr'    => ['Actualités', 'Économie', 'Technologie', 'Divertissement', 'Sport'], // French
    'ja'    => ['ニュース', 'ビジネス', 'テクノロジー', 'エンターテインメント', 'スポーツ'], // Japanese
    'lo'    => ['ຂ່າວ', 'ທຸລະກິດ', 'ເຕັກໂນໂລຊີ', 'ບັນເທີງ', 'ກິລາ'], // Lao
];

foreach ($category_map as $lang_code => $cats) {
    echo "<b>Processing $lang_code...</b><br>";
    
    foreach ($cats as $cat_name) {
        // Unique slug trick: cat-lang
        $slug = sanitize_title($cat_name . '-' . $lang_code);
        
        // Check if term exists (generic check)
        $term_exists = term_exists($slug, 'category'); // checking slug is safer for duplicates
        if (!$term_exists) {
            $term_exists = term_exists($cat_name, 'category');
        }

        $term_id = 0;

        if ( !$term_exists ) {
            $new_term = wp_insert_term( $cat_name, 'category', ['slug' => $slug] );
            if ( ! is_wp_error( $new_term ) ) {
                $term_id = $new_term['term_id'];
                echo "&nbsp;&nbsp;✨ Created '$cat_name' (ID: $term_id)... ";
            } else {
                echo "&nbsp;&nbsp;❌ Error creating '$cat_name': " . $new_term->get_error_message() . "<br>";
            }
        } else {
             $term_id = is_array($term_exists) ? $term_exists['term_id'] : $term_exists;
             echo "&nbsp;&nbsp;✅ '$cat_name' exists (ID: $term_id)... ";
        }

        // Set Language
        if ($term_id && function_exists('pll_set_term_language')) {
            pll_set_term_language( $term_id, $lang_code );
            echo "Set language to '$lang_code'.<br>";
        }
    }
}

echo "<h3>🎉 Setup V2 Completed! (Check WP Admin to confirm)</h3>";
