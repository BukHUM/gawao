<?php
/**
 * Main template file for displaying posts.
 *
 * @package KonDernTang
 */

get_header();
?>

<?php if (is_category() || is_archive()): ?>
    <!-- Category Header -->
    <?php
    $category = get_queried_object();
    $is_international = false;
    if (is_category() && $category->slug === 'international') {
        $is_international = true;
    }
    ?>
    <div class="relative bg-dark text-white py-12 md:py-16 mb-8 overflow-hidden">
        <?php if ($is_international): ?>
            <img src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=1600&q=80" class="absolute inset-0 w-full h-full object-cover opacity-30">
        <?php endif; ?>
        <div class="container mx-auto px-4 text-center relative z-10">
            <?php if (is_category()): ?>
                <span class="text-secondary font-bold tracking-wider uppercase text-sm">Category</span>
            <?php endif; ?>
            <h1 class="text-4xl font-heading font-bold mt-2">
                <?php
                if (is_category()) {
                    single_cat_title();
                } elseif (is_archive()) {
                    the_archive_title();
                }
                ?>
            </h1>
            <?php if (is_category() && category_description()): ?>
                <p class="text-gray-400 mt-2 max-w-2xl mx-auto"><?php echo category_description(); ?></p>
            <?php else: ?>
                <p class="text-gray-400 mt-2 max-w-2xl mx-auto"><?php echo esc_html($is_international ? 'เปิดประสบการณ์ใหม่ในต่างแดน สัมผัสวัฒนธรรมจากทั่วทุกมุมโลก' : 'รวมรีวิวที่เที่ยวในประเทศไทย ครบทั้ง 77 จังหวัด ตั้งแต่เหนือจรดใต้'); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="container mx-auto px-4 grid grid-cols-1 lg:grid-cols-4 gap-8 pb-12">
        <!-- Sidebar (Left) -->
        <aside class="hidden lg:block lg:col-span-1 space-y-8">
            <?php if ($is_international): ?>
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="font-heading font-bold text-lg mb-4">โซนยอดฮิต</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><label class="flex items-center gap-2"><input type="checkbox" class="rounded text-primary"> ญี่ปุ่น (Japan) (150)</label></li>
                        <li><label class="flex items-center gap-2"><input type="checkbox" class="rounded text-primary"> ยุโรป (Europe) (85)</label></li>
                        <li><label class="flex items-center gap-2"><input type="checkbox" class="rounded text-primary"> เอเชีย (Asia) (200)</label></li>
                        <li><label class="flex items-center gap-2"><input type="checkbox" class="rounded text-primary"> อเมริกา (USA) (45)</label></li>
                    </ul>
                </div>
                
                <!-- Agoda Widget for International -->
                <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl p-6 text-white text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 256 256" class="w-12 h-12 mx-auto mb-2"><path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Zm48.55-60.16a8,8,0,0,1-1.11,11.24c-13.43,11.56-29.9,17.92-47.44,17.92a72.22,72.22,0,0,1-19.74-2.72,8,8,0,0,1,4.36-15.36,56.89,56.89,0,0,0,15.38,2.08c13.22,0,26.1-4.75,36.77-13.54A8,8,0,0,1,176.55,155.84ZM96,120a12,12,0,1,1-12-12A12,12,0,0,1,96,120Zm88,0a12,12,0,1,1-12-12A12,12,0,0,1,184,120Z"></path></svg>
                    <h4 class="font-bold text-xl mb-2">จองตั๋วไปญี่ปุ่น?</h4>
                    <p class="text-sm opacity-90 mb-4">ตั๋วเครื่องบินราคาพิเศษ พร้อมที่พักใกล้สถานีรถไฟ</p>
                    <button class="bg-white text-blue-700 font-bold py-2 px-4 rounded-full w-full hover:bg-gray-100">ดูโปรโมชั่น</button>
                </div>
            <?php else: ?>
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="font-heading font-bold text-lg mb-4">ภาค (Regions)</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><label class="flex items-center gap-2"><input type="checkbox" class="rounded text-primary"> ภาคเหนือ (120)</label></li>
                        <li><label class="flex items-center gap-2"><input type="checkbox" class="rounded text-primary"> ภาคใต้ (85)</label></li>
                        <li><label class="flex items-center gap-2"><input type="checkbox" class="rounded text-primary"> ภาคอีสาน (60)</label></li>
                        <li><label class="flex items-center gap-2"><input type="checkbox" class="rounded text-primary"> ภาคตะวันออก (45)</label></li>
                    </ul>
                </div>
            <?php endif; ?>
        </aside>

        <!-- Main Grid (Right) -->
        <main class="lg:col-span-3">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php if (have_posts()): ?>
                    <?php while (have_posts()): the_post(); ?>
                        <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md transition">
                            <a href="<?php the_permalink(); ?>" class="block">
                                <?php if (has_post_thumbnail()): ?>
                                    <?php the_post_thumbnail('medium', ['class' => 'w-full h-48 object-cover']); ?>
                                <?php else: ?>
                                    <img src="https://images.unsplash.com/photo-1596422846543-75c6a197f070?w=600" class="w-full h-48 object-cover" alt="<?php the_title(); ?>">
                                <?php endif; ?>
                                <div class="p-4">
                                    <?php
                                    $categories = get_the_category();
                                    if (!empty($categories) && isset($categories[0])):
                                        $category = $categories[0];
                                    ?>
                                        <span class="text-xs text-primary font-bold"><?php echo esc_html($category->name); ?></span>
                                    <?php endif; ?>
                                    <h3 class="font-heading font-bold text-lg mt-1 mb-2 hover:text-primary"><?php the_title(); ?></h3>
                                    <?php if ($is_international): ?>
                                        <div class="flex items-center gap-2 text-gray-400 text-xs">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                            </svg>
                                            <?php echo get_the_date('j M Y'); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </article>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if (have_posts()): ?>
                <div class="flex justify-center mt-12 gap-2">
                    <?php
                    $pagination = paginate_links([
                        'prev_text' => 'Previous',
                        'next_text' => 'Next',
                        'type' => 'list'
                    ]);
                    if ($pagination) {
                        echo '<div class="flex gap-2">';
                        echo str_replace(['<ul class=\'page-numbers\'>', '</ul>'], '', $pagination);
                        echo '</div>';
                    }
                    ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
<?php else: ?>
    <!-- Default Archive/Index -->
    <div class="container mx-auto px-4 py-12">
        <?php if (is_search()): ?>
            <header class="mb-8">
                <h1 class="text-3xl font-heading font-bold">
                    ผลการค้นหา: <?php echo esc_html(get_search_query()); ?>
                </h1>
            </header>
        <?php elseif (is_404()): ?>
            <header class="mb-8">
                <h1 class="text-3xl font-heading font-bold">
                    ไม่พบหน้าที่คุณต้องการ
                </h1>
            </header>
        <?php endif; ?>

        <?php if (have_posts()): ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php while (have_posts()): the_post(); ?>
                    <?php get_template_part('template-parts/content', ''); ?>
                <?php endwhile; ?>
            </div>

            <?php TailPress\Pagination::render(); ?>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php
get_footer();
