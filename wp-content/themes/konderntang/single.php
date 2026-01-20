<?php
/**
 * Single post template file.
 *
 * @package KonDernTang
 */

get_header();
?>

<?php if (have_posts()): ?>
    <?php while (have_posts()): the_post(); ?>
        <!-- Post Hero -->
        <div class="relative h-[60vh] w-full">
            <?php if (has_post_thumbnail()): ?>
                <?php the_post_thumbnail('full', ['class' => 'w-full h-full object-cover']); ?>
            <?php else: ?>
                <img src="https://images.unsplash.com/photo-1506665531195-3566af2b4dfa?auto=format&fit=crop&w=1600&q=80" class="w-full h-full object-cover" alt="<?php the_title(); ?>">
            <?php endif; ?>
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
            <div class="absolute bottom-0 left-0 w-full p-8 md:p-16 text-white container mx-auto">
                <div class="flex items-center gap-3 mb-4 text-sm font-medium flex-wrap">
                    <?php
                    $categories = get_the_category();
                    if (!empty($categories) && isset($categories[0])):
                        $category = $categories[0];
                    ?>
                        <span class="bg-secondary px-3 py-1 rounded-full"><?php echo esc_html($category->name); ?></span>
                    <?php endif; ?>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 inline-block">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                        <?php echo get_the_date('j M Y'); ?>
                    </span>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 inline-block">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        โดย <?php the_author(); ?>
                    </span>
                </div>
                <h1 class="text-3xl md:text-5xl font-heading font-bold leading-tight drop-shadow-lg">
                    <?php the_title(); ?>
                </h1>
            </div>
        </div>

        <div class="container mx-auto px-4 py-12 grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Article Content (Left) -->
            <article class="lg:col-span-2 prose prose-lg max-w-none font-sans text-gray-700">
                <?php get_template_part('template-parts/content', 'single'); ?>

                <!-- Social Share -->
                <div class="flex gap-4 mt-12 border-t pt-8">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" class="flex items-center gap-2 bg-[#1877F2] text-white px-4 py-2 rounded hover:opacity-90">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 256 256" class="w-5 h-5"><path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm8,191.63V152h24a8,8,0,0,0,0-16H136V112a16,16,0,0,1,16-16h16a8,8,0,0,0,0-16H152a32,32,0,0,0-32,32v24H96a8,8,0,0,0,0,16h24v63.63a88,88,0,1,1,16,0Z"></path></svg>
                        Share
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="flex items-center gap-2 bg-[#1DA1F2] text-white px-4 py-2 rounded hover:opacity-90">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 256 256" class="w-5 h-5"><path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Zm48.55-60.16a8,8,0,0,1-1.11,11.24c-13.43,11.56-29.9,17.92-47.44,17.92a72.22,72.22,0,0,1-19.74-2.72,8,8,0,0,1,4.36-15.36,56.89,56.89,0,0,0,15.38,2.08c13.22,0,26.1-4.75,36.77-13.54A8,8,0,0,1,176.55,155.84ZM96,120a12,12,0,1,1-12-12A12,12,0,0,1,96,120Zm88,0a12,12,0,1,1-12-12A12,12,0,0,1,184,120Z"></path></svg>
                        Tweet
                    </a>
                    <a href="https://social-plugins.line.me/lineit/share?url=<?php echo urlencode(get_permalink()); ?>" target="_blank" class="flex items-center gap-2 bg-[#06C755] text-white px-4 py-2 rounded hover:opacity-90">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 256 256" class="w-5 h-5"><path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Zm48.55-60.16a8,8,0,0,1-1.11,11.24c-13.43,11.56-29.9,17.92-47.44,17.92a72.22,72.22,0,0,1-19.74-2.72,8,8,0,0,1,4.36-15.36,56.89,56.89,0,0,0,15.38,2.08c13.22,0,26.1-4.75,36.77-13.54A8,8,0,0,1,176.55,155.84ZM96,120a12,12,0,1,1-12-12A12,12,0,0,1,96,120Zm88,0a12,12,0,1,1-12-12A12,12,0,0,1,184,120Z"></path></svg>
                        Line
                    </a>
                </div>
            </article>

            <!-- Sidebar (Right) -->
            <aside class="space-y-8">
                <!-- Author Box -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm text-center">
                    <?php echo get_avatar(get_the_author_meta('ID'), 80, '', '', ['class' => 'w-20 h-20 rounded-full mx-auto mb-4 bg-gray-100']); ?>
                    <h4 class="font-heading font-bold text-lg"><?php the_author(); ?></h4>
                    <p class="text-gray-500 text-sm mt-2"><?php echo esc_html(get_the_author_meta('description') ?: 'นักเดินทางสายลุย ชอบกาแฟ และการถ่ายรูป หลงรักภูเขามากกว่าทะเล'); ?></p>
                </div>

                <!-- Related Posts -->
                <?php
                $related_posts = new WP_Query([
                    'category__in' => wp_get_post_categories(get_the_ID()),
                    'post__not_in' => [get_the_ID()],
                    'posts_per_page' => 2,
                    'orderby' => 'rand'
                ]);
                
                if ($related_posts->have_posts()):
                ?>
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <h4 class="font-heading font-bold text-lg mb-4 border-l-4 border-secondary pl-3">บทความแนะนำ</h4>
                    <div class="space-y-4">
                        <?php while ($related_posts->have_posts()): $related_posts->the_post(); ?>
                            <a href="<?php the_permalink(); ?>" class="flex gap-3 cursor-pointer group">
                                <?php if (has_post_thumbnail()): ?>
                                    <?php the_post_thumbnail('thumbnail', ['class' => 'w-20 h-20 object-cover rounded-lg']); ?>
                                <?php else: ?>
                                    <img src="https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?w=100" class="w-20 h-20 object-cover rounded-lg" alt="<?php the_title(); ?>">
                                <?php endif; ?>
                                <div>
                                    <h5 class="font-bold text-sm text-dark group-hover:text-primary leading-tight"><?php the_title(); ?></h5>
                                    <span class="text-xs text-gray-400 mt-1 block"><?php echo get_the_date('j M Y'); ?></span>
                                </div>
                            </a>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Sticky Ad / Promo -->
                <div class="sticky top-24 bg-gradient-to-br from-secondary to-orange-500 rounded-xl p-6 text-white text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 256 256" class="w-12 h-12 mx-auto mb-2"><path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Zm48.55-60.16a8,8,0,0,1-1.11,11.24c-13.43,11.56-29.9,17.92-47.44,17.92a72.22,72.22,0,0,1-19.74-2.72,8,8,0,0,1,4.36-15.36,56.89,56.89,0,0,0,15.38,2.08c13.22,0,26.1-4.75,36.77-13.54A8,8,0,0,1,176.55,155.84ZM96,120a12,12,0,1,1-12-12A12,12,0,0,1,96,120Zm88,0a12,12,0,1,1-12-12A12,12,0,0,1,184,120Z"></path></svg>
                    <h4 class="font-bold text-xl mb-2">จองตั๋วเครื่องบิน?</h4>
                    <p class="text-sm opacity-90 mb-4">เปรียบเทียบราคาตั๋วเครื่องบินทั่วโลก ราคาดีที่สุดที่นี่</p>
                    <a href="#" class="bg-white text-secondary font-bold py-2 px-4 rounded-full w-full hover:bg-gray-100 inline-block">เช็คราคา</a>
                </div>
            </aside>
        </div>

        <?php if (comments_open() || get_comments_number()): ?>
            <div class="container mx-auto px-4">
                <?php comments_template(); ?>
            </div>
        <?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>

<?php
get_footer();

