<?php
/**
 * Custom HTML / Ad Widget (พื้นที่โฆษณา หรือ HTML กำหนดเอง)
 *
 * @package TrendToday
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Custom HTML Widget Class
 */
class TrendToday_Custom_HTML_Widget extends WP_Widget {

    /**
     * Default placeholder banner HTML (shown when content is empty).
     * Inline HTML/CSS only to avoid SVG/encoding issues and parsing errors.
     *
     * @return string HTML for placeholder ad banner.
     */
    private static function get_placeholder_banner() {
        $label = __( 'โฆษณา', 'trendtoday' );
        $size  = '300&times;250';
        return '<div class="trendtoday-ad-placeholder" style="max-width:100%;background:#f3f4f6;border:1px dashed #d1d5db;border-radius:0.5rem;aspect-ratio:300/250;display:flex;align-items:center;justify-content:center;min-height:200px;">
            <span style="color:#9ca3af;font-size:0.875rem;font-weight:500;">' . esc_html( $label ) . ' ' . $size . '</span>
        </div>';
    }

    public function __construct() {
        parent::__construct(
            'trendtoday_custom_html',
            __( 'Trend Today: Custom HTML / Ad', 'trendtoday' ),
            array(
                'description' => __( 'Arbitrary HTML or ad code. Use for ads or custom content.', 'trendtoday' ),
                'classname'   => 'widget_trendtoday_custom_html',
            )
        );
    }

    public function widget( $args, $instance ) {
        $content = ! empty( $instance['content'] ) ? $instance['content'] : '';
        $title   = ! empty( $instance['title'] ) ? $instance['title'] : '';

        if ( empty( $content ) ) {
            $content = self::get_placeholder_banner();
        }

        echo $args['before_widget'];
        if ( $title ) {
            echo $args['before_title'] . '<i class="fas fa-rectangle-ad text-accent"></i> ' . esc_html( $title ) . $args['after_title'];
        }
        echo '<div class="trendtoday-custom-html widget-content">';
        echo wp_kses_post( $content );
        echo '</div>';
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title   = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $content = ! empty( $instance['content'] ) ? $instance['content'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'trendtoday' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>"><?php _e( 'Content (HTML):', 'trendtoday' ); ?></label>
            <textarea class="widefat" rows="8" id="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'content' ) ); ?>"><?php echo esc_textarea( $content ); ?></textarea>
        </p>
        <p class="description" style="margin-top:8px;">
            <strong><?php _e( 'วิธีใส่โค้ด:', 'trendtoday' ); ?></strong><br>
            <?php _e( '• โฆษณาจาก Google AdSense: วางสคริปต์ที่ได้จาก AdSense ทั้งก้อนลงในช่องด้านบน', 'trendtoday' ); ?><br>
            <?php _e( '• แบนเนอร์รูป: ใช้ HTML เช่น', 'trendtoday' ); ?>
            <code style="display:block;margin:4px 0;padding:6px;background:#f0f0f0;border-radius:4px;font-size:11px;">&lt;a href="ลิงก์"&gt;&lt;img src="ที่อยู่รูป" alt="" style="max-width:100%;height:auto;"&gt;&lt;/a&gt;</code>
            <?php _e( '• เนื้อหาอื่น: ใส่แท็ก HTML ที่ต้องการได้ (รองรับแท็กพื้นฐาน)', 'trendtoday' ); ?>
        </p>
        <p class="description"><?php _e( 'ถ้าไม่ใส่โค้ด จะแสดงกรอบ placeholder โฆษณาให้', 'trendtoday' ); ?></p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title']   = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['content'] = ! empty( $new_instance['content'] ) ? wp_kses_post( $new_instance['content'] ) : '';
        return $instance;
    }
}
