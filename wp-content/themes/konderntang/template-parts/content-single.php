<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php
    // Get excerpt or first paragraph as lead
    $excerpt = get_the_excerpt();
    if (!$excerpt) {
        $content = get_the_content();
        $excerpt = wp_trim_words(strip_tags($content), 30);
    }
    ?>
    <?php if ($excerpt): ?>
        <p class="lead text-xl text-gray-600 font-light italic border-l-4 border-secondary pl-4 mb-8">
            "<?php echo esc_html($excerpt); ?>"
        </p>
    <?php endif; ?>
    
    <div class="entry-content">
        <?php the_content(); ?>
    </div>
</article>

