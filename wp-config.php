<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'driver_wp' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'password' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'ZE}BZ<i*{bu4)?gf=qe)N>,-UeBJoF(8LdLGi(+DF)Ls+6-SmtRbHaM>t^-AR#{D');
define('SECURE_AUTH_KEY',  ')uq#_Nb/P~qVqp-O}gIybT?NV|kkez.Rk6omOtC..O/3-,8)Q8:MKEU-Y0/P73q4');
define('LOGGED_IN_KEY',    ':0;Cl0+U5;*?;>a+BC zz-/kJ%qCa;F45ut%Ole=d|y*`D9DF0|ef28G[*kDTllO');
define('NONCE_KEY',        'ua+drtE-xc]z;BB{!$lqWBAO6h:},H5LyEj7C$!tBFwGb{K9(e8Na+cCF*Rd+[ql');
define('AUTH_SALT',        'FSL% CjJG7hq2~fbDGZ0@y;IoOsz(79pw>N@<#K[4O#nJa!i(WN-FKMrE]-_m!!+');
define('SECURE_AUTH_SALT', 'XCW7CF{;Q#fCg=~Zd1bdexc[Sgvj-yMdIW|43?4jNM6NXK|-+l,d%a;C$Dq~h!7F');
define('LOGGED_IN_SALT',   '$(?z|.$zEW#WeI_)-1,k]:$Q +?bzs0:@?zlC&s+PD|U_=S;*[x/d&P]o*W#_CTi');
define('NONCE_SALT',       ',9VS(M::0^q^~?s8+/vBa+lT:=|Ma&ji%p8O9]WC-=as-L`R#Z:5b~FU%FKb-fZ@');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'dr_vp_';

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
