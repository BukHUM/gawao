<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'gawao' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         ',W3DrN#@s`` ebdMP.[?mye9>iD6Py%WN{+*4f&>]wx9v.=C=k B;/~#*eY%bBvN' );
define( 'SECURE_AUTH_KEY',  'PYi&%_-{3@bulS 9d=B~m4SQp#<Xr$CjrYqU}kdAD<PS6 I(+$h)CGG,|8?1qj&k' );
define( 'LOGGED_IN_KEY',    '{R4H;bpra@Z<{4Z>.T6nT0X-lVL$^1lWfWt*e?kP?$P*E(_z_@EI}SF]M98deYP_' );
define( 'NONCE_KEY',        'Y*|{+*qs{{VhX#N_iy8f1Qapg[o}2?;u%0J}})R/beTyApXl+:hF4YY<(_)#{I8{' );
define( 'AUTH_SALT',        'D`CQ>XtQz!g*{-`u=5:1qs{<c=C*Qu$NCWO%X%Og2+SwRtm<#m2J_XBTUovJWqz9' );
define( 'SECURE_AUTH_SALT', 'x55kc=|:6%[V&4W+8C^F3oGD$6Wda##A/W2JqK&Q%QWxJ3$c@4ee{>HA#.|306[f' );
define( 'LOGGED_IN_SALT',   'bb;51^%&ea~D|H_v!A27o=Bvj+74JYHsk_e:`57{c@UV/&uoJ:,o0`m4EY-b,%Y-' );
define( 'NONCE_SALT',       '[1>?0M?{5M?T^VlaS5PzSAscCa~X;(DrVTxzzV>2X~.=;Bf^5[}tU)Q=mVhzw6}.' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'pplove_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
