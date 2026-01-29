<?php
/**
 * Theme footer template.
 *
 * @package TailPress
 */
?>
        </main>

        <?php do_action('tailpress_content_end'); ?>
    </div>

    <?php do_action('tailpress_content_after'); ?>

    <footer class="bg-dark text-white pt-12 pb-8 border-t border-gray-700 mt-auto">
        <div class="container mx-auto px-4 text-center">
            <h2 class="font-heading font-bold text-2xl mb-4">
                <?php bloginfo('name'); ?><span class="text-secondary">.com</span>
            </h2>
            <div class="flex justify-center gap-6 mb-6 text-gray-400 flex-wrap">
                <a href="#" class="hover:text-white">เกี่ยวกับเรา</a>
                <a href="#" class="hover:text-white">ติดต่อโฆษณา</a>
                <a href="#" class="hover:text-white">นโยบายความเป็นส่วนตัว</a>
            </div>
            <p class="text-gray-500 text-sm">
                &copy; <?php echo esc_html(date_i18n('Y')); ?> <?php bloginfo('name'); ?> - เพื่อนเดินทางของคุณ
            </p>
        </div>
    </footer>
</div>

<?php wp_footer(); ?>
</body>
</html>
