<?php
/**
 * Login page customizer - match Trend Today theme design
 *
 * @package TrendToday
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue custom styles and font on login page
 */
function trendtoday_login_enqueue_scripts() {
	wp_enqueue_style(
		'trendtoday-login-font',
		'https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600;700&display=swap',
		array(),
		null
	);
	wp_enqueue_style(
		'trendtoday-login',
		get_template_directory_uri() . '/assets/css/login.css',
		array( 'trendtoday-login-font' ),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'login_enqueue_scripts', 'trendtoday_login_enqueue_scripts' );

/**
 * Logo link goes to site home
 */
function trendtoday_login_headerurl( $url ) {
	return home_url( '/' );
}
add_filter( 'login_headerurl', 'trendtoday_login_headerurl' );

/**
 * Logo title/alt text
 */
function trendtoday_login_headertext( $title ) {
	return get_bloginfo( 'name' );
}
add_filter( 'login_headertext', 'trendtoday_login_headertext' );

/**
 * Output custom logo and inline styles for login header (when theme logo is set)
 */
function trendtoday_login_head() {
	$logo_id = get_option( 'trendtoday_logo', '' );
	if ( ! $logo_id ) {
		return;
	}
	$logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
	if ( ! $logo_url ) {
		return;
	}
	?>
	<style type="text/css">
		.login h1 a {
			background-image: url(<?php echo esc_url( $logo_url ); ?>);
			background-size: contain;
			background-position: center;
			width: 100%;
			max-width: 280px;
			height: 60px;
		}
	</style>
	<?php
}
add_action( 'login_head', 'trendtoday_login_head' );
