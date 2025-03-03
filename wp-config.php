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
define( 'DB_NAME', 'templateWP' );

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
define( 'AUTH_KEY',         '<| g$n?)j:-]Os34a3 !=mXeFsALwCd8O<NW[JqTHE.6veArvpuCF_7j?myU$^pv' );
define( 'SECURE_AUTH_KEY',  '}J8eL/,KZ<?~9dt/M>odWOfUcNGBlaH|t`[U+Y2:4|t*^`q$CdeFx1j:=,pV)g}C' );
define( 'LOGGED_IN_KEY',    'b5y{x+u_>>AIP8Ue3g@DX<)pH`9&H>QD9^`/SqG7V:G[:0V>.wqVg+27[^8y0+E>' );
define( 'NONCE_KEY',        '=sB)byCO*rsH7Xrg$GLR]pjTs/!b;&3@[<j*#hA0rwzS2B0).BkFJNO#wBOkVL K' );
define( 'AUTH_SALT',        '!fDw|W^mdg*T!4KJv]1p`pwaaTX%!>X$e;x~Db$qGEih9sSWe}xI-]F8OmMtmpw]' );
define( 'SECURE_AUTH_SALT', 'k<UYp[C%uARB.}d=$HKN-7,J5sf/mbU/ri8{eD_=47}fL<DA_G,FJi,MYx.?_OEp' );
define( 'LOGGED_IN_SALT',   ')D}:qJS2#{D=.P>dA(GJ#Ow.9H[/H]@U&9kQ=Dx7)]k>3x;B+ue|Wch}ws rBIXR' );
define( 'NONCE_SALT',       'C61nkoN<^RMM&tR1e`hk= U1P(n67K(VuDIl ]a?2cD6*A}Q:tE*baJ W)L|CtW_' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', true );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
