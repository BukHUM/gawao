<?php
/**
 * Seed English Posts for TrendToday
 * Place this file in your WordPress root directory (e.g., c:\xampp\htdocs\trendtoday\automate\)
 * Access it via browser: http://localhost/trendtoday/automate/seed-posts.php
 */

require_once( dirname( dirname( __FILE__ ) ) . '/wp-load.php' ); // Adjusted path to root

if ( !current_user_can( 'manage_options' ) ) {
    die( 'Access denied. Please log in as Administrator.' );
}

echo "<h2>🌱 Seeding English Content...</h2>";

$posts = [
    // --- Technology ---
    [
        'title' => 'iPhone 16 Rumors: What We Know So Far About Apple’s Next Flagship',
        'content' => 'As we approach the expected September launch, rumors about the iPhone 16 differ significantly from previous years. Leaks suggest a new vertical camera layout for spatial video capture, powered by the A18 Pro chip. Analysts predict a strong focus on on-device AI capabilities, potentially integrated with a revamped Siri.',
        'category_slug' => 'technology',
    ],
    [
        'title' => 'The Rise of AI Agents: How 2026 Became the Year of Autonomous Software',
        'content' => 'Artificial Intelligence has moved beyond simple chatbots. The new wave of "AI Agents" can plan, execute, and verify complex tasks autonomously. Companies like Google and OpenAI are leading the charge, transforming how developers build applications and how businesses automate workflows.',
        'category_slug' => 'technology',
    ],
    
    // --- Politics ---
    [
        'title' => 'Global Climate Summit 2026: Nations Agree on Stricter Carbon Limits',
        'content' => 'In a historic agreement, 195 nations have signed the new "Paris Plus" accord, aiming to slash carbon emissions by another 40% by 2030. The summit highlighted the urgent need for renewable energy adoption and stricter penalties for industrial pollution.',
        'category_slug' => 'politics',
    ],
    [
        'title' => 'Election Watch: Key Swing States in the Upcoming Midterms',
        'content' => 'With the midterm elections just around the corner, all eyes are on the key swing states. Early polls suggest a tight race, with economic policy and healthcare being the top concerns for voters. Analysts predict record-breaking voter turnout.',
        'category_slug' => 'politics',
    ],

    // --- Business ---
    [
        'title' => 'Crypto Market Update: Bitcoin Surpasses New All-Time High',
        'content' => 'Bitcoin has once again shattered expectations, reaching a new all-time high of $120,000. Institutional adoption and the approval of new ETF products have driven this latest surge. Experts advise caution as volatility remains a characteristic of the market.',
        'category_slug' => 'business',
    ],
    [
        'title' => 'Tech Giants Report Q2 Earnings: Cloud Growth Slows, AI Revenue Soars',
        'content' => 'The latest earnings season reveals a shift in the tech landscape. Traditional cloud services are seeing slower growth, while revenue from AI-driven services is skyrocketing. Investors are reacting positively to companies investing heavily in GPU infrastructure.',
        'category_slug' => 'business',
    ],

    // --- Entertainment ---
    [
        'title' => 'Oscars 2026 Predictions: Who Will Take Home Best Picture?',
        'content' => 'The academy awards are fast approaching, and the race for Best Picture is tighter than ever. Critics are favoring the indie sci-fi drama "Starlight," while blockbusters like "The Titan Reborn" are strong contenders for technical awards.',
        'category_slug' => 'entertainment',
    ],
    [
        'title' => 'Review: The Latest Marvel Movie Struggles to Find its Footing',
        'content' => 'Marvels latest entry into the MCU has received mixed reviews. While the visual effects are stunning as always, critics argue that the plot feels disjointed and lacks the character depth seen in earlier phases. Fans, however, are praising the post-credits scene.',
        'category_slug' => 'entertainment',
    ],

    // --- Sports ---
    [
        'title' => 'NBA Finals Game 5: Celtics vs. Lakers Showdown',
        'content' => 'The rivalry reignites as the Celtics face off against the Lakers in a crucial Game 5. With the series tied 2-2, tonight\'s game could determine the momentum for the championship. Star players from both sides are reportedly fully healthy and ready to go.',
        'category_slug' => 'sports',
    ],
    [
        'title' => 'F1 Season Opener: Red Bull Dominates Bahrain Practice',
        'content' => 'Formula 1 is back, and Red Bull Racing looks as dominant as ever. Max Verstappen topped the timesheets in both practice sessions in Bahrain, leaving Ferrari and Mercedes scrambling to find pace. The midfield battle, however, looks incredibly close.',
        'category_slug' => 'sports',
    ],

    // --- Science ---
    [
        'title' => 'Mars Colony Mission: SpaceX Targets 2029 for First Human Landing',
        'content' => 'Elon Musk has updated the timeline for SpaceX\'s Mars mission. The Starship program has reached critical milestones, and the company is now targeting a 2029 launch window for the first human landing. NASA is closely monitoring the progress.',
        'category_slug' => 'science',
    ],
    
    // --- Health ---
    [
        'title' => 'Breakthrough Study Links Sleep Quality to Long-term Memory Retention',
        'content' => 'A new study published in the Journal of Neuroscience confirms what many suspected: quality sleep is essential for memory consolidation. The research shows that deep sleep cycles are when the brain "cleans" itself and strengthens neural pathways formed during the day.',
        'category_slug' => 'health',
    ],

    // --- Lifestyle ---
    [
        'title' => 'Top 10 Travel Destinations for Digital Nomads in 2026',
        'content' => 'Remote work is here to stay, and countries are competing to attract digital nomads. From the beaches of Thailand to the historic streets of Portugal, we rank the top 10 destinations based on internet speed, cost of living, and community vibes.',
        'category_slug' => 'lifestyle',
    ]
];

foreach ($posts as $post_data) {
    // Check if post exists
    $existing_post = get_page_by_title($post_data['title'], OBJECT, 'post');

    if ($existing_post) {
        echo "⚠️ Post '{$post_data['title']}' already exists.<br>";
        continue;
    }

    // Insert Post
    $post_id = wp_insert_post([
        'post_title'    => $post_data['title'],
        'post_content'  => $post_data['content'],
        'post_status'   => 'publish',
        'post_author'   => 1, // Admin
    ]);

    if (is_wp_error($post_id)) {
        echo "❌ Error creating '{$post_data['title']}': " . $post_id->get_error_message() . "<br>";
    } else {
        // Set Category
        $term = get_term_by('slug', $post_data['category_slug'], 'category');
        if ($term) {
            wp_set_object_terms($post_id, $term->term_id, 'category');
            
            // Set Language to 'en' (AU) if Polylang is active
            if ( function_exists('pll_set_post_language') ) {
                pll_set_post_language($post_id, 'en');
                echo "&nbsp;&nbsp;🇬🇧 Set lang to 'en'. ";
            }

            echo "✅ Created '{$post_data['title']}' in <b>{$post_data['category_slug']}</b><br>";
        } else {
            echo "⚠️ Created '{$post_data['title']}' but ❌ Category '{$post_data['category_slug']}' not found.<br>";
        }
    }
}

echo "<h3>🎉 English Content Seeding Completed!</h3>";
echo "<p><a href='../' target='_blank'>Visit Homepage</a></p>";
