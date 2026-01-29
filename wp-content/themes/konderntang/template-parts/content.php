<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-xl shadow-sm hover:shadow-lg transition overflow-hidden group border border-gray-100'); ?>>
    <a href="<?php the_permalink(); ?>" class="block">
        <div class="relative h-48 overflow-hidden">
            <?php if (has_post_thumbnail()): ?>
                <?php the_post_thumbnail('medium', ['class' => 'w-full h-full object-cover transition duration-500 group-hover:scale-110']); ?>
            <?php else: ?>
                <img src="https://images.unsplash.com/photo-1596422846543-75c6a197f070?auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover transition duration-500 group-hover:scale-110" alt="<?php the_title(); ?>">
            <?php endif; ?>
            <?php
            $categories = get_the_category();
            if (!empty($categories) && isset($categories[0])):
                $category = $categories[0];
            ?>
                <span class="absolute top-3 left-3 bg-white/90 text-dark text-xs font-bold px-2 py-1 rounded backdrop-blur-sm"><?php echo esc_html($category->name); ?></span>
            <?php endif; ?>
        </div>
        <div class="p-5">
            <h3 class="font-heading font-semibold text-lg text-dark mb-2 leading-snug group-hover:text-primary transition"><?php the_title(); ?></h3>
            <div class="flex items-center gap-2 text-gray-400 text-xs mt-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
                <?php echo get_the_date('j M Y'); ?>
            </div>
        </div>
    </a>
</article>
