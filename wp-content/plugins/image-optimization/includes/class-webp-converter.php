<?php
/**
 * WebP Converter Class
 * Handles WebP conversion and serving
 *
 * @package Image_Optimization
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WebP Converter Class
 */
class IO_WebP_Converter {

	/**
	 * Instance
	 *
	 * @var IO_WebP_Converter
	 */
	private static $instance = null;

	/**
	 * Get instance
	 *
	 * @return IO_WebP_Converter
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		// Convert to WebP after upload
		add_filter( 'wp_generate_attachment_metadata', array( $this, 'convert_to_webp' ), 10, 2 );

		// Replace image URLs with WebP URLs (if browser supports WebP)
		add_filter( 'wp_get_attachment_image_url', array( $this, 'replace_with_webp_url' ), 10, 3 );
		add_filter( 'wp_get_attachment_image_src', array( $this, 'replace_with_webp_src' ), 10, 4 );

		// Use picture tag for WebP support in wp_get_attachment_image
		add_filter( 'wp_get_attachment_image', array( $this, 'add_webp_picture_tag' ), 10, 5 );

		// Replace img src in post content with WebP when available
		add_filter( 'the_content', array( $this, 'replace_webp_in_content' ), 15, 1 );
	}

	/**
	 * Check if WebP is supported by server
	 *
	 * @return bool True if WebP is supported.
	 */
	public function webp_supported() {
		// Check if GD library supports WebP
		if ( function_exists( 'imagewebp' ) ) {
			return true;
		}

		// Check if Imagick supports WebP
		if ( extension_loaded( 'imagick' ) && class_exists( 'Imagick' ) ) {
			$imagick = new Imagick();
			$formats = $imagick->queryFormats();
			if ( in_array( 'WEBP', $formats, true ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Convert image to WebP after upload
	 *
	 * @param array $metadata Attachment metadata.
	 * @param int   $attachment_id Attachment ID.
	 * @return array Modified metadata.
	 */
	public function convert_to_webp( $metadata, $attachment_id ) {
		// Validate input
		if ( ! is_array( $metadata ) ) {
			$metadata = array();
		}

		$attachment_id = absint( $attachment_id );
		if ( $attachment_id <= 0 ) {
			return $metadata;
		}

		// Check if WebP conversion is enabled
		$webp_enabled = get_option( 'io_image_webp_enabled', '1' );
		if ( $webp_enabled !== '1' ) {
			return $metadata;
		}

		// Check if WebP is supported
		if ( ! $this->webp_supported() ) {
			return $metadata;
		}

		// Get attachment file
		$file = get_attached_file( $attachment_id );
		if ( ! $file || ! is_string( $file ) || ! file_exists( $file ) ) {
			return $metadata;
		}

		// Check if it's an image
		$image_types = array( 'image/jpeg', 'image/jpg', 'image/png' );
		$mime_type   = get_post_mime_type( $attachment_id );
		if ( ! $mime_type || ! in_array( $mime_type, $image_types, true ) ) {
			return $metadata;
		}

		// Skip if already WebP
		if ( $mime_type === 'image/webp' ) {
			return $metadata;
		}

		// Get WebP quality
		$webp_quality = absint( get_option( 'io_image_webp_quality', 85 ) );
		$webp_quality = max( 0, min( 100, $webp_quality ) );

		// Create WebP version
		$webp_file = preg_replace( '/\.(jpg|jpeg|png)$/i', '.webp', $file );
		if ( ! $webp_file || $webp_file === $file ) {
			return $metadata;
		}

		// Load image
		$image = wp_get_image_editor( $file );
		if ( is_wp_error( $image ) ) {
			error_log( '[Image Optimization] Failed to load image for WebP conversion: ' . $image->get_error_message() );
			return $metadata;
		}

		// Convert to WebP
		$converted = $image->save( $webp_file, 'image/webp' );
		if ( is_wp_error( $converted ) ) {
			error_log( '[Image Optimization] Failed to convert to WebP: ' . $converted->get_error_message() );
			return $metadata;
		}

		// Validate converted result
		if ( ! is_array( $converted ) || ! isset( $converted['file'] ) ) {
			error_log( '[Image Optimization] Invalid WebP conversion result' );
			return $metadata;
		}

		// Verify WebP file was created
		if ( ! file_exists( $webp_file ) ) {
			error_log( '[Image Optimization] WebP file was not created: ' . $webp_file );
			return $metadata;
		}

		// Store WebP file path in metadata
		if ( ! isset( $metadata['sizes'] ) ) {
			$metadata['sizes'] = array();
		}

		// Add WebP version to sizes
		$metadata['sizes']['webp'] = array(
			'file'      => basename( $webp_file ),
			'width'     => isset( $converted['width'] ) ? absint( $converted['width'] ) : 0,
			'height'    => isset( $converted['height'] ) ? absint( $converted['height'] ) : 0,
			'mime-type' => 'image/webp',
		);

		// Update attachment meta
		update_post_meta( $attachment_id, '_webp_file', $webp_file );

		return $metadata;
	}

	/**
	 * Get WebP URL for attachment
	 *
	 * @param string $url Original image URL.
	 * @param int    $attachment_id Attachment ID.
	 * @return string WebP URL or original URL.
	 */
	public function get_webp_url( $url, $attachment_id ) {
		// Check if WebP is enabled and supported
		$webp_enabled = get_option( 'io_image_webp_enabled', '1' );
		if ( $webp_enabled !== '1' || ! $this->webp_supported() ) {
			return $url;
		}

		// Check if WebP version exists
		$webp_file = get_post_meta( $attachment_id, '_webp_file', true );
		if ( ! $webp_file || ! file_exists( $webp_file ) ) {
			return $url;
		}

		// Get upload directory
		$upload_dir = wp_upload_dir();
		$webp_path  = str_replace( $upload_dir['basedir'], $upload_dir['baseurl'], $webp_file );

		return $webp_path;
	}

	/**
	 * Replace attachment image URL with WebP URL
	 *
	 * @param string|false $url         Image URL or false.
	 * @param int          $attachment_id Attachment ID.
	 * @param string|int[] $size        Image size.
	 * @return string|false WebP URL or original URL.
	 */
	public function replace_with_webp_url( $url, $attachment_id, $size ) {
		if ( ! $url ) {
			return $url;
		}

		$webp_url = $this->get_webp_url( $url, $attachment_id );
		return $webp_url !== $url ? $webp_url : $url;
	}

	/**
	 * Replace attachment image src with WebP src
	 *
	 * @param array|false  $image         Image data array or false.
	 * @param int          $attachment_id Attachment ID.
	 * @param string|int[] $size          Image size.
	 * @param bool         $icon          Whether the image should be treated as an icon.
	 * @return array|false Modified image data or original.
	 */
	public function replace_with_webp_src( $image, $attachment_id, $size, $icon ) {
		if ( ! $image || ! is_array( $image ) || ! isset( $image[0] ) ) {
			return $image;
		}

		$webp_url = $this->get_webp_url( $image[0], $attachment_id );
		if ( $webp_url !== $image[0] ) {
			$image[0] = $webp_url;
		}

		return $image;
	}

	/**
	 * Add picture tag with WebP support to wp_get_attachment_image
	 *
	 * @param string       $html          Image HTML.
	 * @param int          $attachment_id Attachment ID.
	 * @param string|int[] $size          Image size.
	 * @param bool         $icon          Whether the image should be treated as an icon.
	 * @param array|string $attr          Image attributes.
	 * @return string Modified HTML with picture tag.
	 */
	public function add_webp_picture_tag( $html, $attachment_id, $size, $icon, $attr ) {
		// Check if WebP is enabled and supported
		$webp_enabled = get_option( 'io_image_webp_enabled', '1' );
		if ( $webp_enabled !== '1' || ! $this->webp_supported() ) {
			return $html;
		}

		// Check if WebP version exists
		$webp_file = get_post_meta( $attachment_id, '_webp_file', true );
		if ( ! $webp_file || ! file_exists( $webp_file ) ) {
			return $html;
		}

		// Get original image URL (before our filter modifies it)
		remove_filter( 'wp_get_attachment_image_url', array( $this, 'replace_with_webp_url' ), 10 );
		$original_url = wp_get_attachment_image_url( $attachment_id, $size, $icon );
		add_filter( 'wp_get_attachment_image_url', array( $this, 'replace_with_webp_url' ), 10, 3 );

		if ( ! $original_url ) {
			return $html;
		}

		// Get WebP URL
		$webp_url = $this->get_webp_url( $original_url, $attachment_id );
		if ( $webp_url === $original_url ) {
			return $html;
		}

		// Extract img tag from HTML
		if ( ! preg_match( '/<img[^>]+>/i', $html, $matches ) ) {
			return $html;
		}

		$img_tag = $matches[0];

		// Extract attributes from img tag
		preg_match_all( '/(\w+)=["\']([^"\']*)["\']/', $img_tag, $attr_matches );
		$attributes = array();
		foreach ( $attr_matches[1] as $key => $attr_name ) {
			$attributes[ $attr_name ] = $attr_matches[2][ $key ];
		}

		// Get original src
		$original_src = isset( $attributes['src'] ) ? $attributes['src'] : $original_url;

		// Build picture tag
		$picture_html = '<picture>';
		$picture_html .= '<source srcset="' . esc_url( $webp_url ) . '" type="image/webp">';
		$picture_html .= '<img';
		foreach ( $attributes as $attr_name => $attr_value ) {
			if ( $attr_name === 'src' ) {
				$picture_html .= ' src="' . esc_url( $original_src ) . '"';
			} else {
				$picture_html .= ' ' . esc_attr( $attr_name ) . '="' . esc_attr( $attr_value ) . '"';
			}
		}
		$picture_html .= '>';
		$picture_html .= '</picture>';

		return $picture_html;
	}

	/**
	 * Replace img src in post content with WebP URL when attachment has WebP
	 *
	 * @param string $content Post content.
	 * @return string Modified content.
	 */
	public function replace_webp_in_content( $content ) {
		if ( ! is_string( $content ) || '' === $content ) {
			return $content;
		}

		$webp_enabled = get_option( 'io_image_webp_enabled', '1' );
		if ( $webp_enabled !== '1' || ! $this->webp_supported() ) {
			return $content;
		}

		// Match img tags with src pointing to uploads
		if ( ! preg_match_all( '/<img\s[^>]*\ssrc=["\']([^"\']+)["\'][^>]*>/i', $content, $matches, PREG_SET_ORDER ) ) {
			return $content;
		}

		$upload_dir = wp_upload_dir();
		$base_url   = $upload_dir['baseurl'];

		foreach ( $matches as $match ) {
			$full_tag = $match[0];
			$src_url  = $match[1];

			// Only process URLs from our uploads directory
			if ( strpos( $src_url, $base_url ) !== 0 ) {
				continue;
			}

			$attachment_id = attachment_url_to_postid( $src_url );
			if ( ! $attachment_id ) {
				continue;
			}

			$webp_url = $this->get_webp_url( $src_url, $attachment_id );
			if ( $webp_url === $src_url ) {
				continue;
			}

			// Replace src with WebP URL in this img tag
			$new_tag = str_replace( $src_url, $webp_url, $full_tag );
			$content = str_replace( $full_tag, $new_tag, $content );
		}

		return $content;
	}
}
