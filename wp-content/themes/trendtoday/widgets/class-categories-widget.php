<?php
/**
 * Categories Widget (รายการหมวดหมู่)
 *
 * @package TrendToday
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Categories Widget Class
 */
class TrendToday_Categories_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'trendtoday_categories',
            __( 'Trend Today: Categories', 'trendtoday' ),
            array( 'description' => __( 'Display list of categories with post count', 'trendtoday' ) )
        );
    }

    public function widget( $args, $instance ) {
        $title        = ! empty( $instance['title'] ) ? $instance['title'] : __( 'หมวดหมู่', 'trendtoday' );
        $show_count   = ! empty( $instance['show_count'] );
        // Default hierarchical to true so programmatic render (sidebar-single) and new instances show collapsible list
        $hierarchical = isset( $instance['hierarchical'] ) ? ! empty( $instance['hierarchical'] ) : true;

        if ( ! taxonomy_exists( 'category' ) ) {
            return;
        }

        $list_style = 'trendtoday-categories-list';
        $wrap_attr  = '';

        echo $args['before_widget'];
        if ( $title ) {
            echo $args['before_title'] . '<i class="fas fa-folder text-accent"></i> ' . esc_html( $title ) . $args['after_title'];
        }

        if ( $hierarchical ) {
            $top_cats = get_categories( array(
                'orderby'    => 'name',
                'order'      => 'ASC',
                'parent'     => 0,
                'taxonomy'   => 'category',
                'hide_empty' => false,
            ) );
            if ( ! empty( $top_cats ) ) {
                echo '<div class="' . esc_attr( $list_style ) . ' trendtoday-categories-collapsible"' . $wrap_attr . '>';
                echo '<ul class="trendtoday-categories-root space-y-0.5">';
                foreach ( $top_cats as $cat ) {
                    $children = get_categories( array(
                        'orderby'    => 'name',
                        'order'      => 'ASC',
                        'parent'     => $cat->term_id,
                        'taxonomy'   => 'category',
                        'hide_empty' => false,
                    ) );
                    $has_children = ! empty( $children );
                    $tid          = (int) $cat->term_id;
                    echo '<li class="trendtoday-cat-item-wrap' . ( $has_children ? ' trendtoday-cat-has-children' : '' ) . '" data-term-id="' . esc_attr( $tid ) . '">';
                    echo '<div class="trendtoday-cat-row flex items-center gap-1 min-w-0">';
                    if ( $has_children ) {
                        echo '<button type="button" class="trendtoday-cat-toggle flex-shrink-0 w-6 h-6 flex items-center justify-center rounded text-gray-500 hover:bg-gray-100 hover:text-accent transition-colors" aria-expanded="false" aria-label="' . esc_attr__( 'แสดงหมวดย่อย', 'trendtoday' ) . '" data-term-id="' . esc_attr( $tid ) . '" title="' . esc_attr__( 'แสดง/ซ่อนหมวดย่อย', 'trendtoday' ) . '"><i class="fas fa-chevron-right trendtoday-cat-chevron text-xs transition-transform" aria-hidden="true"></i></button>';
                    } else {
                        echo '<span class="trendtoday-cat-no-children flex-shrink-0 w-6 inline-block" aria-hidden="true"></span>';
                    }
                    $this->render_category_link( $cat, $show_count, true );
                    echo '</div>';
                    if ( $has_children ) {
                        echo '<ul class="trendtoday-categories-children pl-4 mt-0.5 mb-1 space-y-0.5 border-l-2 border-gray-100 ml-2" role="group" aria-label="' . esc_attr( $cat->name ) . '">';
                        foreach ( $children as $child ) {
                            echo '<li class="trendtoday-cat-item-wrap">';
                            $this->render_category_link( $child, $show_count, false );
                            echo '</li>';
                        }
                        echo '</ul>';
                    }
                    echo '</li>';
                }
                echo '</ul></div>';
            }
        } else {
            $categories = get_categories( array(
                'orderby'    => 'name',
                'order'      => 'ASC',
                'taxonomy'   => 'category',
                'hide_empty' => false,
            ) );
            if ( ! empty( $categories ) ) {
                echo '<div class="' . esc_attr( $list_style ) . '"' . $wrap_attr . '>';
                echo '<ul class="trendtoday-categories-root space-y-0.5">';
                foreach ( $categories as $cat ) {
                    echo '<li class="trendtoday-cat-item-wrap">';
                    $this->render_category_link( $cat, $show_count, false );
                    echo '</li>';
                }
                echo '</ul></div>';
            }
        }
        echo $args['after_widget'];
    }

    /**
     * Output category link (and optional count badge). Caller wraps in <li> or div.
     *
     * @param WP_Term $cat        Category term.
     * @param bool    $show_count Whether to show post count.
     * @param bool    $in_row     When true, link is inside .trendtoday-cat-row (flex-grow).
     */
    private function render_category_link( $cat, $show_count, $in_row = false ) {
        $url       = home_url( '?cat=' . $cat->term_id );
        $name_attr = esc_attr( $cat->name );
        $name_html = esc_html( $cat->name );
        $class     = 'trendtoday-cat-link flex items-center justify-between gap-2 py-2 px-2.5 rounded-lg text-sm text-gray-700 hover:bg-gray-50 hover:text-accent transition-colors';
        if ( $in_row ) {
            $class .= ' flex-1 min-w-0';
        }
        echo '<a href="' . esc_url( $url ) . '" class="' . esc_attr( $class ) . '" title="' . $name_attr . '">';
        echo '<span class="truncate min-w-0">' . $name_html . '</span>';
        if ( $show_count ) {
            echo '<span class="trendtoday-cat-count flex-shrink-0 text-xs bg-gray-100 text-gray-500 rounded-full px-2 py-0.5">' . (int) $cat->count . '</span>';
        }
        echo '</a>';
    }

    public function form( $instance ) {
        $title        = ! empty( $instance['title'] ) ? $instance['title'] : __( 'หมวดหมู่', 'trendtoday' );
        $show_count   = ! empty( $instance['show_count'] );
        $hierarchical = ! empty( $instance['hierarchical'] );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'trendtoday' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_count' ) ); ?>" <?php checked( $show_count ); ?>>
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>"><?php _e( 'Show post count', 'trendtoday' ); ?></label>
        </p>
        <p>
            <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'hierarchical' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hierarchical' ) ); ?>" <?php checked( $hierarchical ); ?>>
            <label for="<?php echo esc_attr( $this->get_field_id( 'hierarchical' ) ); ?>"><?php _e( 'Show hierarchy (main categories only, click to expand subcategories)', 'trendtoday' ); ?></label>
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title']        = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['show_count']   = ! empty( $new_instance['show_count'] );
        $instance['hierarchical'] = ! empty( $new_instance['hierarchical'] );
        return $instance;
    }
}
