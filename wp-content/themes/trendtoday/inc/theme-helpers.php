<?php
/**
 * Theme helper functions
 *
 * @package TrendToday
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Get theme version
 *
 * @return string Theme version
 */
function trendtoday_get_theme_version() {
    $theme = wp_get_theme();
    return $theme->get( 'Version' );
}

/**
 * Check if post has featured image
 *
 * @param int $post_id Post ID.
 * @return bool True if has featured image, false otherwise.
 */
function trendtoday_has_featured_image( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    return has_post_thumbnail( $post_id );
}

/**
 * Get reading time for post
 *
 * @param int $post_id Post ID.
 * @return int Reading time in minutes.
 */
function trendtoday_get_reading_time( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $reading_time = get_post_meta( $post_id, 'reading_time', true );
    
    if ( $reading_time ) {
        return absint( $reading_time );
    }

    // Calculate reading time based on content
    $content = get_post_field( 'post_content', $post_id );
    $word_count = str_word_count( strip_tags( $content ) );
    $reading_time = ceil( $word_count / 200 ); // Average reading speed: 200 words per minute

    return max( 1, $reading_time );
}

/**
 * Get category color
 *
 * @param int $category_id Category ID.
 * @param string $default Default color if not set.
 * @return string Category color.
 */
function trendtoday_get_category_color( $category_id = null, $default = '#3B82F6' ) {
    // If category_id is not provided, try to get current category
    if ( ! $category_id ) {
        $categories = get_the_category();
        if ( ! empty( $categories ) ) {
            $category_id = $categories[0]->term_id;
        } else {
            return $default;
        }
    }

    // Get category color from term meta
    $color = get_term_meta( $category_id, 'category_color', true );
    if ( ! empty( $color ) ) {
        return $color;
    }

    // Fallback to default color
    return $default;
    $color = get_term_meta( $category_id, 'category_color', true );
    return $color ? $color : $default;
}

/**
 * Check if post is breaking news
 *
 * @param int $post_id Post ID.
 * @return bool True if breaking news, false otherwise.
 */
function trendtoday_is_breaking_news( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    return '1' === get_post_meta( $post_id, 'breaking_news', true );
}

/**
 * Get excerpt with custom length
 *
 * @param int $post_id Post ID.
 * @param int $length Excerpt length.
 * @param string $more More text.
 * @return string Excerpt.
 */
function trendtoday_get_excerpt( $post_id = null, $length = 20, $more = '...' ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    // Check for custom excerpt first
    $custom_excerpt = get_post_meta( $post_id, 'custom_excerpt', true );
    if ( ! empty( $custom_excerpt ) ) {
        return $custom_excerpt;
    }

    $excerpt = get_the_excerpt( $post_id );
    
    if ( empty( $excerpt ) ) {
        $content = get_post_field( 'post_content', $post_id );
        $excerpt = wp_trim_words( $content, $length, $more );
    }

    return $excerpt;
}

/**
 * Sanitize hex color
 *
 * @param string $color Color value.
 * @return string Sanitized color.
 */
function trendtoday_sanitize_hex_color( $color ) {
    if ( empty( $color ) ) {
        return '';
    }

    // Remove # if present
    $color = ltrim( $color, '#' );

    // Check if valid hex color
    if ( preg_match( '/^([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $color ) ) {
        return '#' . $color;
    }

    return '';
}

/**
 * Get social share URLs
 *
 * @param string $platform Social platform.
 * @param int $post_id Post ID.
 * @return string Share URL.
 */
function trendtoday_get_share_url( $platform, $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $url   = urlencode( get_permalink( $post_id ) );
    $title = urlencode( get_the_title( $post_id ) );

    switch ( $platform ) {
        case 'facebook':
            return 'https://www.facebook.com/sharer/sharer.php?u=' . $url;
        case 'twitter':
            return 'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title;
        case 'line':
            return 'https://social-plugins.line.me/lineit/share?url=' . $url;
        default:
            return '';
    }
}

/**
 * Check if post is video news
 *
 * @param int $post_id Post ID.
 * @return bool True if video news, false otherwise.
 */
function trendtoday_is_video_news( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    return get_post_type( $post_id ) === 'video_news';
}

/**
 * Check if post is gallery
 *
 * @param int $post_id Post ID.
 * @return bool True if gallery, false otherwise.
 */
function trendtoday_is_gallery( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    return get_post_type( $post_id ) === 'gallery';
}

/**
 * Check if post is featured story
 *
 * @param int $post_id Post ID.
 * @return bool True if featured story, false otherwise.
 */
function trendtoday_is_featured_story( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    return get_post_type( $post_id ) === 'featured_story';
}

/**
 * Get video URL from post
 *
 * @param int $post_id Post ID.
 * @return string Video URL.
 */
function trendtoday_get_video_url( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    return get_post_meta( $post_id, 'video_url', true );
}

/**
 * Get video duration
 *
 * @param int $post_id Post ID.
 * @return string Video duration.
 */
function trendtoday_get_video_duration( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    return get_post_meta( $post_id, 'video_duration', true );
}

/**
 * Get gallery images
 *
 * @param int $post_id Post ID.
 * @return array Array of image IDs.
 */
function trendtoday_get_gallery_images( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    $images = get_post_meta( $post_id, 'gallery_images', true );
    return is_array( $images ) ? $images : array();
}

/**
 * Get featured story priority
 *
 * @param int $post_id Post ID.
 * @return int Priority (1-10).
 */
function trendtoday_get_featured_priority( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    $priority = get_post_meta( $post_id, 'featured_priority', true );
    return $priority ? absint( $priority ) : 5;
}

/**
 * Get post views count
 *
 * @param int $post_id Post ID.
 * @return int View count.
 */
function trendtoday_get_post_views( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    $views = get_post_meta( $post_id, 'post_views', true );
    return $views ? absint( $views ) : 0;
}

/**
 * Increment post views
 *
 * @param int $post_id Post ID.
 */
function trendtoday_increment_post_views( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    $views = trendtoday_get_post_views( $post_id );
    update_post_meta( $post_id, 'post_views', $views + 1 );
}

/**
 * Get custom author name
 *
 * @param int $post_id Post ID.
 * @return string Author name.
 */
function trendtoday_get_author_name( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    $custom_author = get_post_meta( $post_id, 'author_name', true );
    if ( ! empty( $custom_author ) ) {
        return $custom_author;
    }
    return get_the_author();
}

/**
 * Get custom author bio
 *
 * @param int $post_id Post ID.
 * @return string Author bio.
 */
function trendtoday_get_author_bio( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    return get_post_meta( $post_id, 'author_bio', true );
}

/**
 * Get featured image alt text
 *
 * @param int $post_id Post ID.
 * @return string Alt text.
 */
function trendtoday_get_featured_image_alt( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    $alt = get_post_meta( $post_id, 'featured_image_alt', true );
    if ( ! empty( $alt ) ) {
        return $alt;
    }
    // Fallback to attachment alt text
    $thumbnail_id = get_post_thumbnail_id( $post_id );
    if ( $thumbnail_id ) {
        return get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );
    }
    return '';
}

/**
 * Get social sharing image
 *
 * @param int $post_id Post ID.
 * @return int|false Attachment ID or false.
 */
function trendtoday_get_social_sharing_image( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    $image_id = get_post_meta( $post_id, 'social_sharing_image', true );
    if ( $image_id ) {
        return absint( $image_id );
    }
    // Fallback to featured image
    return get_post_thumbnail_id( $post_id );
}

/**
 * Get related posts
 *
 * @param int $post_id Post ID.
 * @return array Array of post IDs.
 */
function trendtoday_get_related_posts( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    $related = get_post_meta( $post_id, 'related_posts', true );
    return is_array( $related ) ? $related : array();
}

/**
 * Get SEO meta title
 *
 * @param int $post_id Post ID.
 * @return string Meta title.
 */
function trendtoday_get_meta_title( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    $meta_title = get_post_meta( $post_id, 'meta_title', true );
    if ( ! empty( $meta_title ) ) {
        return $meta_title;
    }
    return get_the_title( $post_id );
}

/**
 * Get SEO meta description
 *
 * @param int $post_id Post ID.
 * @return string Meta description.
 */
function trendtoday_get_meta_description( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    $meta_description = get_post_meta( $post_id, 'meta_description', true );
    if ( ! empty( $meta_description ) ) {
        return $meta_description;
    }
    return trendtoday_get_excerpt( $post_id, 25 );
}

/**
 * Get SEO meta keywords
 *
 * @param int $post_id Post ID.
 * @return string Meta keywords.
 */
function trendtoday_get_meta_keywords( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    return get_post_meta( $post_id, 'meta_keywords', true );
}

/**
 * Get flexible image URL that works with any site URL
 * Converts absolute URLs to use current site URL
 *
 * @param string|int $url_or_id Image URL or attachment ID.
 * @param string $size Image size.
 * @return string|array Image URL or array with image data.
 */
function trendtoday_get_flexible_image_url( $url_or_id, $size = 'full' ) {
    // If it's an attachment ID, get the image URL
    if ( is_numeric( $url_or_id ) ) {
        $image = wp_get_attachment_image_src( $url_or_id, $size );
        if ( $image ) {
            return trendtoday_fix_image_url( $image[0] );
        }
        return '';
    }
    
    // If it's already a URL, fix it
    return trendtoday_fix_image_url( $url_or_id );
}

/**
 * Fix image URL to use current site URL instead of hardcoded URL
 * This ensures images work when site URL changes
 *
 * @param string $url Image URL.
 * @return string Fixed image URL.
 */
function trendtoday_fix_image_url( $url ) {
    if ( empty( $url ) ) {
        return $url;
    }
    
    // If it's already a relative URL, make it absolute using current site URL
    if ( strpos( $url, 'http' ) !== 0 ) {
        // It's a relative URL, prepend site URL
        return esc_url( home_url( $url ) );
    }
    
    // It's an absolute URL, check if it's from our site
    $site_url = home_url();
    $parsed_url = parse_url( $url );
    $parsed_site_url = parse_url( $site_url );
    
    // If the domain matches, replace with current site URL
    if ( isset( $parsed_url['host'] ) && isset( $parsed_site_url['host'] ) ) {
        // Extract path from old URL
        $path = isset( $parsed_url['path'] ) ? $parsed_url['path'] : '';
        $query = isset( $parsed_url['query'] ) ? '?' . $parsed_url['query'] : '';
        
        // Rebuild URL with current site URL
        return esc_url( $site_url . $path . $query );
    }
    
    // External URL or couldn't parse, return as is
    return esc_url( $url );
}

/**
 * Get flexible attachment image src
 * Wrapper for wp_get_attachment_image_src with URL fixing
 *
 * @param int $attachment_id Attachment ID.
 * @param string $size Image size.
 * @return array|false Image data array or false.
 */
function trendtoday_get_attachment_image_src( $attachment_id, $size = 'full' ) {
    $image = wp_get_attachment_image_src( $attachment_id, $size );
    if ( $image && isset( $image[0] ) ) {
        $image[0] = trendtoday_fix_image_url( $image[0] );
    }
    return $image;
}

/**
 * Filter attachment URLs to use current site URL
 * This ensures images work when site URL changes
 *
 * @param string $url Attachment URL.
 * @param int $post_id Attachment ID.
 * @return string Fixed attachment URL.
 */
function trendtoday_filter_attachment_url( $url, $post_id ) {
    return trendtoday_fix_image_url( $url );
}
add_filter( 'wp_get_attachment_url', 'trendtoday_filter_attachment_url', 10, 2 );

/**
 * Filter image srcset URLs to use current site URL
 *
 * @param array $sources Image sources array.
 * @param array $size_array Array of width and height values.
 * @param string $image_src Image source URL.
 * @param array $image_meta Image metadata.
 * @param int $attachment_id Attachment ID.
 * @return array Fixed image sources.
 */
function trendtoday_filter_image_srcset( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
    if ( is_array( $sources ) ) {
        foreach ( $sources as &$source ) {
            if ( isset( $source['url'] ) ) {
                $source['url'] = trendtoday_fix_image_url( $source['url'] );
            }
        }
    }
    return $sources;
}
add_filter( 'wp_calculate_image_srcset', 'trendtoday_filter_image_srcset', 10, 5 );

/**
 * Filter custom logo URL to use current site URL
 *
 * @param string $html Custom logo HTML.
 * @return string Fixed custom logo HTML.
 */
function trendtoday_filter_custom_logo( $html ) {
    if ( empty( $html ) ) {
        return $html;
    }
    
    // Extract URL from img src
    preg_match( '/<img[^>]+src=["\']([^"\']+)["\']/', $html, $matches );
    if ( isset( $matches[1] ) ) {
        $old_url = $matches[1];
        $new_url = trendtoday_fix_image_url( $old_url );
        $html = str_replace( $old_url, $new_url, $html );
    }
    
    return $html;
}
add_filter( 'get_custom_logo', 'trendtoday_filter_custom_logo', 10, 1 );

/**
 * Filter post thumbnail HTML to fix image URLs
 *
 * @param string $html Post thumbnail HTML.
 * @param int $post_id Post ID.
 * @param int $post_thumbnail_id Post thumbnail ID.
 * @param string $size Image size.
 * @param array $attr Image attributes.
 * @return string Fixed post thumbnail HTML.
 */
function trendtoday_filter_post_thumbnail_html( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
    if ( empty( $html ) ) {
        return $html;
    }
    
    // Extract all image URLs from the HTML
    preg_match_all( '/<img[^>]+src=["\']([^"\']+)["\']/', $html, $matches );
    if ( ! empty( $matches[1] ) ) {
        foreach ( $matches[1] as $old_url ) {
            $new_url = trendtoday_fix_image_url( $old_url );
            $html = str_replace( $old_url, $new_url, $html );
        }
    }
    
    // Also fix srcset URLs
    preg_match_all( '/srcset=["\']([^"\']+)["\']/', $html, $srcset_matches );
    if ( ! empty( $srcset_matches[1] ) ) {
        foreach ( $srcset_matches[1] as $srcset ) {
            $urls = explode( ',', $srcset );
            $fixed_urls = array();
            foreach ( $urls as $url_part ) {
                $url_part = trim( $url_part );
                $parts = explode( ' ', $url_part );
                if ( ! empty( $parts[0] ) ) {
                    $parts[0] = trendtoday_fix_image_url( $parts[0] );
                    $fixed_urls[] = implode( ' ', $parts );
                } else {
                    $fixed_urls[] = $url_part;
                }
            }
            $new_srcset = implode( ', ', $fixed_urls );
            $html = str_replace( $srcset, $new_srcset, $html );
        }
    }
    
    return $html;
}
add_filter( 'post_thumbnail_html', 'trendtoday_filter_post_thumbnail_html', 10, 5 );

/**
 * Filter attachment image HTML to fix image URLs
 *
 * @param string $html Attachment image HTML.
 * @param int $attachment_id Attachment ID.
 * @param string|array $size Image size.
 * @param bool $icon Whether to show icon.
 * @param string|array $attr Image attributes.
 * @return string Fixed attachment image HTML.
 */
function trendtoday_filter_attachment_image_html( $html, $attachment_id, $size, $icon, $attr ) {
    if ( empty( $html ) ) {
        return $html;
    }
    
    // Extract all image URLs from the HTML
    preg_match_all( '/<img[^>]+src=["\']([^"\']+)["\']/', $html, $matches );
    if ( ! empty( $matches[1] ) ) {
        foreach ( $matches[1] as $old_url ) {
            $new_url = trendtoday_fix_image_url( $old_url );
            $html = str_replace( $old_url, $new_url, $html );
        }
    }
    
    // Also fix srcset URLs
    preg_match_all( '/srcset=["\']([^"\']+)["\']/', $html, $srcset_matches );
    if ( ! empty( $srcset_matches[1] ) ) {
        foreach ( $srcset_matches[1] as $srcset ) {
            $urls = explode( ',', $srcset );
            $fixed_urls = array();
            foreach ( $urls as $url_part ) {
                $url_part = trim( $url_part );
                $parts = explode( ' ', $url_part );
                if ( ! empty( $parts[0] ) ) {
                    $parts[0] = trendtoday_fix_image_url( $parts[0] );
                    $fixed_urls[] = implode( ' ', $parts );
                } else {
                    $fixed_urls[] = $url_part;
                }
            }
            $new_srcset = implode( ', ', $fixed_urls );
            $html = str_replace( $srcset, $new_srcset, $html );
        }
    }
    
    return $html;
}
add_filter( 'wp_get_attachment_image', 'trendtoday_filter_attachment_image_html', 10, 5 );

/**
 * Filter post content to fix image URLs
 * This ensures images in post content work when site URL changes
 *
 * @param string $content Post content.
 * @return string Fixed post content.
 */
function trendtoday_filter_post_content( $content ) {
    if ( empty( $content ) ) {
        return $content;
    }
    
    // Fix img src attributes
    $content = preg_replace_callback(
        '/<img([^>]+)src=["\']([^"\']+)["\']([^>]*)>/i',
        function( $matches ) {
            $before_attrs = $matches[1];
            $url = $matches[2];
            $after_attrs = $matches[3];
            $fixed_url = trendtoday_fix_image_url( $url );
            return '<img' . $before_attrs . 'src="' . esc_attr( $fixed_url ) . '"' . $after_attrs . '>';
        },
        $content
    );
    
    // Fix background-image URLs in style attributes
    $content = preg_replace_callback(
        '/style=["\']([^"\']*background[^"\']*url\([^)]+\)[^"\']*)["\']/i',
        function( $matches ) {
            $style = $matches[1];
            $style = preg_replace_callback(
                '/url\(["\']?([^"\'()]+)["\']?\)/i',
                function( $url_matches ) {
                    $url = $url_matches[1];
                    $fixed_url = trendtoday_fix_image_url( $url );
                    return 'url("' . esc_attr( $fixed_url ) . '")';
                },
                $style
            );
            return 'style="' . esc_attr( $style ) . '"';
        },
        $content
    );
    
    return $content;
}
add_filter( 'the_content', 'trendtoday_filter_post_content', 20, 1 );